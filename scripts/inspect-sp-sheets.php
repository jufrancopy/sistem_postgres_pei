<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

$path = base_path('.docs-bio/ESTADISTICA JULIO 2026.xls');
$r = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);
foreach ($r->getWorksheetIterator() as $s) {
    $t = $s->getTitle();
    if (! preg_match('/SP8|SP11|SP10/i', $t)) {
        continue;
    }
    echo "=== {$t} ===\n";
    if (stripos($t, 'SP11') !== false) {
        for ($row = 10; $row <= 22; $row++) {
            $label = trim((string) $s->getCell([1, $row])->getFormattedValue());
            $label2 = trim((string) $s->getCell([2, $row])->getFormattedValue());
            $maxCol = Coordinate::columnIndexFromString($s->getHighestDataColumn());
            $days = [];
            for ($col = 3; $col <= min($maxCol, 35); $col++) {
                $h = trim((string) $s->getCell([$col, 11])->getFormattedValue());
                $v = $s->getCell([$col, $row])->getCalculatedValue();
                if (is_numeric($v) && (float) $v != 0) {
                    $days[] = ($h ?: $col).':'.$v;
                }
            }
            if ($label || $label2 || $days) {
                echo "R{$row} L1={$label} L2={$label2} nums=".implode(',', array_slice($days, 0, 5)).(count($days) > 5 ? '...' : '')."\n";
            }
        }
        echo "\n";
        continue;
    }
    $maxCol = min(15, Coordinate::columnIndexFromString($s->getHighestDataColumn()));
    for ($row = 1; $row <= 20; $row++) {
        $line = [];
        for ($col = 1; $col <= $maxCol; $col++) {
            $v = trim((string) $s->getCell([$col, $row])->getFormattedValue());
            if ($v !== '') {
                $line[] = "{$col}:{$v}";
            }
        }
        if ($line) {
            echo "R{$row} ".implode(' | ', $line)."\n";
        }
    }
    echo "\n";
}
$r->disconnectWorksheets();
