<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\ExcelImportAnalyzer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

class ExcelImportAnalyzerTest extends TestCase
{
    private array $temporaryFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryFiles as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        parent::tearDown();
    }

    public function test_analyzes_xlsx_and_detects_variables_workbook(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('VARIABLES SALUD (2)');
        $sheet->fromArray([
            ['CODIGO DE VARIABLE', 'DESCRIPCIÓN DE VARIABLE', 'TIPO DE REGISTRO', 'PRESTACIONES'],
            [1, 'Atención materna', 'Consulta', 'Control prenatal'],
            [2, 'Atención infantil', 'Consulta', 'Vacunación'],
        ]);

        $path = $this->save($spreadsheet, 'xlsx');
        $result = (new ExcelImportAnalyzer())->analyze($path);

        self::assertSame('variables_salud', $result['tipo']);
        self::assertSame(1, $result['hojas'][0]['fila_cabecera']);
        self::assertSame('descripcion_de_variable', $result['hojas'][0]['cabeceras'][1]['codigo']);
        self::assertSame('integer', $result['hojas'][0]['columnas'][0]['tipo']);
        self::assertCount(2, $result['hojas'][0]['columnas'][0]['muestras']);
        self::assertJson(json_encode($result, JSON_THROW_ON_ERROR));
    }

    public function test_opens_xls_and_infers_supported_types(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Encuesta');
        $sheet->fromArray([
            ['Cantidad', 'Monto', 'Fecha', 'Hora', 'Activo', 'Categoría', 'Observación'],
            [2, 1.5, '2026-08-01', '08:30', 'Sí', 'A', str_repeat('Texto ', 25)],
            [3, 2.75, '2026-08-02', '09:45', 'No', 'A', str_repeat('Nota ', 30)],
            [4, 3.25, '2026-08-03', '10:00', 'Sí', 'B', str_repeat('Dato ', 30)],
        ]);

        $path = $this->save($spreadsheet, 'xls');
        $result = (new ExcelImportAnalyzer())->analyze($path);
        $types = array_column($result['hojas'][0]['columnas'], 'tipo', 'codigo');

        self::assertSame('generico', $result['tipo']);
        self::assertSame('integer', $types['cantidad']);
        self::assertSame('decimal', $types['monto']);
        self::assertSame('date', $types['fecha']);
        self::assertSame('time', $types['hora']);
        self::assertSame('boolean', $types['activo']);
        self::assertSame('select', $types['categoria']);
        self::assertSame('textarea', $types['observacion']);
    }

    public function test_detects_establecimientos_workbook(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('DIM ESTABLECIMIENTOS');
        $sheet->fromArray([
            ['ID_ESTABLECIMIENTO', 'ESTABLECIMIENTO', 'DEPARTAMENTO'],
            ['1001', 'Hospital Central', 'Central'],
        ]);

        $path = $this->save($spreadsheet, 'xlsx');
        $result = (new ExcelImportAnalyzer())->analyze($path);

        self::assertSame('establecimientos_dim', $result['tipo']);
        self::assertSame('id_establecimiento', $result['hojas'][0]['columnas'][0]['codigo']);
    }

    public function test_detects_formularios_sp_workbook(): void
    {
        $spreadsheet = new Spreadsheet();
        $first = $spreadsheet->getActiveSheet();
        $first->setTitle('SP1 Consultas');
        $first->fromArray([
            ['Prestación', 'Total', 'Hombres', 'Mujeres'],
            ['Clínica Médica', 10, 4, 6],
        ]);
        $second = $spreadsheet->createSheet();
        $second->setTitle('SP3 - ENFERMERIA');
        $second->fromArray([
            ['Prestación', 'Total'],
            ['Curaciones', 3],
        ]);

        $path = $this->save($spreadsheet, 'xlsx');
        $result = (new ExcelImportAnalyzer())->analyze($path);

        self::assertSame('formularios_sp', $result['tipo']);
        self::assertCount(2, $result['hojas']);
    }

    public function test_single_sp_sheet_stays_generic(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('SP1 Consultas');
        $sheet->fromArray([
            ['Prestación', 'Total'],
            ['Clínica Médica', 10],
        ]);

        $path = $this->save($spreadsheet, 'xlsx');
        $result = (new ExcelImportAnalyzer())->analyze($path);

        self::assertSame('generico', $result['tipo']);
    }

    public function test_rejects_non_excel_files(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'bio_excel_');
        file_put_contents($path, 'not an excel file');
        $this->temporaryFiles[] = $path;

        $this->expectException(\InvalidArgumentException::class);
        (new ExcelImportAnalyzer())->analyze($path);
    }

    private function save(Spreadsheet $spreadsheet, string $extension): string
    {
        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid('bio_excel_', true).'.'.$extension;
        $writer = $extension === 'xls' ? new Xls($spreadsheet) : new Xlsx($spreadsheet);
        $writer->save($path);
        $spreadsheet->disconnectWorksheets();
        $this->temporaryFiles[] = $path;

        return $path;
    }
}
