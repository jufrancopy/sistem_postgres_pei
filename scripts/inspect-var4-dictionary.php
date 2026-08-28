<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

$path = base_path('.docs-bio/variables salud.xlsx');
if (! is_file($path)) {
    $path = base_path('.docs-bio/variables salud.xls');
}
$sheet = IOFactory::load($path)->getSheetByName('VARIABLES SALUD')
    ?? IOFactory::load($path)->getAllSheets()[0];

foreach (range(1, $sheet->getHighestDataRow()) as $row) {
    $cod = trim((string) $sheet->getCell([1, $row])->getCalculatedValue());
    if ($cod !== '4') {
        continue;
    }
    $tipo = trim((string) $sheet->getCell([3, $row])->getCalculatedValue());
    $prest = trim((string) $sheet->getCell([4, $row])->getCalculatedValue());
    if ($prest !== '') {
        echo "$tipo | $prest\n";
    }
}
