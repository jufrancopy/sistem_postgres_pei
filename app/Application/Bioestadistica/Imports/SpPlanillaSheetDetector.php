<?php

namespace App\Application\Bioestadistica\Imports;

use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpPlanillaSheetDetector
{
    /**
     * Pistas de título → SP cuando el archivo no declara «TABLA SP N».
     * Orden: más específico primero. No atar a un Excel concreto.
     *
     * @var list<array{0: string, 1: string}>
     */
    private const TITLE_HINTS = [
        ['CONSULTAS MEDICAS', 'SP1'],
        ['CONSULTA MEDICA', 'SP1'],
        ['ENFERMERIA', 'SP2'],
        ['BAJA COMPLEJ', 'SP3'],
        ['ALTA COMPLEJ', 'SP4'],
        ['LABORATOR', 'SP5'],
        ['ODONTOLOG', 'SP6'],
        ['PROCEDIMIENT', 'SP7'],
        ['VACUN', 'SP8'],
        ['URGENC', 'SP9'],
        ['HOSPITALIZ', 'SP10'],
        ['PACIENTE DIA', 'SP11'],
        ['PACIENTE-DIA', 'SP11'],
        ['VIH', 'SP12'],
        ['TUBERCUL', 'SP12'],
        ['PROG. SALUD', 'SP13'],
        ['PROG SALUD', 'SP13'],
        ['PROGRAMAS DE SALUD', 'SP13'],
        ['PROGRAMA DE SALUD', 'SP13'],
        ['MEDICAMENT', 'SP14'],
        ['INSUMO', 'SP14'],
    ];

    public function detect(Worksheet $sheet): ?string
    {
        $scan = $this->scanText($sheet);
        $title = Str::upper(Str::ascii($sheet->getTitle()));

        // Contenido «TABLA SP 2» manda sobre títulos mal rotulados (p.ej. SP3 - ENFERMERIA).
        if (str_contains($scan, 'TABLA SP 2') || str_contains($scan, 'TABLA SP2')) {
            return 'SP2';
        }

        // Enfermería por título (legacy): antes del SP del scan para títulos «SP3 - ENFERMERIA».
        if (str_contains($title, 'ENFERMERIA')) {
            return 'SP2';
        }

        if (preg_match('/\bSP\s*([1-9]|1[0-4])\b/', $scan, $match)) {
            return 'SP'.((int) $match[1]);
        }

        if (preg_match('/^SP\s*([1-9]|1[0-4])\b/', $title, $match)) {
            return 'SP'.((int) $match[1]);
        }

        return $this->detectByTitleHint($title);
    }

    private function detectByTitleHint(string $normalizedTitle): ?string
    {
        foreach (self::TITLE_HINTS as [$needle, $sp]) {
            if (str_contains($normalizedTitle, $needle)) {
                return $sp;
            }
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
