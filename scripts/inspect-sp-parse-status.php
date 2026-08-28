<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$path = base_path('.docs-bio/ESTADISTICA JULIO 2026.xls');
$wb = app(App\Application\Bioestadistica\Imports\SpPlanillaParser::class)->scanWorkbook($path);

foreach ($wb['hojas'] as $h) {
    if (! in_array($h['sp_codigo'] ?? '', ['SP4', 'SP5', 'SP6', 'SP7'], true)) {
        continue;
    }
    echo $h['titulo']."\n";
    echo '  SP: '.($h['sp_codigo'] ?? '?')."\n";
    echo '  parseado: '.(($h['parseado'] ?? false) ? 'si' : 'no')."\n";
    echo '  filas: '.($h['filas_detectadas'] ?? 0)."\n";
    echo '  parser_disponible: '.(($h['parser_disponible'] ?? false) ? 'si' : 'no')."\n";
    echo '  error: '.($h['error'] ?? '—')."\n\n";
}
