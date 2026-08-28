<?php

require __DIR__.'/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$path = __DIR__.'/../.docs-bio/ESTADISTICA JULIO 2026.xls';
$book = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);

foreach ($book->getWorksheetIterator() as $sheet) {
    if (! str_contains($sheet->getTitle(), 'SP5')) {
        continue;
    }
    echo "Hoja: {$sheet->getTitle()}\n";
    echo "Filas: {$sheet->getHighestDataRow()}, Cols: {$sheet->getHighestDataColumn()}\n\n";
    for ($row = 1; $row <= min(25, $sheet->getHighestDataRow()); $row++) {
        $cells = [];
        for ($col = 1; $col <= 8; $col++) {
            $v = trim((string) $sheet->getCell([$col, $row])->getFormattedValue());
            if ($v !== '') {
                $cells[] = chr(64 + $col).$row.':'.$v;
            }
        }
        if ($cells !== []) {
            echo "R{$row}: ".implode(' | ', $cells)."\n";
        }
    }
    break;
}

$book->disconnectWorksheets();
