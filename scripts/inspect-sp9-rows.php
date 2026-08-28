<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

$path = __DIR__ . '/../.docs-bio/ESTADISTICA JULIO 2026.xls';
$sheet = IOFactory::load($path)->getSheetByName('SP9 - URGENCIAS');
for ($row = 13; $row <= 40; $row++) {
    $label = trim((string) $sheet->getCell([3, $row])->getCalculatedValue());
    $c55 = trim((string) $sheet->getCell([4, $row])->getCalculatedValue());
    $c56 = trim((string) $sheet->getCell([5, $row])->getCalculatedValue());
    $c57 = trim((string) $sheet->getCell([6, $row])->getCalculatedValue());
    $c58 = trim((string) $sheet->getCell([7, $row])->getCalculatedValue());
    if ($label === '' && $c58 === '') {
        continue;
    }
    echo "R$row [$label] C=$c55 O=$c56 P=$c57 T=$c58" . PHP_EOL;
}
