<?php

namespace App\Application\Bioestadistica\Imports;

use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpPlanillaSheetDetector
{
    public function detect(Worksheet $sheet): ?string
    {
        $scan = $this->scanText($sheet);
        $title = Str::upper(Str::ascii($sheet->getTitle()));

        if (str_contains($scan, 'TABLA SP 2') || str_contains($scan, 'TABLA SP2')) {
            return 'SP2';
        }

        if (str_contains($title, 'ENFERMERIA')) {
            return 'SP2';
        }

        if (preg_match('/\bSP\s*([1-9]|1[0-4])\b/', $scan, $match)) {
            return 'SP'.((int) $match[1]);
        }

        if (preg_match('/^SP\s*([1-9]|1[0-4])\b/', $title, $match)) {
            return 'SP'.((int) $match[1]);
        }

        return null;
    }

    private function scanText(Worksheet $sheet): string
    {
        $scan = Str::upper(Str::ascii($sheet->getTitle()));
        $lastColumn = min(8, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        for ($row = 1; $row <= min(12, $sheet->getHighestDataRow()); $row++) {
            for ($column = 1; $column <= $lastColumn; $column++) {
                $scan .= ' '.$sheet->getCell([$column, $row])->getFormattedValue();
            }
        }

        return Str::upper(Str::ascii($scan));
    }
}
