<?php

/**
 * Spike: analiza planilla SP con datos (sin escribir en BD).
 * Uso: php scripts/spike-planilla-sp.php [.docs-bio/ESTADISTICA JULIO 2026.xls]
 */

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Application\Bioestadistica\Imports\ExcelImportAnalyzer;
use App\Application\Bioestadistica\Imports\FormulariosSpImporter;
use App\Application\Bioestadistica\Imports\PrestacionMatcher;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $argv[1] ?? base_path('.docs-bio/ESTADISTICA JULIO 2026.xls');
if (! is_file($file)) {
    fwrite(STDERR, "No existe: {$file}\n");
    exit(1);
}

$domains = [
    'SP1' => '1', 'SP2' => '13', 'SP3' => '12', 'SP4' => '11', 'SP5' => '10',
    'SP6' => '14', 'SP7' => '14', 'SP8' => '16', 'SP9' => '4', 'SP10' => '2',
    'SP11' => '2', 'SP12' => '17', 'SP13' => '17', 'SP14' => 'x',
];

$months = [
    'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6,
    'julio' => 7, 'agosto' => 8, 'septiembre' => 9, 'setiembre' => 9, 'octubre' => 10,
    'noviembre' => 11, 'diciembre' => 12,
];

function cleanLabel(string $value): string
{
    $value = preg_replace('/\(\s*\d+\s*\)/', ' ', $value) ?? $value;

    return trim((string) preg_replace('/\s+/u', ' ', trim($value)));
}

function isContext(string $value): bool
{
    $key = Str::lower(Str::ascii($value));
    foreach (['departamento', 'establecimiento', 'codigo', 'mes', 'ano', 'anio', 'planilla', 'distrito'] as $token) {
        if (str_starts_with($key, $token)) {
            return true;
        }
    }

    return false;
}

function isTotal(string $value): bool
{
    $key = Str::upper(Str::ascii($value));

    return str_starts_with($key, 'TOTAL') || str_starts_with($key, 'SUBTOTAL');
}

function parseMonth(?string $raw): ?int
{
    if ($raw === null || trim($raw) === '') {
        return null;
    }
    $key = Str::lower(Str::ascii(trim($raw)));
    global $months;
    if (isset($months[$key])) {
        return $months[$key];
    }
    if (preg_match('/\b(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|setiembre|octubre|noviembre|diciembre)\b/u', $key, $m)) {
        return $months[$m[1]] ?? null;
    }
    if (is_numeric($raw) && (int) $raw >= 1 && (int) $raw <= 12) {
        return (int) $raw;
    }

    return null;
}

