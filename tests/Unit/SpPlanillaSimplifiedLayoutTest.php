<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\SpPlanillaParser;
use App\Application\Bioestadistica\Imports\SpPlanillaSheetDetector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Layouts “casi estándar” sin atar a un archivo .docs-bio concreto.
 */
class SpPlanillaSimplifiedLayoutTest extends TestCase
{
    public function test_title_hints_map_common_sheet_names_to_sp(): void
    {
        $detector = app(SpPlanillaSheetDetector::class);
        $cases = [
            ' CONSULTAS MEDICAS ' => 'SP1',
            ' ODONTOLOGIA ' => 'SP6',
            'VIH - TUBERCULOSIS' => 'SP12',
            'PROG. SALUD' => 'SP13',
            ' ENFERMERIA' => 'SP2',
            'PRODUCTIVIDAD' => null,
        ];

        foreach ($cases as $title => $expected) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle(trim($title) === '' ? 'X' : mb_substr($title, 0, 31));
            // Forzar título exacto (PhpSpreadsheet recorta); recrear con setTitle
            $sheet->setTitle(mb_substr($title, 0, 31));
            $this->assertSame($expected, $detector->detect($sheet), "title={$title}");
            $spreadsheet->disconnectWorksheets();
        }
    }

    public function test_tabla_sp2_wins_over_mistitled_enfermeria_sheet(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('SP3 - ENFERMERIA');
        $sheet->setCellValue('A5', 'TABLA SP 2 - ENFERMERIA');
        $this->assertSame('SP2', app(SpPlanillaSheetDetector::class)->detect($sheet));
        $spreadsheet->disconnectWorksheets();
    }

    public function test_id_items_total_layout_parses_sp1_and_sp2(): void
    {
        $path = storage_path('app/bioestadistica/sp-import-tmp/simplified-id-items.xlsx');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $spreadsheet = new Spreadsheet();

        $sp1 = $spreadsheet->getActiveSheet();
        $sp1->setTitle('CONSULTAS MEDICAS');
        $sp1->setCellValue('A8', 'ID');
        $sp1->setCellValue('B8', 'ITEMS');
        $sp1->setCellValue('C8', 'TOTAL');
        $sp1->setCellValue('A9', '22');
        $sp1->setCellValue('B9', 'GINECOLOGIA');
        $sp1->setCellValue('C9', 5);
        $sp1->setCellValue('A10', '39');
        $sp1->setCellValue('B10', 'OBSTETRICIA');
        $sp1->setCellValue('C10', 0);

        $sp2 = $spreadsheet->createSheet();
        $sp2->setTitle('ENFERMERIA');
        $sp2->setCellValue('A8', 'ID');
        $sp2->setCellValue('B8', 'ITEMS');
        $sp2->setCellValue('C8', 'TOTAL');
        $sp2->setCellValue('A9', '62');
        $sp2->setCellValue('B9', 'CONTROL DE TEMPERATURA');
        $sp2->setCellValue('C9', 430);

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $parser = app(SpPlanillaParser::class);
        try {
            $parsed1 = $parser->parseSheetFromFile($path, 'CONSULTAS MEDICAS', 'SP1');
            $this->assertSame(1, $parsed1['filas_detectadas']);
            $this->assertSame('22', $parsed1['filas'][0]['cod_planilla']);
            $this->assertSame('GINECOLOGIA', $parsed1['filas'][0]['prestacion_label']);
            $this->assertSame(5, $parsed1['filas'][0]['total_consultas']);

            $parsed2 = $parser->parseSheetFromFile($path, 'ENFERMERIA', 'SP2');
            $this->assertSame(1, $parsed2['filas_detectadas']);
            $this->assertSame('62', $parsed2['filas'][0]['cod_planilla']);
            $this->assertSame('CONTROL DE TEMPERATURA', $parsed2['filas'][0]['prestacion_label']);
            $this->assertSame(430, $parsed2['filas'][0]['total']);
        } finally {
            @unlink($path);
        }
    }

    public function test_id_items_total_all_zeros_returns_warning_not_hard_fail(): void
    {
        $path = storage_path('app/bioestadistica/sp-import-tmp/simplified-zeros.xlsx');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ODONTOLOGIA');
        $sheet->setCellValue('A8', 'ID');
        $sheet->setCellValue('B8', 'ITEMS');
        $sheet->setCellValue('C8', 'TOTAL');
        $sheet->setCellValue('A9', '281');
        $sheet->setCellValue('B9', 'DRENAJE');
        $sheet->setCellValue('C9', 0);

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $parsed = app(SpPlanillaParser::class)->parseSheetFromFile($path, 'ODONTOLOGIA', 'SP6');
            $this->assertSame(0, $parsed['filas_detectadas']);
            $this->assertNotEmpty($parsed['advertencias']);
        } finally {
            @unlink($path);
        }
    }
}
