<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\SpPlanillaParser;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class SpPlanillaColumnSynonymTest extends TestCase
{
    public function test_sp1_accepts_consultas_column_synonym_without_total_word(): void
    {
        $path = storage_path('app/bioestadistica/sp-import-tmp/synonym-sp1-test.xlsx');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('CONSULTAS');
        $sheet->setCellValue('B1', 'TABLA SP 1');
        $sheet->setCellValue('B3', 'COD');
        $sheet->setCellValue('C3', 'ESPECIALIDAD');
        $sheet->setCellValue('D3', 'Consultas'); // sinónimo (sin "Total")
        $sheet->setCellValue('B4', '1');
        $sheet->setCellValue('C4', 'CLINICA MEDICA');
        $sheet->setCellValue('D4', 12);

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $parsed = app(SpPlanillaParser::class)->parseSheetFromFile($path, 'CONSULTAS', 'SP1');
            $this->assertSame('SP1', $parsed['formulario_codigo']);
            $this->assertGreaterThanOrEqual(1, $parsed['filas_detectadas']);
            $this->assertSame(12, $parsed['filas'][0]['total_consultas']);
        } finally {
            @unlink($path);
        }
    }

    public function test_sp9_mapping_override_uses_manual_columns(): void
    {
        $path = storage_path('app/bioestadistica/sp-import-tmp/synonym-sp9-test.xlsx');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('URGENCIAS');
        $sheet->setCellValue('B1', 'TABLA SP 9');
        $sheet->setCellValue('B5', 'URGENCIA');
        $sheet->setCellValue('C5', 'Atencion');
        $sheet->setCellValue('D5', 'Obs');
        $sheet->setCellValue('E5', 'Proc');
        $sheet->setCellValue('F5', 'Total');
        $sheet->setCellValue('B6', 'CLINICA');
        $sheet->setCellValue('C6', 2);
        $sheet->setCellValue('D6', 1);
        $sheet->setCellValue('E6', 0);
        $sheet->setCellValue('F6', 3);

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $parsed = app(SpPlanillaParser::class)->parseSheetFromFile($path, 'URGENCIAS', 'SP9', [
                'fila_encabezado' => 5,
                'columnas' => [
                    'label' => 'B',
                    'consultas' => 'C',
                    'observacion' => 'D',
                    'procedimiento' => 'E',
                    'total' => 'F',
                ],
            ]);
            $this->assertSame('SP9', $parsed['formulario_codigo']);
            $this->assertGreaterThanOrEqual(1, $parsed['filas_detectadas']);
            $this->assertSame(2, $parsed['filas'][0]['consultas']);
            $this->assertSame(1, $parsed['filas'][0]['observacion']);
        } finally {
            @unlink($path);
        }
    }

    public function test_sp6_all_zero_totals_returns_warning_instead_of_error(): void
    {
        $path = storage_path('app/bioestadistica/sp-import-tmp/sp6-zeros-test.xlsx');
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
        $sheet->setCellValue('A9', 1);
        $sheet->setCellValue('B9', 'EXODONCIA');
        $sheet->setCellValue('C9', 0);

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $parsed = app(SpPlanillaParser::class)->parseSheetFromFile($path, 'ODONTOLOGIA', 'SP6');
            $this->assertSame(0, $parsed['filas_detectadas']);
            $this->assertNotEmpty($parsed['advertencias']);
            $this->assertStringContainsString('totales son 0', $parsed['advertencias'][0]);
        } finally {
            @unlink($path);
        }
    }
}