function parseHeader(PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $scanRows = 25): array
{
    $header = [
        'departamento' => null,
        'establecimiento' => null,
        'codigo' => null,
        'mes' => null,
        'anio' => null,
        'raw_pairs' => [],
    ];
    $lastCol = min(6, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
    for ($row = 1; $row <= min($scanRows, $sheet->getHighestDataRow()); $row++) {
        for ($col = 1; $col <= $lastCol - 1; $col++) {
            $label = cleanLabel((string) $sheet->getCell([$col, $row])->getFormattedValue());
            if ($label === '' || ! isContext($label)) {
                continue;
            }
            $value = cleanLabel((string) $sheet->getCell([$col + 1, $row])->getFormattedValue());
            if ($value === '') {
                for ($c = $col + 2; $c <= $lastCol; $c++) {
                    $value = cleanLabel((string) $sheet->getCell([$c, $row])->getFormattedValue());
                    if ($value !== '') {
                        break;
                    }
                }
            }
            $header['raw_pairs'][] = ['fila' => $row, 'etiqueta' => $label, 'valor' => $value];
            $key = Str::lower(Str::ascii($label));
            if (str_contains($key, 'departamento')) {
                $header['departamento'] = $value ?: $header['departamento'];
            } elseif (str_contains($key, 'establecimiento') && ! str_contains($key, 'codigo')) {
                $header['establecimiento'] = $value ?: $header['establecimiento'];
            } elseif (str_contains($key, 'codigo')) {
                $header['codigo'] = $value ?: $header['codigo'];
            } elseif (str_contains($key, 'mes') || str_contains($key, 'planilla')) {
                $header['mes'] = parseMonth($value) ?? $header['mes'];
            } elseif (str_contains($key, 'ano') || str_contains($key, 'anio')) {
                $header['anio'] = is_numeric($value) ? (int) $value : $header['anio'];
            }
        }
    }

    return $header;
}

function detectSpCode(PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): ?string
{
    $title = Str::upper(Str::ascii($sheet->getTitle()));
    $scan = $title;
    $lastColumn = min(8, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
    for ($row = 1; $row <= min(12, $sheet->getHighestDataRow()); $row++) {
        for ($column = 1; $column <= $lastColumn; $column++) {
            $scan .= ' '.$sheet->getCell([$column, $row])->getFormattedValue();
        }
    }
    $scan = Str::upper(Str::ascii($scan));
    if (str_contains($scan, 'TABLA SP 2') || str_contains($scan, 'TABLA SP2')) {
        return 'SP2';
    }
    if (preg_match('/\bSP\s*([1-9]|1[0-4])\b/', $scan, $match)) {
        return 'SP'.((int) $match[1]);
    }
    if (preg_match('/^SP\s*([1-9]|1[0-4])\b/', $title, $match)) {
        return 'SP'.((int) $match[1]);
    }

    return null;
}

function findTableHeader(PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
{
    $lastRow = $sheet->getHighestDataRow();
    $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
    $bestRow = null;
    $bestHeaders = [];
    $bestScore = -1;
    for ($row = 1; $row <= min(40, $lastRow); $row++) {
        $headers = [];
        $nonEmpty = 0;
        $contextHits = 0;
        for ($column = 1; $column <= $lastColumn; $column++) {
            $value = cleanLabel((string) $sheet->getCell([$column, $row])->getFormattedValue());
            $headers[$column] = $value;
            if ($value === '') {
                continue;
            }
            $nonEmpty++;
            if (isContext($value)) {
                $contextHits++;
            }
        }
        if ($nonEmpty < 2 || $contextHits > 0) {
            continue;
        }
        $score = $nonEmpty - $contextHits;
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestRow = $row;
            $bestHeaders = $headers;
        }
    }

    return [$bestRow, $bestHeaders, $lastColumn, $lastRow];
}

function readNumeric($cell): ?float
{
    $val = $cell->getCalculatedValue();
    if ($val === null || $val === '') {
        return null;
    }
    if (is_numeric($val)) {
        return (float) $val;
    }

    return null;
}

echo "=== SPIKE: planilla SP con datos ===\n";
echo 'Archivo: '.basename($file)."\n\n";

$analyzer = app(ExcelImportAnalyzer::class);
$analysis = $analyzer->analyze($file);
echo 'Tipo detectado (hoy): '.$analysis['tipo']."\n";
echo 'Hojas: '.count($analysis['hojas'])."\n\n";

$structurePreview = null;
try {
    $structurePreview = app(FormulariosSpImporter::class)->preview($file);
} catch (Throwable $e) {
    echo 'Preview estructura (FormulariosSpImporter): ERROR — '.$e->getMessage()."\n\n";
}

$reader = IOFactory::createReaderForFile($file);
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($file);
$matcher = app(PrestacionMatcher::class);

$filenameYear = null;
if (preg_match('/\b(20\d{2})\b/', basename($file), $m)) {
    $filenameYear = (int) $m[1];
}
$filenameMonth = null;
foreach ($months as $name => $num) {
    if (stripos(basename($file), $name) !== false) {
        $filenameMonth = $num;
        break;
    }
}

$globalCodigo = null;
$globalMes = $filenameMonth;
$globalAnio = $filenameYear;

foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
    $sheetName = $sheet->getTitle();
    $spCode = detectSpCode($sheet);
    $header = parseHeader($sheet);
    if ($header['codigo']) {
        $globalCodigo = $header['codigo'];
    }
    if ($header['mes']) {
        $globalMes = $header['mes'];
    }
    if ($header['anio']) {
        $globalAnio = $header['anio'];
    }

    [$headerRow, $headers, $lastColumn, $lastRow] = findTableHeader($sheet);
    if (! $headerRow) {
        echo "--- Hoja: {$sheetName} ---\n";
        echo "  SP: ".($spCode ?? 'no detectado')."\n";
        echo "  Sin fila de encabezado de tabla (¿hoja auxiliar?)\n\n";
        continue;
    }

    $metricCols = [];
    $first = true;
    foreach ($headers as $colIdx => $label) {
        if ($first) {
            $first = false;
            continue;
        }
        if ($label !== '') {
            $metricCols[$colIdx] = $label;
        }
    }

    $domain = $spCode ? ($domains[$spCode] ?? null) : null;
    $rowsWithData = 0;
    $cellsWithData = 0;
    $matchLevels = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
    $samples = [];
    $unmatchedSamples = [];

    for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
        $label = cleanLabel((string) $sheet->getCell([1, $row])->getFormattedValue());
        if ($label === '' || isContext($label) || isTotal($label)) {
            continue;
        }
        $rowHasData = false;
        $rowValues = [];
        foreach ($metricCols as $colIdx => $metricLabel) {
            $num = readNumeric($sheet->getCell([$colIdx, $row]));
            if ($num !== null && $num != 0) {
                $rowHasData = true;
                $cellsWithData++;
                $rowValues[$metricLabel] = $num;
            }
        }
        if (! $rowHasData) {
            continue;
        }
        $rowsWithData++;
        $match = $matcher->match($label, $domain);
        $lvl = (int) ($match['nivel'] ?? 4);
        $matchLevels[$lvl] = ($matchLevels[$lvl] ?? 0) + 1;
        if (count($samples) < 3) {
            $samples[] = [
                'prestacion' => $label,
                'nivel' => $lvl,
                'sugerencia' => $match['sugerencia'] ?? null,
                'valores' => $rowValues,
            ];
        }
        if ($lvl >= 4 && count($unmatchedSamples) < 5) {
            $unmatchedSamples[] = $label.' → '.($match['sugerencia'] ?? 'sin sugerencia');
        }
    }

    echo "--- Hoja: {$sheetName} ---\n";
    echo '  SP detectado: '.($spCode ?? '—').($domain ? " (dominio {$domain})" : '')."\n";
    echo "  Encabezado planilla:\n";
    echo '    Departamento: '.($header['departamento'] ?? '—')."\n";
    echo '    Establecimiento: '.($header['establecimiento'] ?? '—')."\n";
    echo '    Código: '.($header['codigo'] ?? '—')."\n";
    echo '    Mes: '.($header['mes'] ?? '—').' | Año: '.($header['anio'] ?? '—')."\n";
    echo "  Tabla: fila encabezado {$headerRow}, columnas métricas: ".implode(', ', array_values($metricCols))."\n";
    echo "  Filas con datos numéricos: {$rowsWithData} | Celdas con valor: {$cellsWithData}\n";
    echo "  Match prestaciones: L1={$matchLevels[1]} L2={$matchLevels[2]} L3={$matchLevels[3]} L4={$matchLevels[4]}\n";
    if ($samples) {
        echo "  Muestra filas importables:\n";
        foreach ($samples as $s) {
            $vals = json_encode($s['valores'], JSON_UNESCAPED_UNICODE);
            echo "    · [L{$s['nivel']}] {$s['prestacion']} {$vals}\n";
        }
    }
    if ($unmatchedSamples) {
        echo "  Sin match (muestra):\n";
        foreach ($unmatchedSamples as $u) {
            echo "    · {$u}\n";
        }
    }
    echo "\n";
}

