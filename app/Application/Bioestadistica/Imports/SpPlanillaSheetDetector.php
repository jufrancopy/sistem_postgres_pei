<?php

namespace App\Application\Bioestadistica\Imports;

use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpPlanillaSheetDetector
{
    /**
     * Pistas de título → SP cuando el archivo no declara «TABLA SP N».
     * Orden: más específico primero.
     *
     * @var list<array{0: string, 1: string}>
     */
    private const TITLE_HINTS = [
        ['CONSULTAS MEDICAS', 'SP1'],
        ['CONSULTA MEDICA', 'SP1'],
        ['ESPECIALIDADES MEDICAS', 'SP1'],
        ['ESPECIALIDADES', 'SP1'],
        ['ENFERMERIA', 'SP2'],
        ['BAJA COMPLEJ', 'SP3'],
        ['ALTA COMPLEJ', 'SP4'],
        ['LABORATOR', 'SP5'],
        ['ODONTOLOG', 'SP6'],
        ['PROCEDIMIENT', 'SP7'],
        ['VACUN', 'SP8'],
        ['URGENC', 'SP9'],
        ['HOSPITALIZ', 'SP10'],
        ['INTERNACION', 'SP10'],
        ['PACIENTE DIA', 'SP11'],
        ['PACIENTE-DIA', 'SP11'],
        ['VIH', 'SP12'],
        ['TUBERCUL', 'SP12'],
        ['PROG. SALUD', 'SP13'],
        ['PROG SALUD', 'SP13'],
        ['PROGRAMAS DE SALUD', 'SP13'],
        ['PROGRAMA DE SALUD', 'SP13'],
        ['PROGRAMAS SALUD', 'SP13'],
        ['MEDICAMENT', 'SP14'],
        ['INSUMO', 'SP14'],
    ];

    public function detect(Worksheet $sheet): ?string
    {
        $scan = $this->scanText($sheet);
        $title = Str::upper(Str::ascii($sheet->getTitle()));

        // Contenido regional / mal numerado manda sobre el «SP N» del título.
        if ($byContent = $this->detectByContent($scan, $title)) {
            return $byContent;
        }

        // Contenido «TABLA SP 2» manda sobre títulos mal rotulados (p.ej. SP3 - ENFERMERIA).
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

        return $this->detectByTitleHint($title) ?? $this->detectByTitleHint($scan);
    }

    /**
     * Alias de packs regionales donde el número SP del Excel no coincide con el sistema.
     */
    private function detectByContent(string $scan, string $title): ?string
    {
        // Odontología (a menudo «SP 2» en planillas regionales → SP6 sistema).
        if (str_contains($scan, 'PRESTACIONES ODONTO')
            || (str_contains($scan, 'ODONTOLOG') && (str_contains($scan, 'DETARTRAJE') || str_contains($scan, 'EXODONCIA') || str_contains($title, 'ODONTOLOG')))
        ) {
            return 'SP6';
        }

        // Especialidades médicas / consultas (a menudo «SP 3» regional → SP1 sistema).
        if (
            str_contains($scan, 'ESPECIALIDADES MEDICAS')
            || (
                (str_contains($scan, 'ESPECIALIDADES') || str_contains($title, 'ESPECIALIDADES'))
                && (str_contains($scan, 'CONVENIO') || str_contains($scan, 'CLINICA MEDICA') || str_contains($scan, 'TOTALES'))
                && ! str_contains($scan, 'BAJA COMPLEJ')
                && ! str_contains($scan, 'ALTA COMPLEJ')
            )
        ) {
            return 'SP1';
        }

        // SP1 explícito por tipo de seguro / totales (CEDES, etc.).
        if (str_contains($scan, 'TABLA SP1') || str_contains($scan, 'TABLA SP 1') || str_contains($scan, 'TABLA SP  1')) {
            if (str_contains($scan, 'ESPECIALIDAD') || str_contains($scan, 'TIPO DE SEGURO') || str_contains($scan, 'CONSULTAS')) {
                return 'SP1';
            }
        }

        // Urgencias (a menudo «SP 8» regional → SP9; no vacunas).
        if (
            str_contains($scan, 'CONSULTAS REALIZADAS EN URGENCIA')
            || (
                (str_contains($scan, 'URGENCIA') || str_contains($title, 'URGENC'))
                && ! str_contains($scan, 'VACUN')
                && ! str_contains($title, 'VACUN')
            )
        ) {
            return 'SP9';
        }

        // Laboratorio (a menudo «SP 7» regional → SP5).
        if (
            (str_contains($scan, 'LABORATOR') || str_contains($title, 'LABORATOR'))
            && (str_contains($scan, 'DETERMINACION') || str_contains($scan, 'ANALISIS') || str_contains($scan, 'PACIENTES') || str_contains($title, 'LABORATOR'))
            && ! str_contains($scan, 'BAJA COMPLEJ')
        ) {
            // Preferir SP5 si no es claramente SP3/SP4 estudios.
            if (! str_contains($scan, 'ALTA COMPLEJ')) {
                return 'SP5';
            }
        }

        // Medicamentos (a menudo «SP 6» regional → SP14).
        if (
            (str_contains($scan, 'MEDICAMENT') || str_contains($title, 'MEDICAMENT'))
            && ! str_contains($scan, 'ODONTOLOG')
        ) {
            return 'SP14';
        }

        // Enfermería.
        if (str_contains($scan, 'ENFERMERIA') || str_contains($title, 'ENFERMERIA')) {
            return 'SP2';
        }

        return null;
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
        $lastColumn = min(12, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        for ($row = 1; $row <= min(20, $sheet->getHighestDataRow()); $row++) {
            for ($column = 1; $column <= $lastColumn; $column++) {
                $scan .= ' '.$sheet->getCell([$column, $row])->getFormattedValue();
            }
        }

        return Str::upper(Str::ascii($scan));
    }
}
