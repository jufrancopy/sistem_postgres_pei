<?php

namespace App\Application\Bioestadistica\Imports;

use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class VariablesSaludImporter
{
    private const SHEET_NAMES = ['VARIABLES SALUD', 'VARIABLES SALUD (2)'];

    /**
     * @return array<string, mixed>
     */
    public function import(string $filePath): array
    {
        $spreadsheet = null;
        $summary = [
            'tipo' => BioestadisticaDedicatedImporter::VARIABLES_SALUD,
            'archivo' => basename($filePath),
            'procesados' => 0,
            'creados' => [
                'variables' => 0,
                'variable_detalles' => 0,
                'prestaciones' => 0,
            ],
            'advertencias' => [],
        ];

        try {
            $spreadsheet = $this->loadSpreadsheet($filePath);
            $sheet = $this->resolveSheet($spreadsheet);

            if (! $sheet) {
                throw new RuntimeException(
                    "El archivo no contiene una hoja 'VARIABLES SALUD'."
                );
            }

            $this->assertExpectedHeaders($sheet);
            $this->importRows($sheet, $filePath, $summary);

            if ($summary['procesados'] === 0) {
                throw new RuntimeException(
                    "La hoja '" . $sheet->getTitle() . "' no contiene prestaciones válidas."
                );
            }

            return $summary;
        } catch (Throwable $exception) {
            if ($exception instanceof RuntimeException
                && str_starts_with($exception->getMessage(), 'No se pudo importar')) {
                throw $exception;
            }

            throw new RuntimeException(
                "No se pudo importar variables de salud desde '{$filePath}': {$exception->getMessage()}",
                0,
                $exception
            );
        } finally {
            $this->disconnect($spreadsheet);
        }
    }

    /**
     * @param array<string, mixed> $summary
     */
    private function importRows(
        Worksheet $sheet,
        string $filePath,
        array &$summary
    ): void {
        $current = ['codigo' => null, 'dominio' => null, 'tipo' => null];
        $seen = [];

        for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $values = [];
            for ($column = 1; $column <= 4; $column++) {
                $values[$column] = $this->clean(
                    $sheet->getCell([$column, $row])->getFormattedValue()
                );
            }

            if ($this->isHeader($values)) {
                continue;
            }

            if ($values[1] && ! preg_match('/^(?:[1-9]|1[0-8]|x)$/i', $values[1])) {
                continue;
            }

            $current['codigo'] = $values[1] ?: $current['codigo'];
            $current['dominio'] = $values[2] ?: $current['dominio'];
            $current['tipo'] = $values[3] ?: $current['tipo'];
            $prestacion = $values[4] ?: ($values[3] ? $values[3] : null);

            if (! $current['codigo'] || ! $current['dominio'] || ! $current['tipo'] || ! $prestacion) {
                continue;
            }

            $naturalKey = $this->key(
                "{$current['codigo']}|{$current['tipo']}|{$prestacion}"
            );
            if (isset($seen[$naturalKey])) {
                $summary['advertencias'][] =
                    "Fila {$row}: prestación duplicada omitida ({$current['tipo']} / {$prestacion}).";
                continue;
            }
            $seen[$naturalKey] = true;

            try {
                DB::transaction(function () use (
                    $current,
                    $prestacion,
                    &$summary
                ): void {
                    $dictionary = (new HealthVariableDictionary())->remember(
                        $current['codigo'],
                        $current['dominio'],
                        $current['tipo'],
                        $prestacion
                    );

                    $summary['procesados']++;
                    $summary['creados']['variables'] += (int) $dictionary['created']['variable'];
                    $summary['creados']['variable_detalles'] += (int) $dictionary['created']['detalle'];
                    $summary['creados']['prestaciones'] += (int) $dictionary['created']['prestacion'];
                });
            } catch (Throwable $exception) {
                throw new RuntimeException(
                    "Error en la fila {$row} de '" . $sheet->getTitle() . "': {$exception->getMessage()}",
                    0,
                    $exception
                );
            }
        }
    }

    private function assertExpectedHeaders(Worksheet $sheet): void
    {
        $found = false;
        $lastRow = min(30, $sheet->getHighestDataRow());

        for ($row = 1; $row <= $lastRow; $row++) {
            $cells = [];
            for ($column = 1; $column <= 4; $column++) {
                $cells[] = $this->key($sheet->getCell([$column, $row])->getFormattedValue());
            }

            $joined = implode('|', $cells);
            if (str_contains($joined, 'CODIGO_DE_VARIABLE')
                && str_contains($joined, 'DESCRIPCION_DE_VARIABLE')
                && str_contains($joined, 'TIPO_DE_REGISTRO')
                && str_contains($joined, 'PRESTACIONES')) {
                $found = true;
                break;
            }
        }

        if (! $found) {
            throw new RuntimeException(
                "La hoja '" . $sheet->getTitle() . "' no tiene los encabezados esperados."
            );
        }
    }

    /**
     * @param array<int, string|null> $values
     */
    private function isHeader(array $values): bool
    {
        $row = $this->key(implode(' ', array_filter($values)));

        return str_contains($row, 'CODIGO_DE_VARIABLE')
            || str_contains($row, 'TIPO_DE_REGISTRO')
            || str_contains($row, 'PRESTACIONES');
    }

    private function resolveSheet(Spreadsheet $spreadsheet): ?Worksheet
    {
        foreach (self::SHEET_NAMES as $name) {
            $sheet = $spreadsheet->getSheetByName($name);
            if ($sheet) {
                return $sheet;
            }
        }

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $title = $this->key($sheet->getTitle());
            if (str_starts_with($title, 'VARIABLES_SALUD')) {
                return $sheet;
            }
        }

        return $spreadsheet->getSheetCount() === 1 ? $spreadsheet->getActiveSheet() : null;
    }

    private function loadSpreadsheet(string $filePath): Spreadsheet
    {
        try {
            return IOFactory::createReaderForFile($filePath)
                ->setReadDataOnly(true)
                ->load($filePath);
        } catch (Throwable $exception) {
            throw new RuntimeException(
                "El archivo no pudo abrirse como Excel: {$exception->getMessage()}",
                0,
                $exception
            );
        }
    }

    private function clean(mixed $value): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function key(mixed $value): string
    {
        return trim(
            preg_replace('/[^A-Z0-9]+/', '_', Str::upper(Str::ascii((string) $value))),
            '_'
        );
    }

    private function disconnect(?Spreadsheet $spreadsheet): void
    {
        if ($spreadsheet) {
            $spreadsheet->disconnectWorksheets();
        }
    }
}
