<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\VariablesSaludSheetReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

class VariablesSaludSheetReaderTest extends TestCase
{
    public function test_skips_domain_x_and_yellow_rows(): void
    {
        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'vars-salud-reader-test.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('VARIABLES SALUD');
        $sheet->fromArray([
            ['CODIGO DE VARIABLE', 'DESCRIPCION DE VARIABLE', 'TIPO DE REGISTRO', 'PRESTACIONES'],
            ['1', 'AMBULATORIO', 'CONSULTA POR ESPECIALIDAD', 'CARDIOLOGIA'],
            ['x', 'MEDICAMENTOS', 'INSUMOS', 'PARACETAMOL'],
            ['4', 'URGENCIAS', 'ATENCION DE URGENCIAS ADULTOS', 'CONSULTA DE URGENCIAS ADULTOS'],
            ['4', 'URGENCIAS', 'ATENCION DE URGENCIAS ADULTOS', 'URGENCIA CLINICA'],
        ]);

        $this->paintYellow($sheet, 4, 4);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $loaded = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet = $loaded->getActiveSheet();
        $reader = new VariablesSaludSheetReader();
        $rows = iterator_to_array($reader->rows($sheet), false);

        $this->assertCount(2, $rows);
        $this->assertSame('CARDIOLOGIA', $rows[0]['prestacion']);
        $this->assertSame('URGENCIA CLINICA', $rows[1]['prestacion']);
        $this->assertTrue($reader->isExcludedDomainCodigo('x'));
        $this->assertTrue($reader->isYellowRow($sheet, 4));

        $loaded->disconnectWorksheets();
        @unlink($path);
    }

    private function paintYellow(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $row, int $col): void
    {
        $sheet->getStyle([$col, $row])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB(Color::COLOR_YELLOW);
    }
}
