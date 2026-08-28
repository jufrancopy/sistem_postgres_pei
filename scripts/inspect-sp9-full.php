<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

$sheet = IOFactory::load(base_path('.docs-bio/ESTADISTICA JULIO 2026.xls'))->getSheetByName('SP9 - URGENCIAS');
for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
    $label = trim((string) $sheet->getCell([3, $row])->getCalculatedValue());
    if ($label === '') {
        continue;
    }
    $metrics = [];
    foreach ([4 => 'C', 5 => 'O', 6 => 'P', 7 => 'T'] as $col => $k) {
        $v = trim((string) $sheet->getCell([$col, $row])->getCalculatedValue());
        if ($v !== '') {
            $metrics[$k] = $v;
        }
    }
    echo sprintf("R%02d %-45s %s\n", $row, mb_substr($label, 0, 45), $metrics ? json_encode($metrics) : '');
}
