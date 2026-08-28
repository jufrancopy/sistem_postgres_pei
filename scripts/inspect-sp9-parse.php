<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$parsed = app(App\Application\Bioestadistica\Imports\SpPlanillaParser::class)
    ->parseSheet(
        PhpOffice\PhpSpreadsheet\IOFactory::load(base_path('.docs-bio/ESTADISTICA JULIO 2026.xls'))
            ->getSheetByName('SP9 - URGENCIAS'),
        'SP9'
    );

foreach ($parsed['filas'] as $fila) {
    echo ($fila['field_code'] ?? '?') . ' | ' . ($fila['prestacion_label'] ?? '') . ' | ' . json_encode($fila['metricas'] ?? []) . PHP_EOL;
}
