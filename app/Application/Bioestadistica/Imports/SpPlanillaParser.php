<?php

namespace App\Application\Bioestadistica\Imports;

use App\Application\Bioestadistica\Sp11Matrix;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class SpPlanillaParser
{
    /** @var array<string, int> */
    private const MONTHS = [
        'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6,
        'julio' => 7, 'agosto' => 8, 'septiembre' => 9, 'setiembre' => 9, 'octubre' => 10,
        'noviembre' => 11, 'diciembre' => 12,
    ];

    /** SP con una sola métrica «total» en la planilla Excel. */
    private const SINGLE_TOTAL_SPS = ['SP2', 'SP12', 'SP13', 'SP14'];

    /** SP9: consultas / observación / procedimiento + total por fila. */
    private const URGENCIAS_SPS = ['SP9'];

    /** SP tabular con columnas pacientes / estudios / prestaciones / determinaciones (tabla única). */
    private const MULTI_METRIC_SPS = ['SP3', 'SP4', 'SP7'];

    /** SP con bloques apilados COD | etiqueta | TOTAL (p. ej. pacientes + determinaciones). */
    private const STACKED_TABLE_SPS = ['SP5', 'SP6'];

    private const CROSSTAB_SPS = ['SP8'];

    private const MATRIX_SPS = ['SP11'];

    private const NOMINATIVE_SPS = ['SP10'];

    /** @var array<int, string> */
    private const SP8_AGE_GROUPS = ['menores_1', '1_3', '4_14', '15_59', '60_mas'];

    /** @return array<int, string> */
    public static function parsedSpCodes(): array
    {
        return array_merge(
            ['SP1'],
            self::SINGLE_TOTAL_SPS,
            self::URGENCIAS_SPS,
            self::MULTI_METRIC_SPS,
            self::STACKED_TABLE_SPS,
            self::CROSSTAB_SPS,
            self::MATRIX_SPS,
            self::NOMINATIVE_SPS
        );
    }

    public function __construct(
        private SpPlanillaSheetDetector $detector,
        private HospEpisodioImporter $hospImporter
    ) {
    }

    /**
     * Escanea el libro completo y parsea cada hoja con SP reconocible.
     *
     * @return array<string, mixed>
     */
    public function scanWorkbook(string $absolutePath): array
    {
        $spreadsheet = $this->load($absolutePath);
        try {
            $contexto = [
                'departamento' => null,
                'establecimiento_nombre' => null,
                'codigo_planilla' => null,
                'periodo_mes' => $this->inferMonthFromFilename($absolutePath),
                'periodo_anio' => $this->inferYearFromFilename($absolutePath),
            ];
            $hojas = [];

            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $spCode = $this->detector->detect($sheet);
                $header = $this->parseHeader($sheet);
                $contexto = $this->mergeContexto($contexto, $header);

                $entry = [
                    'titulo' => trim(str_replace("\u{00A0}", ' ', $sheet->getTitle())),
                    'sp_codigo' => $spCode,
                    'departamento' => $header['departamento'],
                    'establecimiento_nombre' => $header['establecimiento'],
                    'codigo_planilla' => $header['codigo'],
                    'periodo_mes' => $header['mes'],
                    'periodo_anio' => $header['anio'],
                    'parser_disponible' => $spCode && in_array($spCode, self::parsedSpCodes(), true),
                    'filas_detectadas' => 0,
                    'parseado' => false,
                    'error' => null,
                    'detectado' => null,
                    'advertencia_hoja' => $spCode === 'SP2' && ! str_contains(Str::upper($sheet->getTitle()), 'SP2')
                        ? 'Hoja nombrada «'.$sheet->getTitle().'», normalizada a SP2.'
                        : null,
                ];

                if (! $spCode) {
                    $entry['error'] = 'No se detectó código SP en la hoja.';
                    $hojas[] = $entry;

                    continue;
                }

                if (! in_array($spCode, self::parsedSpCodes(), true)) {
                    $entry['error'] = 'Parser de datos aún no implementado para '.$spCode.'.';
                    $hojas[] = $entry;

                    continue;
                }

                try {
                    $detectado = $this->parseSheet($sheet, $spCode);
                    $entry['detectado'] = $detectado;
                    $entry['filas_detectadas'] = (int) ($detectado['filas_detectadas'] ?? 0);
                    $entry['parseado'] = true;
                } catch (Throwable $exception) {
                    $entry['error'] = $exception->getMessage();
                }

                $hojas[] = $entry;
            }

            if ($contexto['codigo_planilla'] !== null && ! $this->isValidCodigo($contexto['codigo_planilla'])) {
                $contexto['codigo_planilla'] = $this->resolveCodigoFromHojas($hojas) ?? $contexto['codigo_planilla'];
            }

            return [
                'contexto' => $contexto,
                'hojas' => $hojas,
            ];
        } finally {
            $spreadsheet->disconnectWorksheets();
        }
    }

    /**
     * Parsea una hoja concreta (compatibilidad: primera hoja importable con datos, o SP1).
     *
     * @return array<string, mixed>
     */
    public function parse(string $absolutePath, ?string $sheetTitle = null): array
    {
        $workbook = $this->scanWorkbook($absolutePath);

        if ($sheetTitle !== null) {
            foreach ($workbook['hojas'] as $hoja) {
                if (($hoja['titulo'] ?? '') === $sheetTitle && is_array($hoja['detectado'] ?? null)) {
                    return $hoja['detectado'];
                }
            }
            throw new RuntimeException('No se pudo parsear la hoja seleccionada.');
        }

        foreach ($workbook['hojas'] as $hoja) {
            if (($hoja['importable'] ?? false) && ($hoja['filas_detectadas'] ?? 0) > 0 && is_array($hoja['detectado'] ?? null)) {
                return $hoja['detectado'];
            }
        }

        foreach ($workbook['hojas'] as $hoja) {
            if (($hoja['sp_codigo'] ?? '') === 'SP1' && is_array($hoja['detectado'] ?? null)) {
                return $hoja['detectado'];
            }
        }

        throw new RuntimeException('No se encontró una hoja importable con datos en el archivo.');
    }

    /**
     * @param  array<string, mixed>  $overrides  fila_encabezado, columnas[label|total|total_consultas|cod|pacientes|estudios|…]
     * @return array<string, mixed>
     */
    public function parseSheet(Worksheet $sheet, string $spCode, array $overrides = []): array
    {
        return match ($spCode) {
            'SP1' => $this->parseSp1Sheet($sheet, $overrides),
            default => match (true) {
                in_array($spCode, self::URGENCIAS_SPS, true) => $this->parseSp9Sheet($sheet, $overrides),
                in_array($spCode, self::SINGLE_TOTAL_SPS, true) => $this->parseSingleTotalSheet($sheet, $spCode, $overrides),
                in_array($spCode, self::MULTI_METRIC_SPS, true) => $this->parseMultiMetricSheet($sheet, $spCode, $overrides),
                in_array($spCode, self::STACKED_TABLE_SPS, true) => match ($spCode) {
                    'SP5' => $this->parseSp5Sheet($sheet, $overrides),
                    'SP6' => $this->parseSp6Sheet($sheet, $overrides),
                    default => throw new RuntimeException('Parser apilado no implementado para '.$spCode.'.'),
                },
                in_array($spCode, self::CROSSTAB_SPS, true) => $this->parseSp8Sheet($sheet, $overrides),
                in_array($spCode, self::MATRIX_SPS, true) => $this->parseSp11Sheet($sheet),
                in_array($spCode, self::NOMINATIVE_SPS, true) => $this->parseSp10Sheet($sheet),
                default => throw new RuntimeException('Parser no implementado para '.$spCode.'.'),
            },
        };
    }

    /**
     * Reparsea una hoja de un archivo ya guardado (asistente de mapeo).
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public function parseSheetFromFile(string $absolutePath, string $sheetTitle, string $spCode, array $overrides = []): array
    {
        $spreadsheet = $this->load($absolutePath);
        try {
            $sheet = $this->findWorksheet($spreadsheet, $sheetTitle);
            if (! $sheet) {
                throw new RuntimeException('No se encontró la hoja «'.$sheetTitle.'» en el archivo.');
            }

            return $this->parseSheet($sheet, $spCode, $overrides);
        } finally {
            $spreadsheet->disconnectWorksheets();
        }
    }

    /**
     * Grilla cruda para el asistente de mapeo (filas × columnas como texto).
     *
     * @return array{columns: array<int, string>, rows: array<int, array{row: int, cells: array<int, string>}>}
     */
    public function sheetGridFromFile(string $absolutePath, string $sheetTitle, int $maxRows = 18, int $maxCols = 12): array
    {
        $spreadsheet = $this->load($absolutePath);
        try {
            $sheet = $this->findWorksheet($spreadsheet, $sheetTitle);
            if (! $sheet) {
                throw new RuntimeException('No se encontró la hoja «'.$sheetTitle.'» en el archivo.');
            }

            $lastCol = min($maxCols, max(1, Coordinate::columnIndexFromString($sheet->getHighestDataColumn())));
            $lastRow = min($maxRows, max(1, $sheet->getHighestDataRow()));
            $columns = [];
            for ($col = 1; $col <= $lastCol; $col++) {
                $columns[$col] = Coordinate::stringFromColumnIndex($col);
            }
            $rows = [];
            for ($row = 1; $row <= $lastRow; $row++) {
                $cells = [];
                for ($col = 1; $col <= $lastCol; $col++) {
                    $cells[$col] = $this->cellText($sheet, $col, $row);
                }
                $rows[] = ['row' => $row, 'cells' => $cells];
            }

            return [
                'columns' => $columns,
                'rows' => $rows,
                'highest_row' => $sheet->getHighestDataRow(),
                'highest_column' => $lastCol,
            ];
        } finally {
            $spreadsheet->disconnectWorksheets();
        }
    }

    private function findWorksheet(Spreadsheet $spreadsheet, string $sheetTitle): ?Worksheet
    {
        $sheet = $spreadsheet->getSheetByName($sheetTitle);
        if ($sheet) {
            return $sheet;
        }

        $needle = $this->normalizeSheetName($sheetTitle);
        foreach ($spreadsheet->getAllSheets() as $candidate) {
            if ($this->normalizeSheetName($candidate->getTitle()) === $needle) {
                return $candidate;
            }
        }

        return null;
    }

    private function normalizeSheetName(string $title): string
    {
        $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title = str_replace("\u{00A0}", ' ', $title);
        $title = preg_replace('/\s+/u', ' ', trim($title)) ?? trim($title);

        return Str::upper(Str::ascii($title));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseSp1Sheet(Worksheet $sheet, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        $detected = $this->findSp1DataHeader($sheet);
        $columns = $overrides['columnas'] ?? [];

        $headerRow = $detected['row'] ?? null;
        if (! empty($overrides['fila_encabezado'])) {
            $headerRow = (int) $overrides['fila_encabezado'];
        }

        $labelColumn = ! empty($columns['label'])
            ? $this->columnIndex($columns['label'])
            : ($detected['label'] ?? null);
        $codColumn = ! empty($columns['cod'])
            ? $this->columnIndex($columns['cod'])
            : ($detected['cod'] ?? null);
        $totalColumn = ! empty($columns['total_consultas'])
            ? $this->columnIndex($columns['total_consultas'])
            : (! empty($columns['total']) ? $this->columnIndex($columns['total']) : ($detected['total'] ?? null));
        $ipsColumn = ! empty($columns['ips'])
            ? $this->columnIndex($columns['ips'])
            : ($detected['ips'] ?? null);
        $convenioColumn = ! empty($columns['convenio'])
            ? $this->columnIndex($columns['convenio'])
            : ($detected['convenio'] ?? null);

        if ($headerRow && ! $labelColumn) {
            $headerLabels = $this->rowHeaders($sheet, $headerRow);
            $labelColumn = $this->resolveLabelColumn($headerLabels, $totalColumn ?? $ipsColumn ?? 3);
        }
        if ($headerRow && ! $codColumn) {
            $headerLabels = $this->rowHeaders($sheet, $headerRow);
            $codColumn = $this->resolveCodColumn($headerLabels);
        }
        if ($headerRow && ! $totalColumn && ! $ipsColumn && ! $convenioColumn) {
            $totalColumn = $this->guessTotalColumnOnRow($sheet, $headerRow);
        }

        $layout = ($ipsColumn || $convenioColumn) ? 'ips_convenio' : 'total';
        if ($layout === 'total' && ! $totalColumn) {
            throw new RuntimeException('No se detectó la fila COD / ESPECIALIDADES / TOTAL CONSULTAS. Use el asistente de mapeo.');
        }
        if (! $headerRow || ! $labelColumn) {
            throw new RuntimeException('No se detectó la fila COD / ESPECIALIDADES / TOTAL CONSULTAS. Use el asistente de mapeo.');
        }

        $warnings = [];
        if (! $this->detectSp1($sheet)) {
            $warnings[] = 'La hoja no declara explícitamente «TABLA SP 1»; se procesó por mapeo o contenido (especialidades / IPS-convenio).';
        }
        if ($layout === 'ips_convenio') {
            $warnings[] = 'Layout SP1 detectado: columnas IPS/CONVENIO → bloques consulta y convenio.';
        } else {
            $warnings[] = 'Layout SP1 detectado: columna TOTAL(ES) → bloque consultas por especialidad.';
        }
        if ($this->sp1HeaderHasTipoSeguroExtras($sheet, $headerRow)) {
            $warnings[] = 'Se detectaron columnas de tipo de seguro (p. ej. particular); en esta versión solo se importan TOTALES o IPS/CONVENIO.';
        }
        if ($overrides !== []) {
            $warnings[] = 'Parseo con mapeo manual (fila encabezado '.$headerRow.').';
        }

        $rows = [];
        $labeledNumeric = 0;
        $lastRow = $sheet->getHighestDataRow();
        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $cod = $codColumn ? $this->cellText($sheet, $codColumn, $row) : '';
            $label = $this->cellText($sheet, $labelColumn, $row);
            if ($label === '' || $this->isTotal($label) || $this->isContext($label)) {
                continue;
            }

            if ($layout === 'ips_convenio') {
                $ips = $ipsColumn ? $this->readNumeric($sheet, $ipsColumn, $row) : null;
                $convenio = $convenioColumn ? $this->readNumeric($sheet, $convenioColumn, $row) : null;
                $totales = $totalColumn ? $this->readNumeric($sheet, $totalColumn, $row) : null;
                if (($ips === null || $ips <= 0) && ($convenio === null || $convenio <= 0)) {
                    if (($ips !== null && $ips === 0) || ($convenio !== null && $convenio === 0) || ($totales !== null && $totales === 0)) {
                        $labeledNumeric++;
                    }
                    continue;
                }
                $labeledNumeric++;
                $metricas = [];
                if ($ips !== null && $ips > 0) {
                    $metricas['ips'] = (int) $ips;
                }
                if ($convenio !== null && $convenio > 0) {
                    $metricas['convenio'] = (int) $convenio;
                }
                if ($totales !== null && $totales > 0) {
                    $metricas['totales_planilla'] = (int) $totales;
                    $sum = ($metricas['ips'] ?? 0) + ($metricas['convenio'] ?? 0);
                    if ($sum > 0 && $sum !== (int) $totales) {
                        $warnings[] = "Fila {$row} ({$label}): IPS+CONVENIO={$sum} ≠ TOTALES={$totales}; se importan IPS/CONVENIO.";
                    }
                }
                $rows[] = $this->normalizeRow($row, $cod, $label, $metricas);
                continue;
            }

            $total = $this->readNumeric($sheet, $totalColumn, $row);
            if ($total === null) {
                continue;
            }
            $labeledNumeric++;
            if ($total <= 0) {
                continue;
            }
            $rows[] = $this->normalizeRow($row, $cod, $label, ['total_consultas' => (int) $total]);
        }

        if ($rows === []) {
            if ($labeledNumeric > 0) {
                $warnings[] = 'La tabla SP1 tiene filas con etiqueta, pero todos los totales son 0 o vacíos; no hay valores para importar.';

                return $this->buildResult('SP1', $sheet, $header, $headerRow, [], $warnings) + [
                    'sp1_layout' => $layout,
                ];
            }
            throw new RuntimeException('No se encontraron filas con consultas numéricas en la planilla SP1.');
        }

        return $this->buildResult('SP1', $sheet, $header, $headerRow, $rows, $warnings) + [
            'sp1_layout' => $layout,
        ];
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseSingleTotalSheet(Worksheet $sheet, string $spCode, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        [$headerRow, $headers, $totalColumn] = $this->findGenericTableHeader($sheet);
        $columns = $overrides['columnas'] ?? [];

        if (! empty($overrides['fila_encabezado'])) {
            $headerRow = (int) $overrides['fila_encabezado'];
            $headers = $this->rowHeaders($sheet, $headerRow);
        }
        if (! empty($columns['total'])) {
            $totalColumn = $this->columnIndex($columns['total']);
        } elseif ($headerRow && ! $totalColumn) {
            $totalColumn = $this->guessTotalColumnOnRow($sheet, $headerRow);
        }

        if (! $headerRow || ! $totalColumn) {
            throw new RuntimeException('No se detectó fila de encabezado con columna TOTAL. Use el asistente de mapeo.');
        }

        $labelColumn = ! empty($columns['label'])
            ? $this->columnIndex($columns['label'])
            : $this->resolveLabelColumn($headers ?: $this->rowHeaders($sheet, $headerRow), $totalColumn);

        $rows = [];
        $labeledNumeric = 0;
        $lastRow = $sheet->getHighestDataRow();
        $warnings = $overrides !== [] ? ['Parseo con mapeo manual (fila encabezado '.$headerRow.').'] : [];
        $codColumn = ! empty($columns['cod'])
            ? $this->columnIndex($columns['cod'])
            : ($this->resolveCodColumn($headers ?: $this->rowHeaders($sheet, $headerRow)) ?? ($labelColumn > 1 ? $labelColumn - 1 : null));

        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $label = $this->cellText($sheet, $labelColumn, $row);
            if ($label === '' || $this->isContext($label) || $this->isTotal($label)) {
                continue;
            }
            $total = $this->readNumeric($sheet, $totalColumn, $row);
            if ($total === null) {
                continue;
            }
            $labeledNumeric++;
            if ($total <= 0) {
                continue;
            }

            $cod = $codColumn ? $this->cellText($sheet, $codColumn, $row) : '';
            if ($this->looksLikeCodigo($label) && ! $this->looksLikeCodigo($cod)) {
                [$cod, $label] = [$label, $cod];
            }

            $rows[] = $this->normalizeRow($row, $cod, $label, ['total' => (int) $total]);
        }

        if ($rows === []) {
            if ($labeledNumeric > 0) {
                $warnings[] = 'La tabla '.$spCode.' tiene filas con etiqueta, pero todos los totales son 0 o vacíos; no hay valores para importar.';

                return $this->buildResult($spCode, $sheet, $header, $headerRow, [], $warnings);
            }
            throw new RuntimeException('No se encontraron filas con totales numéricos en la planilla '.$spCode.'.');
        }

        return $this->buildResult($spCode, $sheet, $header, $headerRow, $rows, $warnings);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseSp9Sheet(Worksheet $sheet, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        [$headerRow, $labelColumn, $metricColumns] = $this->findSp9TableHeader($sheet);
        $columns = $overrides['columnas'] ?? [];

        if (! empty($overrides['fila_encabezado'])) {
            $headerRow = (int) $overrides['fila_encabezado'];
        }
        if ($columns !== []) {
            if (! empty($columns['label'])) {
                $labelColumn = $this->columnIndex($columns['label']);
            }
            $metricColumns = [];
            foreach (['consultas', 'observacion', 'procedimiento', 'total'] as $metric) {
                if (! empty($columns[$metric])) {
                    $metricColumns[$metric] = $this->columnIndex($columns[$metric]);
                }
            }
        } elseif ($headerRow && $metricColumns === []) {
            $metricColumns = $this->guessSp9MetricsOnRow($sheet, $headerRow);
            $labelColumn ??= 3;
        }

        if (! $headerRow || $metricColumns === []) {
            throw new RuntimeException('No se detectó el encabezado SP9 (consultas / observación / procedimiento / total). Use el asistente de mapeo.');
        }
        $labelColumn ??= 3;

        $rows = [];
        $section = 'adultos';
        $lastRow = $sheet->getHighestDataRow();
        $warnings = $overrides !== [] ? ['Parseo con mapeo manual (fila encabezado '.$headerRow.').'] : [];
        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $label = $this->cellText($sheet, $labelColumn, $row);
            if ($label === '' || $this->isContext($label)) {
                continue;
            }
            if ($this->isTotal($label)) {
                continue;
            }

            $resolvedSection = $this->resolveSp9Section($label);
            if ($resolvedSection !== null) {
                $section = $resolvedSection;
            }

            $metricas = [];
            foreach ($metricColumns as $code => $col) {
                $num = $this->readNumeric($sheet, $col, $row);
                if ($num !== null && $num > 0) {
                    $metricas[$code] = (int) $num;
                }
            }
            if ($metricas === []) {
                continue;
            }

            $breakdownSum = ($metricas['consultas'] ?? 0)
                + ($metricas['observacion'] ?? 0)
                + ($metricas['procedimiento'] ?? 0);
            if ($breakdownSum > 0) {
                $metricas['total'] = $breakdownSum;
            } elseif (! isset($metricas['total'])) {
                continue;
            }

            $cod = $labelColumn > 1 ? $this->cellText($sheet, $labelColumn - 1, $row) : '';
            if ($this->looksLikeCodigo($label) && ! $this->looksLikeCodigo($cod)) {
                [$cod, $label] = [$label, $cod];
            }

            $normalized = $this->normalizeRow($row, $cod, $label, $metricas);
            $normalized['field_code'] = $this->sp9FieldCode($section);
            $rows[] = $normalized;
        }

        if ($rows === []) {
            throw new RuntimeException('No se encontraron filas con métricas de urgencias en la planilla SP9.');
        }

        $result = $this->buildResult('SP9', $sheet, $header, $headerRow, $rows, $warnings);
        $result['layout'] = 'tabular';

        return $result;
    }

    /**
     * @return array{0: int|null, 1: int|null, 2: array<string, int>}
     */
    private function findSp9TableHeader(Worksheet $sheet): array
    {
        $lastRow = min(25, $sheet->getHighestDataRow());
        $bestRow = null;
        $bestColumns = [];
        $bestLabelCol = null;
        $bestScore = -1;

        for ($row = 1; $row <= $lastRow; $row++) {
            $metricColumns = [];
            for ($scanRow = $row; $scanRow <= min($row + 2, $lastRow); $scanRow++) {
                for ($col = 1; $col <= 10; $col++) {
                    $key = $this->normalizeKey($this->cellText($sheet, $col, $scanRow));
                    if ($key === '' || str_contains($key, 'paciente')) {
                        continue;
                    }
                    if ($this->headerMatchesRole($key, 'consultas') || str_contains($key, 'consulta')) {
                        $metricColumns['consultas'] = $col;
                    } elseif ($this->headerMatchesRole($key, 'observacion') || str_contains($key, 'observacion')) {
                        $metricColumns['observacion'] = $col;
                    } elseif ($this->headerMatchesRole($key, 'procedimiento') || str_contains($key, 'procedimiento')) {
                        $metricColumns['procedimiento'] = $col;
                    } elseif ($this->headerMatchesRole($key, 'total')
                        || $key === 'total'
                        || (str_starts_with($key, 'total ') && ! str_contains($key, 'general'))) {
                        $metricColumns['total'] = $col;
                    }
                }
            }

            if (count($metricColumns) < 4) {
                continue;
            }

            $labelColumn = null;
            for ($scanRow = $row; $scanRow <= min($row + 2, $lastRow); $scanRow++) {
                for ($col = 1; $col <= 6; $col++) {
                    $key = $this->normalizeKey($this->cellText($sheet, $col, $scanRow));
                    if (str_contains($key, 'urgencia')) {
                        $labelColumn = $col;
                        break 2;
                    }
                }
            }
            $labelColumn ??= min(array_values($metricColumns)) - 1;
            if ($labelColumn < 1) {
                $labelColumn = 3;
            }

            $score = count($metricColumns) * 10;
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = min($row + 2, $lastRow);
                for ($scanRow = $row; $scanRow <= min($row + 2, $lastRow); $scanRow++) {
                    $hasMetric = false;
                    foreach ($metricColumns as $metricCol) {
                        if ($this->cellText($sheet, $metricCol, $scanRow) !== '') {
                            $hasMetric = true;
                            break;
                        }
                    }
                    if ($hasMetric) {
                        $bestRow = max($bestRow, $scanRow);
                    }
                }
                $bestColumns = $metricColumns;
                $bestLabelCol = $labelColumn;
            }
        }

        return [$bestRow, $bestLabelCol, $bestColumns];
    }

    private function resolveSp9Section(string $label): ?string
    {
        $key = $this->normalizeKey($label);
        if (str_contains($key, 'urgencia') && str_contains($key, 'convenio')) {
            return 'convenio';
        }
        if (preg_match('/^atencion de urgencias pediatricas\b/u', $key) === 1) {
            return 'pediatricas';
        }
        if (preg_match('/^atencion de urgencias adultos\b/u', $key) === 1) {
            return 'adultos';
        }

        return null;
    }

    private function sp9FieldCode(string $section): string
    {
        return match ($section) {
            'pediatricas' => 'var_4_atencion_de_urgencias_pediatricas',
            'convenio' => 'var_4_atencion_urgencias_por_convenio',
            default => 'var_4_atencion_de_urgencias_adultos',
        };
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseMultiMetricSheet(Worksheet $sheet, string $spCode, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        [$headerRow, $headers, $metricColumns, $labelColumn] = $this->findMultiMetricTableHeader($sheet, $spCode);
        $columns = $overrides['columnas'] ?? [];

        if (! empty($overrides['fila_encabezado'])) {
            $headerRow = (int) $overrides['fila_encabezado'];
            $headers = $this->rowHeaders($sheet, $headerRow);
            if ($columns === []) {
                $metricColumns = $this->mapMetricColumns($headers, $spCode);
                $labelColumn = $this->resolveMultiMetricLabelColumn($headers, $metricColumns);
            }
        }

        if ($columns !== []) {
            if (! empty($columns['label'])) {
                $labelColumn = $this->columnIndex($columns['label']);
            }
            $metricColumns = [];
            foreach (['pacientes', 'estudios', 'prestaciones', 'determinaciones', 'total'] as $metric) {
                if (! empty($columns[$metric])) {
                    $metricColumns[$metric] = $this->columnIndex($columns[$metric]);
                }
            }
            if ($metricColumns === [] && ! empty($columns['total'])) {
                $metricColumns['total'] = $this->columnIndex($columns['total']);
            }
        }

        if (! $headerRow || $metricColumns === [] || ! $labelColumn) {
            throw new RuntimeException('No se detectó fila de encabezado con columnas métricas (pacientes, estudios, prestaciones…). Use el asistente de mapeo.');
        }

        $rows = [];
        $lastRow = $sheet->getHighestDataRow();
        $warnings = $overrides !== [] ? ['Parseo con mapeo manual (fila encabezado '.$headerRow.').'] : [];
        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $label = $this->cellText($sheet, $labelColumn, $row);
            $cod = $labelColumn > 1 ? $this->cellText($sheet, $labelColumn - 1, $row) : '';
            if ($label === '' || $this->isContext($label) || $this->isTotal($label)) {
                continue;
            }
            if ($this->looksLikeCodigo($label) && ! $this->looksLikeCodigo($cod) && $cod !== '') {
                [$cod, $label] = [$label, $cod];
            } elseif ($this->looksLikeCodigo($label) && $cod === '') {
                $cod = $label;
                $label = '';
            }

            $metricas = [];
            foreach ($metricColumns as $code => $col) {
                $num = $this->readNumeric($sheet, $col, $row);
                if ($num !== null && $num > 0) {
                    $metricas[$code] = (int) $num;
                }
            }
            if ($metricas === []) {
                continue;
            }

            $displayLabel = $label !== '' ? $label : ($cod !== '' ? 'COD '.$cod : 'Fila '.$row);
            $rows[] = $this->normalizeRow($row, $cod, $displayLabel, $metricas);
        }

        if ($rows === []) {
            throw new RuntimeException('No se encontraron filas con valores numéricos en la planilla '.$spCode.'.');
        }

        return $this->buildResult($spCode, $sheet, $header, $headerRow, $rows, $warnings);
    }

    /**
     * SP5: bloque resumen de pacientes + tabla de determinaciones con columna TOTAL.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseSp5Sheet(Worksheet $sheet, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        $rows = [];
        $warnings = [];
        $block = null;
        $pacientesBlock = null;
        $determinacionesBlock = null;

        if ($overrides !== []) {
            $block = $this->stackedBlockFromOverrides($sheet, 'total', $overrides);
            if ($block) {
                $rows = array_merge($rows, $this->parseStackedMetricRows($sheet, $block));
                $warnings[] = 'Parseo con mapeo manual (fila encabezado '.$block['header_row'].').';
            }
            $pacientesBlock = $this->findStackedMetricBlock($sheet, 'pacientes', 'total');
            if ($pacientesBlock && (! $block || $pacientesBlock['header_row'] !== $block['header_row'])) {
                $rows = array_merge($rows, $this->parseStackedMetricRows($sheet, $pacientesBlock));
            }
        } else {
            $pacientesBlock = $this->findStackedMetricBlock($sheet, 'pacientes', 'total');
            if ($pacientesBlock) {
                $rows = array_merge($rows, $this->parseStackedMetricRows($sheet, $pacientesBlock));
            }

            $determinacionesBlock = $this->findStackedMetricBlock($sheet, 'determinaciones', 'total');
            if ($determinacionesBlock) {
                $rows = array_merge($rows, $this->parseStackedMetricRows($sheet, $determinacionesBlock));
            }
        }

        if ($rows === []) {
            throw new RuntimeException('No se encontraron filas con valores numéricos en la planilla SP5. Use el asistente de mapeo.');
        }

        $headerRow = $block['header_row']
            ?? $determinacionesBlock['header_row']
            ?? $pacientesBlock['header_row']
            ?? 1;

        return $this->buildResult('SP5', $sheet, $header, $headerRow, $rows, $warnings);
    }

    /**
     * SP6: resúmenes de pacientes + tabla de prestaciones odontológicas con TOTAL.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseSp6Sheet(Worksheet $sheet, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        $warnings = [];
        $block = null;
        if ($overrides !== []) {
            $block = $this->stackedBlockFromOverrides($sheet, 'total', $overrides);
            if (! $block) {
                $block = $this->findStackedMetricBlock($sheet, 'prestaciones', 'total');
            }
        } else {
            $block = $this->findStackedMetricBlock($sheet, 'prestaciones', 'total');
        }

        if (! $block) {
            throw new RuntimeException(
                'No se detectó la tabla de prestaciones odontológicas (COD / TOTAL). '
                .'En el asistente elija SP6, la fila de encabezado y las columnas «Prestación / etiqueta» y «Total».'
            );
        }
        if ($overrides !== []) {
            $warnings[] = 'Parseo con mapeo manual (fila encabezado '.$block['header_row'].').';
        }

        $rows = $this->parseStackedMetricRows($sheet, $block);
        if ($rows === []) {
            $labeled = $this->countStackedLabeledRows($sheet, $block);
            if ($labeled > 0) {
                $warnings[] = "Se detectaron {$labeled} prestaciones en la tabla, pero todos los totales son 0. No hay cantidades que importar para este período.";

                return $this->buildResult('SP6', $sheet, $header, $block['header_row'], [], $warnings);
            }
            throw new RuntimeException('No se encontraron filas con valores numéricos en la planilla SP6.');
        }

        return $this->buildResult('SP6', $sheet, $header, $block['header_row'], $rows, $warnings);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array{header_row: int, cod_col: ?int, label_col: int, total_col: int, metric: string}|null
     */
    private function stackedBlockFromOverrides(Worksheet $sheet, string $metricCode, array $overrides): ?array
    {
        $columns = $overrides['columnas'] ?? [];
        $headerRow = (int) ($overrides['fila_encabezado'] ?? 0);
        if ($headerRow < 1) {
            return null;
        }

        $headers = $this->rowHeaders($sheet, $headerRow);
        $labelCol = ! empty($columns['label'])
            ? $this->columnIndex($columns['label'])
            : $this->resolveLabelColumn($headers, $this->guessTotalColumnOnRow($sheet, $headerRow) ?? 3);

        $totalLetter = $columns['total']
            ?? $columns['total_consultas']
            ?? $columns['prestaciones']
            ?? $columns['determinaciones']
            ?? null;
        $totalCol = $totalLetter
            ? $this->columnIndex($totalLetter)
            : ($this->guessTotalColumnOnRow($sheet, $headerRow) ?? null);

        // Si el usuario mapeó etiqueta + otra columna numérica (B/C) y no hay TOTAL detectable,
        // usar la primera columna mapeada distinta de label/cod como total.
        if (! $totalCol) {
            foreach ($columns as $role => $letter) {
                if (in_array($role, ['label', 'cod'], true) || $letter === null || $letter === '') {
                    continue;
                }
                $totalCol = $this->columnIndex($letter);
                break;
            }
        }

        $codCol = ! empty($columns['cod'])
            ? $this->columnIndex($columns['cod'])
            : null;

        if (! $totalCol) {
            return null;
            }

            return [
            'header_row' => $headerRow,
            'cod_col' => $codCol,
            'label_col' => $labelCol,
            'total_col' => $totalCol,
            'metric' => $metricCode,
        ];
    }

    /**
     * Localiza un bloque COD | etiqueta métrica | TOTAL en planillas apiladas.
     *
     * @return array{header_row: int, cod_col: ?int, label_col: int, total_col: int, metric: string}|null
     */
    private function findStackedMetricBlock(Worksheet $sheet, string $labelNeedle, string $metricCode): ?array
    {
        $lastRow = min(50, $sheet->getHighestDataRow());
        $lastColumn = min(16, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));

        for ($row = 1; $row <= $lastRow; $row++) {
            $headers = [];
            for ($col = 1; $col <= $lastColumn; $col++) {
                $headers[$col] = $this->cellText($sheet, $col, $row);
            }

            $labelCol = null;
            $totalCol = null;
            $codCol = null;
            foreach ($headers as $col => $text) {
                $key = $this->normalizeKey($text);
                if ($key === '' || $this->isContext($text)) {
                    continue;
                }
                if ($this->isCodHeader($key)) {
                    $codCol = $col;
                }
                if ($this->matchesStackedLabelNeedle($key, $labelNeedle)) {
                    $labelCol = $col;
                }
                if ($this->isTotalHeader($key) && $totalCol === null) {
                    $totalCol = $col;
                }
            }

            if ($labelCol && $totalCol && $labelCol !== $totalCol) {
                return [
                    'header_row' => $row,
                    'cod_col' => $codCol,
                    'label_col' => $labelCol,
                    'total_col' => $totalCol,
                    'metric' => $metricCode,
                ];
            }
        }

        return null;
    }

    /**
     * @param  array{header_row: int, cod_col: ?int, label_col: int, total_col: int, metric: string}  $block
     * @return array<int, array<string, mixed>>
     */
    private function parseStackedMetricRows(Worksheet $sheet, array $block): array
    {
        $rows = [];
        $lastRow = $sheet->getHighestDataRow();

        for ($row = $block['header_row'] + 1; $row <= $lastRow; $row++) {
            if ($this->isStackedTableHeaderRow($sheet, $row, $block)) {
                break;
            }

            $label = $this->cellText($sheet, $block['label_col'], $row);
            $cod = $block['cod_col'] ? $this->cellText($sheet, $block['cod_col'], $row) : '';
            if ($label === '' || $this->isTotal($label) || $this->isContext($label)) {
                continue;
            }

            $num = $this->readNumeric($sheet, $block['total_col'], $row);
            if ($num === null || $num <= 0) {
                continue;
            }

            $rows[] = $this->normalizeRow($row, $cod, $label, [$block['metric'] => (int) $num]);
        }

        return $rows;
    }

    /**
     * @param  array{header_row: int, cod_col: ?int, label_col: int, total_col: int, metric: string}  $block
     */
    private function isStackedTableHeaderRow(Worksheet $sheet, int $row, array $block): bool
    {
        if ($row <= $block['header_row'] + 1) {
            return false;
        }

        $lastColumn = min(16, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        $hasCod = false;
        $hasTotal = false;
        $hasMetricHeader = false;

        for ($col = 1; $col <= $lastColumn; $col++) {
            $key = $this->normalizeKey($this->cellText($sheet, $col, $row));
            if ($key === '') {
                continue;
            }
            if ($this->isCodHeader($key)) {
                $hasCod = true;
            }
            if ($this->isTotalHeader($key)) {
                $hasTotal = true;
            }
            if (str_contains($key, 'determinacion')
                || str_contains($key, 'prestacion')
                || (str_contains($key, 'paciente') && ! str_contains($key, 'atendido'))) {
                $hasMetricHeader = true;
            }
        }

        return $hasCod && $hasTotal && $hasMetricHeader;
    }

    private function isCodHeader(string $key): bool
    {
        return $key === 'id'
            || $key === 'codigo'
            || preg_match('/^cod(?:\b|_|\s|\()/', $key) === 1;
    }

    private function isTotalHeader(string $key): bool
    {
        if ($key !== 'total' && ! str_starts_with($key, 'total ')) {
            return false;
        }

        // Resúmenes como «TOTAL DE PRESTACIONES» son etiquetas de fila, no columna TOTAL.
        return ! str_contains($key, 'prestacion')
            && ! str_contains($key, 'determinacion')
            && ! str_contains($key, 'paciente');
    }

    private function matchesStackedLabelNeedle(string $key, string $needle): bool
    {
        return match ($needle) {
            'pacientes' => str_contains($key, 'paciente'),
            'determinaciones' => str_contains($key, 'determinacion'),
            'prestaciones' => str_contains($key, 'prestacion')
                || $key === 'items'
                || $key === 'item'
                || str_contains($key, 'descripcion')
                || $key === 'detalle'
                || $key === 'servicio'
                || $key === 'servicios',
            default => str_contains($key, $needle),
        };
    }

    /**
     * Cuenta filas con etiqueta (incluye totales en 0) para mensajes de calidad.
     *
     * @param  array{header_row: int, cod_col: ?int, label_col: int, total_col: int, metric: string}  $block
     */
    private function countStackedLabeledRows(Worksheet $sheet, array $block): int
    {
        $count = 0;
        $lastRow = $sheet->getHighestDataRow();
        for ($row = $block['header_row'] + 1; $row <= $lastRow; $row++) {
            if ($this->isStackedTableHeaderRow($sheet, $row, $block)) {
                break;
            }
            $label = $this->cellText($sheet, $block['label_col'], $row);
            if ($label === '' || $this->isTotal($label) || $this->isContext($label)) {
                continue;
            }
            $num = $this->readNumeric($sheet, $block['total_col'], $row);
            if ($num === null) {
                continue;
            }
            $count++;
        }

        return $count;
    }

    /**
     * @return array{0: int|null, 1: array<int, string>, 2: array<string, int>, 3: int|null}
     */
    private function findMultiMetricTableHeader(Worksheet $sheet, string $spCode): array
    {
        $lastRow = min(40, $sheet->getHighestDataRow());
        $lastColumn = min(16, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        $bestRow = null;
        $bestHeaders = [];
        $bestMetrics = [];
        $bestLabelCol = null;
        $bestScore = -1;

        for ($row = 1; $row <= $lastRow; $row++) {
            $headers = [];
            $nonEmpty = 0;
            $contextHits = 0;
            for ($col = 1; $col <= $lastColumn; $col++) {
                $value = $this->cellText($sheet, $col, $row);
                $headers[$col] = $value;
                if ($value === '') {
                    continue;
                }
                $nonEmpty++;
                if ($this->isContext($value)) {
                    $contextHits++;
                }
            }
            if ($nonEmpty < 2 || $contextHits > 0) {
                continue;
            }

            $metricColumns = $this->mapMetricColumns($headers, $spCode);
            $labelColumn = $this->resolveMultiMetricLabelColumn($headers, $metricColumns);
            if ($metricColumns === [] || ! $labelColumn) {
                continue;
            }

            $score = count($metricColumns) * 10 + $nonEmpty - $contextHits;
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $row;
                $bestHeaders = $headers;
                $bestMetrics = $metricColumns;
                $bestLabelCol = $labelColumn;
            }
        }

        return [$bestRow, $bestHeaders, $bestMetrics, $bestLabelCol];
    }

    /**
     * @param  array<int, string>  $headers
     * @return array<string, int>
     */
    private function mapMetricColumns(array $headers, string $spCode): array
    {
        $metrics = [];
        foreach ($headers as $col => $label) {
            $key = $this->normalizeKey($label);
            if ($key === '') {
                continue;
            }
            if ($this->headerMatchesRole($key, 'pacientes') || str_contains($key, 'paciente')) {
                $metrics['pacientes'] = $col;
            } elseif ($this->headerMatchesRole($key, 'estudios') || str_contains($key, 'estudio')) {
                $metrics['estudios'] = $col;
            } elseif ($this->headerMatchesRole($key, 'determinaciones') || str_contains($key, 'determinacion')) {
                $metrics['determinaciones'] = $col;
            } elseif ($this->headerMatchesRole($key, 'prestaciones') || str_contains($key, 'prestacion')) {
                $metrics['prestaciones'] = $col;
            } elseif (($this->headerMatchesRole($key, 'total') || $key === 'total') && ! isset($metrics['prestaciones'])) {
                if (in_array($spCode, ['SP5'], true) && isset($metrics['pacientes'])) {
                    $metrics['determinaciones'] = $col;
                } elseif (in_array($spCode, ['SP6', 'SP7'], true)) {
                    $metrics['prestaciones'] = $col;
                }
            }
        }

        return $metrics;
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<string, int>  $metricColumns
     */
    private function resolveMultiMetricLabelColumn(array $headers, array $metricColumns): ?int
    {
        $metricCols = array_flip($metricColumns);
        foreach ($headers as $col => $label) {
            if (isset($metricCols[$col])) {
                continue;
            }
            $key = $this->normalizeKey($label);
            if ($key === '' || str_contains($key, 'cod')) {
                continue;
            }
            if (preg_match('/(metodo|auxiliar|diagnost|prestacion|procedimiento|odontolog|laboratorio|urgencia|servicio)/', $key)) {
                return $col;
            }
        }

        foreach ($headers as $col => $label) {
            if (isset($metricCols[$col])) {
                continue;
            }
            $key = $this->normalizeKey($label);
            if ($key !== '' && ! str_contains($key, 'cod')) {
                return $col;
            }
        }

        $firstMetric = min(array_values($metricColumns));

        return $firstMetric > 1 ? $firstMetric - 1 : null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, string>  $warnings
     * @return array<string, mixed>
     */
    private function buildResult(
        string $spCode,
        Worksheet $sheet,
        array $header,
        int $headerRow,
        array $rows,
        array $warnings = []
    ): array {
        return [
            'formulario_codigo' => $spCode,
                'hoja' => $sheet->getTitle(),
                'departamento' => $header['departamento'],
                'establecimiento_nombre' => $header['establecimiento'],
                'codigo_planilla' => $header['codigo'],
                'periodo_mes' => $header['mes'],
                'periodo_anio' => $header['anio'],
                'fila_encabezado' => $headerRow,
                'filas' => $rows,
                'filas_detectadas' => count($rows),
                'advertencias' => $warnings,
            ];
    }

    /**
     * @param  array<string, int>  $metricas
     * @return array<string, mixed>
     */
    private function normalizeRow(int $row, string $cod, string $label, array $metricas): array
    {
        return [
            'key' => 'fila_'.$row,
            'fila' => $row,
            'cod_planilla' => $cod,
            'prestacion_label' => $label,
            'especialidad' => $label,
            'metricas' => $metricas,
            ...$metricas,
        ];
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $header
     * @return array<string, mixed>
     */
    private function mergeContexto(array $base, array $header): array
    {
        foreach (['departamento', 'establecimiento', 'codigo', 'mes', 'anio'] as $key) {
            $target = match ($key) {
                'establecimiento' => 'establecimiento_nombre',
                'codigo' => 'codigo_planilla',
                'mes' => 'periodo_mes',
                'anio' => 'periodo_anio',
                default => 'departamento',
            };
            $value = $header[$key] ?? null;
            if ($key === 'codigo' && ! $this->isValidCodigo($value)) {
                continue;
            }
            if ($value !== null && $value !== '') {
                $base[$target] = $value;
            }
        }

        return $base;
    }

    /**
     * @param  array<int, array<string, mixed>>  $hojas
     */
    private function resolveCodigoFromHojas(array $hojas): ?string
    {
        foreach ($hojas as $hoja) {
            $codigo = $hoja['codigo_planilla'] ?? $hoja['detectado']['codigo_planilla'] ?? null;
            if ($this->isValidCodigo($codigo)) {
                return (string) $codigo;
            }
        }

        return null;
    }

    private function isValidCodigo(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        $value = trim((string) $value);
        if (! preg_match('/^\d+$/', $value)) {
            return false;
        }

        return ! in_array(Str::upper($value), ['DESCRIPCION', 'CODIGO'], true);
    }

    private function looksLikeCodigo(string $value): bool
    {
        return preg_match('/^\d+$/', trim($value)) === 1;
    }

    /**
     * @param  array<int, string>  $headers
     */
    private function resolveLabelColumn(array $headers, int $totalColumn): int
    {
        foreach ($headers as $col => $label) {
            $key = $this->normalizeKey($label);
            if ($key === '' || $this->isCodHeader($key) || $col === $totalColumn) {
                continue;
            }
            if ($this->headerMatchesRole($key, 'label')
                || preg_match('/(prestacion|servicio|metodo|urgencia|enfermeria|vacuna|programa|medicamento|insumo|procedimiento|odontolog|items?)/', $key)) {
                return $col;
            }
        }

        foreach ($headers as $col => $label) {
            $key = $this->normalizeKey($label);
            if ($col !== $totalColumn && $key !== '' && ! $this->isCodHeader($key)) {
                return $col;
            }
        }

        return max(1, $totalColumn - 1);
    }

    /**
     * @param  array<int, string>  $headers
     */
    private function resolveCodColumn(array $headers): ?int
    {
        foreach ($headers as $col => $label) {
            $key = $this->normalizeKey($label);
            if ($key !== '' && $this->isCodHeader($key)) {
                return $col;
            }
        }

        return null;
    }

    /**
     * @return array{0: int|null, 1: array<int, string>, 2: int|null}
     */
    private function findGenericTableHeader(Worksheet $sheet): array
    {
        $lastRow = min(40, $sheet->getHighestDataRow());
        $lastColumn = min(12, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        $bestRow = null;
        $bestHeaders = [];
        $bestTotalCol = null;
        $bestScore = -1;

        for ($row = 1; $row <= $lastRow; $row++) {
            $headers = [];
            $nonEmpty = 0;
            $contextHits = 0;
            $totalCol = null;
            for ($col = 1; $col <= $lastColumn; $col++) {
                $value = $this->cellText($sheet, $col, $row);
                $headers[$col] = $value;
                if ($value === '') {
                    continue;
                }
                $nonEmpty++;
                if ($this->isContext($value)) {
                    $contextHits++;
                }
                $key = $this->normalizeKey($value);
                if ($key === 'total' || ($key !== '' && str_starts_with($key, 'total ') && ! str_contains($key, 'subtotal'))) {
                    $totalCol = $col;
                }
            }
            if ($nonEmpty < 2 || $contextHits > 0 || ! $totalCol) {
                continue;
            }
            $score = $nonEmpty - $contextHits;
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $row;
                $bestHeaders = $headers;
                $bestTotalCol = $totalCol;
            }
        }

        return [$bestRow, $bestHeaders, $bestTotalCol];
    }

    private function load(string $path): Spreadsheet
    {
        try {
            return IOFactory::createReaderForFile($path)
                ->setReadDataOnly(true)
                ->load($path);
        } catch (Throwable $exception) {
            throw new RuntimeException('No se pudo abrir el Excel: '.$exception->getMessage(), 0, $exception);
        }
    }

    private function detectSp1(Worksheet $sheet): bool
    {
        $scan = Str::upper(Str::ascii($sheet->getTitle()));
        $lastColumn = min(6, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        for ($row = 1; $row <= min(12, $sheet->getHighestDataRow()); $row++) {
            for ($column = 1; $column <= $lastColumn; $column++) {
                $scan .= ' '.$sheet->getCell([$column, $row])->getFormattedValue();
            }
        }
        $scan = Str::upper(Str::ascii($scan));

        return str_contains($scan, 'TABLA SP 1') || str_contains($scan, 'TABLA SP1')
            || preg_match('/\bSP\s*1\b.*CONSULT/', $scan) === 1;
    }

    /**
     * @return array{departamento: ?string, establecimiento: ?string, codigo: ?string, mes: ?int, anio: ?int}
     */
    private function parseHeader(Worksheet $sheet): array
    {
        $header = [
            'departamento' => null,
            'establecimiento' => null,
            'codigo' => null,
            'mes' => null,
            'anio' => null,
        ];
        $scanRows = min(12, $sheet->getHighestDataRow());
        for ($row = 1; $row <= $scanRows; $row++) {
            for ($col = 1; $col <= 6; $col++) {
                $raw = $this->cellText($sheet, $col, $row);
                if ($raw === '') {
                    continue;
                }

                // Layout simplificado: «DEPARTAMENTO: GUAIRA» / «CODIGO: 203» en una sola celda.
                if (str_contains($raw, ':')) {
                    $this->absorbHeaderInline($header, $raw);
                }

                if ($col > 5) {
                    continue;
                }

                $label = $this->normalizeKey($raw);
                $value = $this->cellText($sheet, $col + 1, $row);
                if ($value === '') {
                    $value = $this->cellText($sheet, $col + 2, $row);
                }
                if (str_contains($label, 'departamento')) {
                    $header['departamento'] = $this->valueAfterColon($value) ?: ($value ?: $header['departamento']);
                } elseif (str_contains($label, 'establecimiento')) {
                    $header['establecimiento'] = $this->valueAfterColon($value) ?: ($value ?: $header['establecimiento']);
                    $extra = $this->cellText($sheet, $col + 2, $row);
                    if ($header['codigo'] === null) {
                        $header['codigo'] = $this->extractCodigo($extra);
                    }
                } elseif (str_contains($label, 'codigo')) {
                    $header['codigo'] = $this->extractCodigo($value) ?? $this->extractCodigo($raw) ?? $header['codigo'];
                } elseif (str_contains($label, 'planilla') || (str_contains($label, 'mes') && ! str_contains($label, 'establecimiento'))) {
                    $mesSource = $value !== '' ? $value : $raw;
                    $header['mes'] = $this->parseMonth($mesSource) ?? $header['mes'];
                    $yearCell = $this->cellText($sheet, $col + 2, $row);
                    $header['anio'] = $this->parseYear($yearCell)
                        ?? $this->parseYear($mesSource)
                        ?? $header['anio'];
                } elseif (str_contains($label, 'ano') || str_contains($label, 'anio')) {
                    $header['anio'] = $this->parseYear($value) ?? $this->parseYear($raw) ?? $header['anio'];
                }
            }
        }

        return $header;
    }

    /**
     * @param  array{departamento: ?string, establecimiento: ?string, codigo: ?string, mes: ?int, anio: ?int}  $header
     */
    private function absorbHeaderInline(array &$header, string $raw): void
    {
        $key = $this->normalizeKey($raw);
        $value = $this->valueAfterColon($raw);

        if (str_contains($key, 'departamento') && $value !== '') {
            $header['departamento'] = $header['departamento'] ?: $value;
        }
        if (str_contains($key, 'establecimiento') && $value !== '') {
            $header['establecimiento'] = $header['establecimiento'] ?: $value;
        }
        if (str_contains($key, 'codigo')) {
            $header['codigo'] = $header['codigo'] ?: ($this->extractCodigo($raw) ?? $this->extractCodigo($value));
        }
        if (str_contains($key, 'planilla') || (str_contains($key, 'mes') && str_contains($key, 'estad'))) {
            $header['mes'] = $header['mes'] ?: $this->parseMonth($raw);
            $header['anio'] = $header['anio'] ?: $this->parseYear($raw);
        }
    }

    private function valueAfterColon(string $raw): string
    {
        if (! str_contains($raw, ':')) {
            return trim($raw);
        }
        $parts = explode(':', $raw, 2);

        return trim($parts[1] ?? '');
    }

    /**
     * @return array{row: ?int, label: ?int, cod: ?int, total: ?int, ips: ?int, convenio: ?int}
     */
    private function findSp1DataHeader(Worksheet $sheet): array
    {
        $empty = ['row' => null, 'label' => null, 'cod' => null, 'total' => null, 'ips' => null, 'convenio' => null];
        $lastRow = min(35, $sheet->getHighestDataRow());
        $lastCol = min(12, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));

        for ($row = 1; $row <= $lastRow; $row++) {
            $labels = [];
            for ($col = 1; $col <= $lastCol; $col++) {
                $labels[$col] = $this->normalizeKey($this->cellText($sheet, $col, $row));
            }

            $hasCod = false;
            $labelCol = null;
            $totalCol = null;
            $ipsCol = null;
            $convenioCol = null;

            foreach ($labels as $col => $label) {
                if ($label === '') {
                    continue;
                }
                if ($this->headerMatchesRole($label, 'cod') || $this->isCodHeader($label)) {
                    $hasCod = true;
                }
                if ($this->headerMatchesRole($label, 'ips')) {
                    $ipsCol = $col;
                    continue;
                }
                if ($this->headerMatchesRole($label, 'convenio')) {
                    $convenioCol = $col;
                    continue;
                }
                if (
                    $this->headerMatchesRole($label, 'label')
                    || str_contains($label, 'especialidad')
                    || ($label === 'consultas medicas' || str_starts_with($label, 'consultas medicas'))
                ) {
                    $labelCol = $col;
                    continue;
                }
                if ($this->headerMatchesRole($label, 'total_consultas')
                    || (str_contains($label, 'total') && str_contains($label, 'consult'))
                    || $label === 'consultas'
                    || str_starts_with($label, 'consultas ')) {
                    // No tomar «consultas medicas» como métrica si ya hay IPS/CONVENIO.
                    if (! ($ipsCol || $convenioCol) && $label !== 'consultas medicas' && ! str_starts_with($label, 'consultas medicas')) {
                        $totalCol = $col;
                    }
                } elseif ($totalCol === null && $this->headerMatchesRole($label, 'total')) {
                    $totalCol = $col;
                }
            }

            // Si hay IPS/CONVENIO, la primera columna de texto suele ser la etiqueta.
            if (($ipsCol || $convenioCol) && ! $labelCol) {
                foreach ($labels as $col => $label) {
                    if ($label === '' || $col === $ipsCol || $col === $convenioCol || $col === $totalCol) {
                        continue;
                    }
                    if ($this->headerMatchesRole($label, 'cod') || $this->isCodHeader($label)) {
                        continue;
                    }
                    $labelCol = $col;
                    break;
                }
            }

            $codCol = $hasCod ? ($this->resolveCodColumn($this->rowHeaders($sheet, $row)) ?? null) : null;

            if (($ipsCol || $convenioCol) && $labelCol) {
                return [
                    'row' => $row,
                    'label' => $labelCol,
                    'cod' => $codCol,
                    'total' => $totalCol,
                    'ips' => $ipsCol,
                    'convenio' => $convenioCol,
                ];
            }

            if ($labelCol && $totalCol) {
                return [
                    'row' => $row,
                    'label' => $labelCol,
                    'cod' => $codCol,
                    'total' => $totalCol,
                    'ips' => null,
                    'convenio' => null,
                ];
            }
        }

        return $empty;
    }

    private function sp1HeaderHasTipoSeguroExtras(Worksheet $sheet, int $headerRow): bool
    {
        foreach ($this->rowHeaders($sheet, $headerRow) as $label) {
            $key = $this->normalizeKey($label);
            if ($key === '') {
                continue;
            }
            if (
                str_contains($key, 'particular')
                || str_contains($key, 'sin seguro')
                || str_contains($key, 'tipo de seguro')
                || str_contains($key, 'privado')
                || str_contains($key, 'militar')
            ) {
                return true;
            }
        }

        return false;
    }

    private function cellText(Worksheet $sheet, int $col, int $row): string
    {
        $value = (string) $sheet->getCell([$col, $row])->getFormattedValue();

        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    /**
     * @return array<int, string>
     */
    private function rowHeaders(Worksheet $sheet, int $row): array
    {
        $lastColumn = min(16, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        $headers = [];
        for ($col = 1; $col <= $lastColumn; $col++) {
            $headers[$col] = $this->cellText($sheet, $col, $row);
        }

        return $headers;
    }

    private function columnIndex(int|string $column): int
    {
        if (is_int($column) || ctype_digit((string) $column)) {
            $index = (int) $column;
            if ($index < 1) {
                throw new RuntimeException('Columna inválida: '.$column);
            }

            return $index;
        }

        return Coordinate::columnIndexFromString(Str::upper(trim((string) $column)));
    }

    private function guessTotalColumnOnRow(Worksheet $sheet, int $row): ?int
    {
        foreach ($this->rowHeaders($sheet, $row) as $col => $label) {
            $key = $this->normalizeKey($label);
            if ($this->headerMatchesRole($key, 'total_consultas')
                || $this->headerMatchesRole($key, 'total')
                || $key === 'total'
                || ($key !== '' && str_starts_with($key, 'total ') && ! str_contains($key, 'subtotal'))) {
                return (int) $col;
            }
            if (str_contains($key, 'total') && str_contains($key, 'consult')) {
                return (int) $col;
            }
        }

        return null;
    }

    /**
     * @return array<string, int>
     */
    private function guessSp9MetricsOnRow(Worksheet $sheet, int $row): array
    {
        $metricColumns = [];
        for ($scanRow = $row; $scanRow <= min($row + 2, $sheet->getHighestDataRow()); $scanRow++) {
            for ($col = 1; $col <= 10; $col++) {
                $key = $this->normalizeKey($this->cellText($sheet, $col, $scanRow));
                if ($key === '' || str_contains($key, 'paciente')) {
                    continue;
                }
                if ($this->headerMatchesRole($key, 'consultas') || str_contains($key, 'consulta')) {
                    $metricColumns['consultas'] = $col;
                } elseif ($this->headerMatchesRole($key, 'observacion') || str_contains($key, 'observacion')) {
                    $metricColumns['observacion'] = $col;
                } elseif ($this->headerMatchesRole($key, 'procedimiento') || str_contains($key, 'procedimiento')) {
                    $metricColumns['procedimiento'] = $col;
                } elseif ($this->headerMatchesRole($key, 'total')
                    || $key === 'total'
                    || (str_starts_with($key, 'total ') && ! str_contains($key, 'general'))) {
                    $metricColumns['total'] = $col;
                }
            }
        }

        return $metricColumns;
    }

    /**
     * Sinónimos tolerantes de encabezados de columna (planillas no estándar).
     */
    private function headerMatchesRole(string $normalizedKey, string $role): bool
    {
        if ($normalizedKey === '') {
            return false;
        }

        $synonyms = match ($role) {
            'total_consultas' => ['total consultas', 'tot consultas', 'n consultas', 'nro consultas', 'cant consultas', 'total consult'],
            'total' => ['total', 'totales', 'tot'],
            'ips' => ['ips', 'i.p.s', 'i p s', 'consulta ips', 'consultas ips', 'consultas medicas ips', 'consultas medicas i.p.s'],
            'convenio' => ['convenio', 'convenios', 'consulta convenio', 'consultas convenio', 'consultas medicas convenio'],
            'pacientes' => ['pacientes', 'paciente', 'nro pacientes', 'cant pacientes', 'n pacientes'],
            'estudios' => ['estudios', 'estudio', 'analisis', 'examenes'],
            'determinaciones' => ['determinaciones', 'determinacion', 'dets'],
            'prestaciones' => ['prestaciones', 'prestacion', 'servicios realizados'],
            'consultas' => ['consultas', 'consulta', 'atenciones', 'atencion'],
            'observacion' => ['observacion', 'observaciones', 'obs'],
            'procedimiento' => ['procedimiento', 'procedimientos', 'proc'],
            'label' => ['especialidad', 'especialidades', 'prestacion', 'prestaciones', 'servicio', 'servicios', 'descripcion', 'detalle', 'urgencia', 'vacuna', 'vacunas', 'items', 'item', 'concepto', 'actividad', 'indicador'],
            'cod' => ['cod', 'codigo', 'cod.', 'id'],
            'vacuna' => ['vacuna', 'vacunas', 'vacunacion', 'inmunizacion'],
            default => [],
        };

        foreach ($synonyms as $synonym) {
            if ($normalizedKey === $synonym || str_starts_with($normalizedKey, $synonym.' ') || str_contains($normalizedKey, $synonym)) {
                // Evitar falsos positivos demasiado amplios en "total" vs "subtotal"
                if ($role === 'total' && str_contains($normalizedKey, 'subtotal')) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private function normalizeKey(string $value): string
    {
        return Str::lower(Str::ascii($value));
    }

    private function extractCodigo(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (preg_match('/CODIGO\s*[:.]?\s*(\d+)/i', $value, $match)) {
            return trim($match[1]);
        }
        if (preg_match('/^\d+$/', trim($value))) {
            return trim($value);
        }

        return null;
    }

    private function parseMonth(?string $value): ?int
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $key = Str::lower(Str::ascii(trim($value)));
        if (isset(self::MONTHS[$key])) {
            return self::MONTHS[$key];
        }
        foreach (self::MONTHS as $name => $num) {
            if (str_contains($key, $name)) {
                return $num;
            }
        }
        if (is_numeric($value) && (int) $value >= 1 && (int) $value <= 12) {
            return (int) $value;
        }

        return null;
    }

    private function parseYear(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (preg_match('/(20\d{2})/', $value, $match)) {
            return (int) $match[1];
        }

        return is_numeric($value) ? (int) $value : null;
    }

    private function inferMonthFromFilename(string $path): ?int
    {
        $name = Str::lower(Str::ascii(basename($path)));
        foreach (self::MONTHS as $label => $num) {
            if (str_contains($name, $label)) {
                return $num;
            }
        }

        return null;
    }

    private function inferYearFromFilename(string $path): ?int
    {
        if (preg_match('/\b(20\d{2})\b/', basename($path), $match)) {
            return (int) $match[1];
        }

        return null;
    }

    private function readNumeric(Worksheet $sheet, int $col, int $row): ?float
    {
        $val = $sheet->getCell([$col, $row])->getCalculatedValue();
        if ($val === null || $val === '') {
            return null;
        }

        return is_numeric($val) ? (float) $val : null;
    }

    private function isTotal(string $value): bool
    {
        $key = Str::upper(Str::ascii($value));

        return str_starts_with($key, 'TOTAL') || str_starts_with($key, 'SUBTOTAL');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function parseSp8Sheet(Worksheet $sheet, array $overrides = []): array
    {
        $header = $this->parseHeader($sheet);
        [$headerRow, $columnMap] = $this->findSp8Header($sheet);
        $columns = $overrides['columnas'] ?? [];

        if (! empty($overrides['fila_encabezado'])) {
            $headerRow = (int) $overrides['fila_encabezado'];
        }
        if ($headerRow && $columnMap === []) {
            $columnMap = $this->defaultSp8ColumnMap();
        }

        $labelCol = ! empty($columns['label']) ? $this->columnIndex($columns['label']) : 2;
        $codCol = ! empty($columns['cod']) ? $this->columnIndex($columns['cod']) : 1;

        if (! $headerRow || $columnMap === []) {
            throw new RuntimeException('No se detectó el encabezado de vacunación (COD / grupos etarios M-F). Use el asistente de mapeo.');
        }

        $rows = [];
        $lastRow = $sheet->getHighestDataRow();
        $warnings = $overrides !== [] ? ['Parseo con mapeo manual (fila encabezado '.$headerRow.').'] : [];
        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $cod = $this->cellText($sheet, $codCol, $row);
            $label = $this->cellText($sheet, $labelCol, $row);
            if ($label === '' || $this->isTotal($label)) {
                continue;
            }

            $metricas = [];
            foreach ($columnMap as $code => $col) {
                $num = $this->readNumeric($sheet, $col, $row);
                if ($num !== null && $num > 0) {
                    $metricas[$code] = (int) $num;
                }
            }
            if ($metricas === []) {
                continue;
            }

            $metricas['total'] = array_sum($metricas);

            $rows[] = $this->normalizeRow($row, $cod, $label, $metricas);
        }

        if ($rows === []) {
            throw new RuntimeException('No se encontraron filas con dosis en la planilla SP8.');
        }

        $result = $this->buildResult('SP8', $sheet, $header, $headerRow, $rows, $warnings);
        $result['layout'] = 'tabular';

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    private function parseSp11Sheet(Worksheet $sheet): array
    {
        $header = $this->parseHeader($sheet);
        [$headerRow, $dayColumns, $totalColumn] = $this->findSp11DayHeader($sheet);
        if (! $headerRow || $dayColumns === []) {
            throw new RuntimeException('No se detectó la fila de días del calendario SP11.');
        }

        $matrixRows = [];
        $lastRow = min($sheet->getHighestDataRow(), $headerRow + 20);
        for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
            $label1 = $this->cellText($sheet, 1, $row);
            $label2 = $this->cellText($sheet, 2, $row);
            $rowCode = $this->resolveSp11RowCode($label1, $label2);
            if ($rowCode === null || Sp11Matrix::isComputed($rowCode)) {
                continue;
            }

            $cells = [];
            $sum = 0;
            foreach ($dayColumns as $day => $col) {
                $num = $this->readNumeric($sheet, $col, $row);
                if ($num !== null && $num > 0) {
                    $cells[(string) $day] = (int) $num;
                    $sum += (int) $num;
                }
            }
            if ($cells === [] && $totalColumn) {
                $totalVal = $this->readNumeric($sheet, $totalColumn, $row);
                if ($totalVal !== null && $totalVal > 0) {
                    $cells['total'] = (int) $totalVal;
                    $sum = (int) $totalVal;
                }
            } else {
                $cells['total'] = $sum;
            }
            if ($sum <= 0 && ! isset($cells['total'])) {
                continue;
            }
            $matrixRows[$rowCode] = $cells;
        }

        if ($matrixRows === []) {
            throw new RuntimeException('No se encontraron filas del censo SP11 (principio, ingresos o egresos).');
        }

        $result = $this->buildResult('SP11', $sheet, $header, $headerRow, []);
        $result['layout'] = 'matriz';
        $result['matriz'] = ['rows' => $matrixRows];
        $result['filas'] = [];
        $result['filas_detectadas'] = count($matrixRows);

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    private function parseSp10Sheet(Worksheet $sheet): array
    {
        $header = $this->parseHeader($sheet);
        $episodios = $this->hospImporter->parseWorksheet($sheet);
        if ($episodios === []) {
            throw new RuntimeException('No se encontraron episodios hospitalarios en la planilla SP10.');
        }

        $result = $this->buildResult('SP10', $sheet, $header, 1, []);
        $result['layout'] = 'nominativo';
        $result['episodios'] = $episodios;
        $result['filas'] = [];
        $result['filas_detectadas'] = count($episodios);

        return $result;
    }

    /**
     * @return array{0: int|null, 1: array<string, int>}
     */
    private function findSp8Header(Worksheet $sheet): array
    {
        $lastRow = min(20, $sheet->getHighestDataRow());
        for ($row = 1; $row <= $lastRow; $row++) {
            $key = $this->normalizeKey($this->cellText($sheet, 2, $row));
            if (! $this->headerMatchesRole($key, 'vacuna') && ! str_contains($key, 'vacun')) {
                continue;
            }

            return [$row, $this->defaultSp8ColumnMap()];
        }

        return [null, []];
    }

    /**
     * @return array<string, int>
     */
    private function defaultSp8ColumnMap(): array
    {
        $map = [];
        foreach (self::SP8_AGE_GROUPS as $index => $group) {
            $map['m_'.$group] = 4 + $index;
            $map['f_'.$group] = 9 + $index;
        }

        return $map;
    }

    /**
     * @return array{0: int|null, 1: array<int, int>, 2: int|null}
     */
    private function findSp11DayHeader(Worksheet $sheet): array
    {
        $lastRow = min(20, $sheet->getHighestDataRow());
        $lastColumn = min(35, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        for ($row = 1; $row <= $lastRow; $row++) {
            $dayColumns = [];
            $totalColumn = null;
            for ($col = 3; $col <= $lastColumn; $col++) {
                $label = $this->normalizeKey($this->cellText($sheet, $col, $row));
                if ($label === 'total') {
                    $totalColumn = $col;
                    continue;
                }
                if (is_numeric($label) && (int) $label >= 1 && (int) $label <= 31) {
                    $dayColumns[(int) $label] = $col;
                }
            }
            if (count($dayColumns) >= 5) {
                return [$row, $dayColumns, $totalColumn];
            }
        }

        return [null, [], null];
    }

    private function resolveSp11RowCode(string $label1, string $label2): ?string
    {
        $key1 = $this->normalizeKey($label1);
        $key2 = $this->normalizeKey($label2);

        if ($key2 === 'altas' || str_starts_with($key2, 'alta')) {
            return 'altas';
        }
        if ($key2 === 'traslados' || str_starts_with($key2, 'traslad')) {
            return 'traslados';
        }
        if ($key2 === 'obitos' || str_starts_with($key2, 'obito') || str_contains($key2, 'fallec')) {
            return 'obitos';
        }
        if ($key2 === 'abandono' || str_starts_with($key2, 'abandon')) {
            return 'abandono';
        }
        if (str_contains($key1, 'total egresos') || str_contains($key2, 'total egresos')) {
            return 'total_egresos';
        }
        if (str_contains($key1, 'principio') || str_contains($key2, 'principio')) {
            return 'principio_dia';
        }
        if (str_contains($key1, 'total pacientes') || str_contains($key2, 'total pacientes')
            || (str_contains($key1, 'pacientes') && str_contains($key1, 'dia'))) {
            return 'total_pacientes_dia';
        }

        $code = Sp11Matrix::canonicalRow(trim($label1.' '.$label2));
        $allowed = array_keys(Sp11Matrix::ROWS);
        if (in_array($code, $allowed, true)) {
            return $code;
        }

        return null;
    }

    private function isContext(string $value): bool
    {
        $key = $this->normalizeKey($value);
        foreach (['departamento', 'establecimiento', 'codigo', 'mes', 'ano', 'anio', 'planilla', 'distrito'] as $token) {
            if (str_starts_with($key, $token)) {
                return true;
            }
        }

        return false;
    }
}
