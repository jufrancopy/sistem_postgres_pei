<?php

/**
 * Coteja Formularios SP.xls (plantilla vacía) contra el escáner/parser de importación.
 * Uso: php scripts/verify-formularios-sp-template.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Application\Bioestadistica\Imports\SpPlanillaImportService;
use App\Application\Bioestadistica\Imports\SpPlanillaParser;

$path = base_path('.docs-bio/Formularios SP.xls');
if (! is_file($path)) {
    fwrite(STDERR, "No se encontró: {$path}\n");
    exit(1);
}

$expected = [
    'SP1', 'SP2', 'SP3', 'SP4', 'SP5', 'SP6', 'SP7', 'SP8', 'SP9',
    'SP10', 'SP11', 'SP12', 'SP13', 'SP14',
];

$parser = app(SpPlanillaParser::class);
$importable = SpPlanillaImportService::IMPORTABLE;
$workbook = $parser->scanWorkbook($path);

echo "Archivo: Formularios SP.xls (plantilla vacía)\n";
echo str_repeat('=', 100)."\n";
printf("%-28s %-5s %-8s %-8s %-12s %s\n", 'Hoja', 'SP', 'Parser', 'Parseó', 'Importable', 'Resultado');
echo str_repeat('-', 100)."\n";

$detectedSps = [];
$structureOk = 0;
$parseOk = 0;
$parseEmpty = 0;
$parseFail = 0;
$noSp = 0;

foreach ($workbook['hojas'] as $hoja) {
    $titulo = $hoja['titulo'] ?? '—';
    $sp = $hoja['sp_codigo'] ?? '—';
    $hasParser = ($hoja['parser_disponible'] ?? false) ? 'sí' : 'no';
    $parsed = ($hoja['parseado'] ?? false) ? 'sí' : 'no';
    $canImport = ($hoja['importable'] ?? false) ? 'sí' : 'no';
    $filas = (int) ($hoja['filas_detectadas'] ?? 0);
    $error = $hoja['error'] ?? null;

    if ($sp !== '—') {
        $detectedSps[] = $sp;
    } else {
        $noSp++;
    }

    if ($hoja['parseado'] ?? false) {
        $parseOk++;
        $resultado = "{$filas} filas";
    } elseif ($error && str_contains($error, 'No se encontraron filas')) {
        $parseEmpty++;
        $resultado = 'Estructura OK, sin datos numéricos';
    } elseif ($error && str_contains($error, 'aún no implementado')) {
        $parseFail++;
        $resultado = 'Parser no implementado';
    } elseif ($error) {
        $parseFail++;
        $resultado = $error;
    } elseif (! ($hoja['sp_codigo'] ?? null)) {
        $resultado = 'SP no detectado';
    } else {
        $parseFail++;
        $resultado = 'Error desconocido';
    }

    if ($error && (
        str_contains($error, 'No se encontraron filas')
        || str_contains($error, 'No se detectó')
    ) && ($hoja['parser_disponible'] ?? false)) {
        $structureOk++;
    }

    printf(
        "%-28s %-5s %-8s %-8s %-12s %s\n",
        mb_strimwidth($titulo, 0, 28),
        $sp,
        $hasParser,
        $parsed,
        $canImport,
        mb_strimwidth($resultado, 0, 45)
    );
}

echo str_repeat('=', 100)."\n";
echo "Resumen\n";
echo "  Hojas totales: ".count($workbook['hojas'])."\n";
echo "  SP detectados: ".count(array_unique($detectedSps))." / ".count($expected)."\n";
echo "  Parse OK (con filas): {$parseOk}\n";
echo "  Estructura reconocida, vacía: {$parseEmpty}\n";
echo "  Fallos de estructura/parser: {$parseFail}\n";
echo "  Sin SP detectado: {$noSp}\n";

$missing = array_diff($expected, array_unique($detectedSps));
$extra = array_diff(array_unique($detectedSps), $expected);
if ($missing !== []) {
    echo "  SP esperados no detectados: ".implode(', ', $missing)."\n";
}
if ($extra !== []) {
    echo "  SP extra detectados: ".implode(', ', $extra)."\n";
}

echo "\nImportación habilitada en módulo (IMPORTABLE): ".implode(', ', $importable)."\n";

$parsers = SpPlanillaParser::parsedSpCodes();
$notImportable = array_diff($parsers, $importable);
if ($notImportable !== []) {
    echo "Parser listo pero importación pendiente: ".implode(', ', $notImportable)."\n";
}

exit($parseFail + $noSp > 0 ? 1 : 0);
