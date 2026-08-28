<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

$path = __DIR__ . '/../.docs-bio/ESTADISTICA JULIO 2026.xls';
$spreadsheet = IOFactory::load($path);
foreach ($spreadsheet->getAllSheets() as $sheet) {
    if (! preg_match('/SP\s*9|URGENCI/i', $sheet->getTitle())) {
        continue;
    }
    echo 'Sheet: ' . $sheet->getTitle() . PHP_EOL;
    for ($row = 1; $row <= 20; $row++) {
        $cells = [];
        for ($col = 1; $col <= 10; $col++) {
            $v = trim((string) $sheet->getCell([$col, $row])->getCalculatedValue());
            if ($v !== '') {
                $cells[] = "[$col]=$v";
            }
        }
        if ($cells !== []) {
            echo "R$row: " . implode(' | ', $cells) . PHP_EOL;
        }
    }
}
