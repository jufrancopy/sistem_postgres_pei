<?php

namespace App\Application\Bioestadistica\Imports;

use Illuminate\Support\Str;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;
use Throwable;

class ExcelImportAnalyzer
{
    private const SAMPLE_LIMIT = 50;

    private const HEADER_SCAN_LIMIT = 100;

    /**
     * Analiza un libro local sin importar datos ni acceder a la base de datos.
     */
    public function analyze(string $path): array
    {
        $this->assertReadableExcel($path);

        $spreadsheet = null;

        try {
            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $reader->setIncludeCharts(false);

            $spreadsheet = $reader->load($path);
            $sheets = [];
            $warnings = [];

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $sheet = $this->analyzeWorksheet($worksheet);
                $sheets[] = $sheet;

                foreach ($sheet['advertencias'] as $warning) {
                    $warnings[] = $warning;
                }
            }

            if ($sheets === []) {
                $warnings[] = 'El libro no contiene hojas analizables.';
            }

            return [
                'tipo' => $this->detectWorkbookType($sheets),
                'hojas' => $sheets,
                'cabeceras' => $this->indexSheetField($sheets, 'cabeceras'),
                'columnas' => $this->indexSheetField($sheets, 'columnas'),
                'advertencias' => array_values(array_unique($warnings)),
            ];
        } catch (InvalidArgumentException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'No se pudo analizar el archivo Excel: '.$exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        } finally {
            if ($spreadsheet instanceof Spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }

            unset($spreadsheet);
        }
    }

    private function assertReadableExcel(string $path): void
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new InvalidArgumentException('El archivo Excel local no existe o no es legible.');
        }

        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($extension, ['xls', 'xlsx'], true)) {
            throw new InvalidArgumentException('Solo se admiten archivos locales .xls o .xlsx.');
        }
    }

    private function analyzeWorksheet(Worksheet $worksheet): array
    {
        $name = $this->normalizeHeader($worksheet->getTitle());
        $lastRow = max(0, $worksheet->getHighestDataRow());
        $lastColumnLetter = $worksheet->getHighestDataColumn();
        $lastColumn = $lastRow > 0
            ? Coordinate::columnIndexFromString($lastColumnLetter)
            : 0;
        $warnings = [];

        if ($lastRow === 0 || $lastColumn === 0 || ! $this->worksheetHasData($worksheet, $lastRow, $lastColumn)) {
            $warning = "La hoja «{$name}» está vacía.";

            return [
                'nombre' => $name,
                'filas' => 0,
                'cantidad_columnas' => 0,
                'fila_cabecera' => null,
                'cabeceras' => [],
                'columnas' => [],
                'advertencias' => [$warning],
            ];
        }

        [$headerRow, $usedFallback] = $this->findHeaderRow($worksheet, $lastRow, $lastColumn);
        if ($headerRow === null) {
            $warning = "No se pudo identificar una fila de encabezado en la hoja «{$name}».";

            return [
                'nombre' => $name,
                'filas' => $lastRow,
                'cantidad_columnas' => $lastColumn,
                'fila_cabecera' => null,
                'cabeceras' => [],
                'columnas' => [],
                'advertencias' => [$warning],
            ];
        }

        if ($usedFallback) {
            $warnings[] = "La hoja «{$name}» no tenía una fila con mayoría de celdas no vacías y no numéricas; se usó la mejor candidata (fila {$headerRow}).";
        }

        $headers = $this->headers($worksheet, $headerRow, $lastColumn, $name, $warnings);
        $samples = $this->samples($worksheet, $headerRow, $lastRow, array_keys($headers));
        $columns = [];
        $serializedHeaders = [];

        foreach ($headers as $index => $header) {
            $inference = $this->inferType($samples[$index] ?? []);
            $serializedHeaders[] = [
                'indice' => $index,
                'letra' => Coordinate::stringFromColumnIndex($index),
                'nombre' => $header['nombre'],
                'codigo' => $header['codigo'],
            ];
            $columns[] = [
                'indice' => $index,
                'letra' => Coordinate::stringFromColumnIndex($index),
                'cabecera' => $header['nombre'],
                'codigo' => $header['codigo'],
                'tipo' => $inference['tipo'],
                'muestras' => array_column($samples[$index] ?? [], 'valor'),
                'opciones' => $inference['opciones'],
            ];

            if (($samples[$index] ?? []) === []) {
                $warnings[] = "La columna «{$header['nombre']}» de la hoja «{$name}» no tiene datos para inferir su tipo.";
            }
        }

        return [
            'nombre' => $name,
            'filas' => $lastRow,
            'cantidad_columnas' => $lastColumn,
            'fila_cabecera' => $headerRow,
            'cabeceras' => $serializedHeaders,
            'columnas' => $columns,
            'advertencias' => array_values(array_unique($warnings)),
        ];
    }

    private function worksheetHasData(Worksheet $worksheet, int $lastRow, int $lastColumn): bool
    {
        $scanRows = min($lastRow, self::HEADER_SCAN_LIMIT);

        for ($row = 1; $row <= $scanRows; $row++) {
            for ($column = 1; $column <= $lastColumn; $column++) {
                if (! $this->isEmpty($worksheet->getCellByColumnAndRow($column, $row)->getValue())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array{0: int|null, 1: bool}
     */
    private function findHeaderRow(Worksheet $worksheet, int $lastRow, int $lastColumn): array
    {
        $scanRows = min($lastRow, self::HEADER_SCAN_LIMIT);
        $bestRow = null;
        $bestScore = -1.0;

        for ($row = 1; $row <= $scanRows; $row++) {
            $nonEmpty = 0;
            $nonNumeric = 0;

            for ($column = 1; $column <= $lastColumn; $column++) {
                $value = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                if ($this->isEmpty($value)) {
                    continue;
                }

                $nonEmpty++;
                if (! $this->isNumericValue($value)) {
                    $nonNumeric++;
                }
            }

            if ($nonEmpty === 0) {
                continue;
            }

            $score = ($nonEmpty / $lastColumn) + ($nonNumeric / $nonEmpty);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $row;
            }

            if ($nonEmpty > ($lastColumn / 2) && $nonNumeric > ($nonEmpty / 2)) {
                return [$row, false];
            }
        }

        return [$bestRow, $bestRow !== null];
    }

    /**
     * @param  array<int, string>  $warnings
     * @return array<int, array{nombre: string, codigo: string}>
     */
    private function headers(
        Worksheet $worksheet,
        int $headerRow,
        int $lastColumn,
        string $sheetName,
        array &$warnings
    ): array {
        $headers = [];
        $usedCodes = [];

        for ($column = 1; $column <= $lastColumn; $column++) {
            $raw = $worksheet->getCellByColumnAndRow($column, $headerRow)->getValue();
            $header = $this->normalizeHeader($this->scalarString($raw));

            if ($header === '') {
                continue;
            }

            $baseCode = $this->normalizeCode($header);
            $code = $baseCode;
            $suffix = 2;

            while (isset($usedCodes[$code])) {
                $code = "{$baseCode}_{$suffix}";
                $suffix++;
            }

            if ($code !== $baseCode) {
                $warnings[] = "La cabecera duplicada «{$header}» de la hoja «{$sheetName}» se normalizó como «{$code}».";
            }

            $usedCodes[$code] = true;
            $headers[$column] = [
                'nombre' => $header,
                'codigo' => $code,
            ];
        }

        return $headers;
    }

    /**
     * @param  array<int>  $columns
     * @return array<int, array<int, array{valor: scalar, clase: string}>>
     */
    private function samples(
        Worksheet $worksheet,
        int $headerRow,
        int $lastRow,
        array $columns
    ): array {
        $samples = array_fill_keys($columns, []);
        $sampledRows = 0;

        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            if ($sampledRows >= self::SAMPLE_LIMIT) {
                break;
            }

            $rowSamples = [];
            foreach ($columns as $column) {
                $sample = $this->sampleCell($worksheet, $column, $row);
                if ($sample !== null) {
                    $rowSamples[$column] = $sample;
                }
            }

            if ($rowSamples === []) {
                continue;
            }

            foreach ($rowSamples as $column => $sample) {
                $samples[$column][] = $sample;
            }

            $sampledRows++;
        }

        return $samples;
    }

    /**
     * @return array{valor: scalar, clase: string}|null
     */
    private function sampleCell(Worksheet $worksheet, int $column, int $row): ?array
    {
        $cell = $worksheet->getCellByColumnAndRow($column, $row);
        $value = $cell->getValue();

        if ($this->isEmpty($value)) {
            return null;
        }

        if (is_numeric($value) && ExcelDate::isDateTime($cell)) {
            $date = ExcelDate::excelToDateTimeObject((float) $value);
            $isTime = (float) $value >= 0 && (float) $value < 1;

            return [
                'valor' => $date->format($isTime ? 'H:i:s' : 'Y-m-d'),
                'clase' => $isTime ? 'time' : 'date',
            ];
        }

        $serialized = $this->serializableValue($value);

        return [
            'valor' => $serialized,
            'clase' => $this->classifyValue($serialized),
        ];
    }

    /**
     * @param  array<int, array{valor: scalar, clase: string}>  $samples
     * @return array{tipo: string, opciones: array<int, scalar>}
     */
    private function inferType(array $samples): array
    {
        if ($samples === []) {
            return ['tipo' => 'text', 'opciones' => []];
        }

        $classes = array_column($samples, 'clase');
        $booleanNumbers = array_filter(
            $samples,
            static fn (array $sample): bool => in_array($sample['valor'], [0, 1, '0', '1'], true)
        );
        $all = static fn (string $class): bool => count(array_filter(
            $classes,
            static fn (string $candidate): bool => $candidate === $class
        )) === count($classes);

        if ($all('boolean') || count($booleanNumbers) === count($samples)) {
            return ['tipo' => 'boolean', 'opciones' => []];
        }
        if ($all('integer')) {
            return ['tipo' => 'integer', 'opciones' => []];
        }
        if (count(array_diff($classes, ['integer', 'decimal'])) === 0) {
            return ['tipo' => 'decimal', 'opciones' => []];
        }
        if ($all('date')) {
            return ['tipo' => 'date', 'opciones' => []];
        }
        if ($all('time')) {
            return ['tipo' => 'time', 'opciones' => []];
        }

        $distinct = [];
        foreach ($samples as $sample) {
            $key = gettype($sample['valor']).':'.(string) $sample['valor'];
            $distinct[$key] = $sample['valor'];
        }

        if (count($distinct) < 20 && count($distinct) < count($samples)) {
            return ['tipo' => 'select', 'opciones' => array_values($distinct)];
        }

        $hasLongText = count(array_filter(
            $samples,
            static fn (array $sample): bool => mb_strlen((string) $sample['valor']) > 120
        )) > 0;

        return [
            'tipo' => $hasLongText ? 'textarea' : 'text',
            'opciones' => [],
        ];
    }

    private function classifyValue(mixed $value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }

        $normalized = Str::lower(trim(Str::ascii((string) $value)));
        if (in_array($normalized, ['si', 'no', 's', 'n', 'v', 'f', 'verdadero', 'falso', 'true', 'false'], true)) {
            return 'boolean';
        }
        if ($this->isIntegerValue($value)) {
            return 'integer';
        }
        if ($this->isDecimalValue($value)) {
            return 'decimal';
        }
        if ($this->isDateString((string) $value)) {
            return 'date';
        }
        if (preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', trim((string) $value))) {
            return 'time';
        }

        return 'text';
    }

    private function isDateString(string $value): bool
    {
        $value = trim($value);

        foreach (['!Y-m-d', '!d/m/Y', '!d-m-Y', '!Y/m/d'] as $format) {
            $date = \DateTimeImmutable::createFromFormat($format, $value);
            $errors = \DateTimeImmutable::getLastErrors();

            if ($date !== false && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return true;
            }
        }

        return false;
    }

    private function isIntegerValue(mixed $value): bool
    {
        return is_int($value)
            || (is_float($value) && floor($value) === $value)
            || (is_string($value) && preg_match('/^[+-]?\d+$/', trim($value)) === 1);
    }

    private function isDecimalValue(mixed $value): bool
    {
        if (is_float($value) || is_int($value)) {
            return true;
        }

        return is_string($value)
            && preg_match('/^[+-]?(?:\d+[.,]\d+|\d*\.\d+)$/', trim($value)) === 1;
    }

    private function isNumericValue(mixed $value): bool
    {
        return $this->isIntegerValue($value) || $this->isDecimalValue($value);
    }

    private function isEmpty(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }

    private function serializableValue(mixed $value): string|int|float|bool
    {
        if (is_string($value) || is_int($value) || is_float($value) || is_bool($value)) {
            return $value;
        }

        return $this->scalarString($value);
    }

    private function scalarString(mixed $value): string
    {
        if ($value instanceof RichText) {
            return $value->getPlainText();
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }

    private function normalizeHeader(string $value): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', Str::ascii($value)));
    }

    private function normalizeCode(string $value): string
    {
        $code = Str::of(Str::ascii($value))
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->value();

        return $code !== '' ? $code : 'columna';
    }

    /**
     * @param  array<int, array<string, mixed>>  $sheets
     */
    private function detectWorkbookType(array $sheets): string
    {
        foreach ($sheets as $sheet) {
            $sheetCode = $this->normalizeCode($sheet['nombre']);
            $headerCodes = array_column($sheet['cabeceras'], 'codigo');

            if ($sheetCode === 'variables_salud_2'
                && count(array_intersect($headerCodes, [
                    'codigo_de_variable',
                    'descripcion_de_variable',
                    'tipo_de_registro',
                    'prestaciones',
                ])) === 4) {
                return 'variables_salud';
            }
        }

        foreach ($sheets as $sheet) {
            $sheetCode = $this->normalizeCode($sheet['nombre']);
            $headerCodes = array_column($sheet['cabeceras'], 'codigo');

            if ($sheetCode === 'dim_establecimientos'
                || count(array_intersect($headerCodes, ['id_establecimiento', 'establecimiento'])) === 2) {
                return 'establecimientos_dim';
            }
        }

        $spSheets = array_filter(
            $sheets,
            fn (array $sheet): bool => preg_match('/^sp_?\d+(?:_|$)/', $this->normalizeCode($sheet['nombre'])) === 1
                && $sheet['fila_cabecera'] !== null
        );

        return count($spSheets) >= 2 ? 'formularios_sp' : 'generico';
    }

    /**
     * @param  array<int, array<string, mixed>>  $sheets
     * @return array<string, mixed>
     */
    private function indexSheetField(array $sheets, string $field): array
    {
        $indexed = [];

        foreach ($sheets as $sheet) {
            $indexed[$sheet['nombre']] = $sheet[$field];
        }

        return $indexed;
    }
}
