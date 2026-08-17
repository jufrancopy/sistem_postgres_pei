<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Imports\FormulariosSpImporter;
use App\Application\Bioestadistica\Imports\GenericFormImporter;
use App\Models\Bioestadistica\Formulario;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class FormulariosSpImporterPreviewTest extends TestCase
{
    public function test_preview_normalizes_sp2_and_ignores_access_ids(): void
    {
        $spreadsheet = new Spreadsheet();
        $first = $spreadsheet->getActiveSheet();
        $first->setTitle('SP1');
        $first->fromArray([
            ['Departamento', 'Central', ''],
            ['Establecimiento', 'Hospital Central', ''],
            ['Prestación', 'Total', 'Hombres'],
            ['Clínica Médica (12)', 10, 4],
            ['TOTAL', 10, 4],
        ]);

        $second = $spreadsheet->createSheet();
        $second->setTitle('SP3 - ENFERMERIA');
        $second->fromArray([
            ['TABLA SP 2', '', ''],
            ['Prestación', 'Total'],
            ['Curaciones (99)', 8],
        ]);

        $third = $spreadsheet->createSheet();
        $third->setTitle('SP10 Hospitalización');
        $third->fromArray([
            ['Cédula', 'Fecha de ingreso'],
            ['123456', '2026-01-01'],
        ]);

        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid('bio_sp_', true).'.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $preview = app(FormulariosSpImporter::class)->preview($path);
            $byCode = collect($preview['formularios'])->keyBy('codigo');

            $this->assertTrue($byCode->has('SP1'));
            $this->assertTrue($byCode->has('SP2'));
            $this->assertTrue($byCode->has('SP10'));
            $this->assertContains(
                'SP2: hoja nombrada SP3 - ENFERMERIA, normalizada a SP2',
                $preview['advertencias']
            );
            $this->assertSame(['Clínica Médica'], $byCode['SP1']['filas']);
            $this->assertSame(['Curaciones'], $byCode['SP2']['filas']);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function test_generic_data_pass_rejects_sp10_nominativo(): void
    {
        $formulario = new Formulario([
            'codigo' => 'SP10',
            'layout_type' => 'nominativo',
        ]);

        $this->expectException(ValidationException::class);

        app(GenericFormImporter::class)->importData(
            'dummy.xlsx',
            $formulario,
            [
                'sheet' => 'Hoja1',
                'header_row' => 1,
                'form_code' => 'SP10',
                'form_name' => 'Hospitalización',
                'columns' => [
                    ['source' => 'Cédula', 'code' => 'cedula', 'label' => 'Cédula', 'type' => 'text'],
                ],
            ],
            ['anio' => 2026, 'mes' => 7],
            new User(),
            false
        );
    }
}