$spreadsheet->disconnectWorksheets();

$codigo = $globalCodigo;
$est = null;
if ($codigo) {
    $est = Establecimiento::where('codigo', $codigo)->first()
        ?? Establecimiento::where('codigo_sih', $codigo)->first();
}

echo "=== Resumen global ===\n";
echo 'Código establecimiento (header): '.($codigo ?? '—')."\n";
echo 'Período inferido: mes='.($globalMes ?? '—').' año='.($globalAnio ?? '—');
if (! $globalMes || ! $globalAnio) {
    echo ' (fallback desde nombre archivo: '.($filenameMonth ?? '—').'/'.($filenameYear ?? '—').')';
}
echo "\n";
if ($est) {
    echo "Establecimiento en BD: {$est->nombre} (id={$est->id})\n";
    echo 'Distrito asignado: '.($est->distrito_id ? 'sí' : 'NO — bloquearía captura')."\n";
    $servicios = $est->establecimientoServicios()->count();
    echo "Corte por servicio: ".($servicios > 0 ? "{$servicios} servicios (requiere elegir corte)" : 'no')."\n";
} else {
    echo "Establecimiento en BD: NO ENCONTRADO con código «{$codigo}»\n";
}

if ($structurePreview) {
    echo "\nComparación importador actual (solo estructura):\n";
    echo '  Prestaciones enlazadas: '.($structurePreview['prestaciones_enlazadas'] ?? 0)."\n";
    echo '  Sin match: '.($structurePreview['prestaciones_sin_match'] ?? 0)."\n";
    echo '  → El importador actual NO lee los números del cuerpo; solo etiquetas de fila.'."\n";
}

if ($globalMes && $globalAnio && $est) {
    echo "\nRecords existentes (muestra por SP detectado en hojas):\n";
    $codes = collect($structurePreview['formularios'] ?? [])->pluck('codigo')->unique()->filter();
    foreach ($codes as $code) {
        $form = Formulario::where('codigo', $code)->first();
        if (! $form) {
            continue;
        }
        $rec = Record::where('formulario_id', $form->id)
            ->where('establecimiento_id', $est->id)
            ->where('periodo_anio', $globalAnio)
            ->where('periodo_mes', $globalMes)
            ->first();
        echo "  {$code}: ".($rec ? "record #{$rec->id} ({$rec->estado})" : 'sin record')."\n";
    }
}

echo "\n=== Conclusión spike ===\n";
echo "¿Importable como planilla con datos? ";
$importable = $codigo && $globalMes && $globalAnio && $est && ($est->distrito_id ?? false);
echo $importable ? "SÍ (con nuevo SpPlanillaDataImporter)\n" : "PARCIAL — revisar bloqueos arriba\n";
echo "Tipo detectado hoy («{$analysis['tipo']}») ";
echo $analysis['tipo'] === 'formularios_sp'
    ? "→ ruta incorrecta (estructura, no datos)\n"
    : "→ requiere nuevo tipo «planilla_sp_datos»\n";
