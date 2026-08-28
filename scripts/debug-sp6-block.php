<?php

require __DIR__.'/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

$book = IOFactory::createReaderForFile(__DIR__.'/../.docs-bio/ESTADISTICA JULIO 2026.xls')->setReadDataOnly(true)->load(__DIR__.'/../.docs-bio/ESTADISTICA JULIO 2026.xls');
$sheet = null;
foreach ($book->getWorksheetIterator() as $s) {
    if (str_contains($s->getTitle(), 'SP6')) {
        $sheet = $s;
        break;
    }
}

$normalizeKey = fn (string $v) => Str::lower(Str::ascii($v));
$isCodHeader = fn (string $k) => preg_match('/^cod(?:\b|_|\s|\()/', $k) === 1;
$isTotalHeader = fn (string $k) => $k === 'total' || str_starts_with($k, 'total ');
$matches = fn (string $k) => str_contains($k, 'prestacion');

$lastRow = min(50, $sheet->getHighestDataRow());
$lastColumn = min(16, \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));

for ($row = 1; $row <= $lastRow; $row++) {
    $labelCol = $totalCol = $codCol = null;
    for ($col = 1; $col <= $lastColumn; $col++) {
        $text = trim((string) $sheet->getCell([$col, $row])->getFormattedValue());
        $key = $normalizeKey($text);
        if ($isCodHeader($key)) {
            $codCol = $col;
        }
        if ($matches($key)) {
            $labelCol = $col;
        }
        if ($isTotalHeader($key) && $totalCol === null) {
            $totalCol = $col;
        }
    }
    if ($labelCol && $totalCol) {
        echo "MATCH row {$row}: cod={$codCol} label={$labelCol} total={$totalCol}\n";
        for ($r = $row + 1; $r <= min($row + 5, $lastRow); $r++) {
            $label = trim((string) $sheet->getCell([$labelCol, $r])->getFormattedValue());
            $total = $sheet->getCell([$totalCol, $r])->getCalculatedValue();
            echo "  data R{$r}: label={$label} total={$total}\n";
        }
    }
}
