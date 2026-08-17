<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class BioestadisticaReportExport implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(private array $rows, private string $sheetTitle = 'Reporte')
    {
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return mb_substr($this->sheetTitle, 0, 31);
    }
}
