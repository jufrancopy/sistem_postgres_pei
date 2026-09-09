<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\EspecialidadesCatalogImporter;
use App\Models\Bioestadistica\EspecialidadMedica;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class EspecialidadesCatalogImporterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_updates_existing_and_creates_missing_skipping_obs(): void
    {
        $existing = EspecialidadMedica::query()->firstOrCreate(
            ['nombre' => 'CARDIOLOGIA TEST IMPORT'],
            [
                'codigo' => null,
                'nombre_normalizado' => 'cardiologia test import',
                'especialidad_base' => 'CARDIOLOGIA TEST IMPORT',
                'orden' => 0,
                'activo' => true,
            ]
        );

        $path = storage_path('app/bioestadistica/sp-import-tmp/especialidades-import-test.xlsx');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['codigo', 'especialidad', 'obs'],
            ['9001', 'CARDIOLOGIA TEST IMPORT', ''],
            ['9002', 'NUEVA ESPECIALIDAD TEST IMPORT', ''],
            ['9003', 'OMITIDA POR OBS', 'No'],
        ]);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $summary = (new EspecialidadesCatalogImporter)->import($path);
            $this->assertSame(2, $summary['procesados']);
            $this->assertSame(1, $summary['omitidos_obs']);
            $this->assertGreaterThanOrEqual(1, $summary['actualizados']);
            $this->assertSame(1, $summary['creados']);

            $existing->refresh();
            $this->assertSame('9001', $existing->codigo);

            $created = EspecialidadMedica::query()
                ->where('nombre', 'NUEVA ESPECIALIDAD TEST IMPORT')
                ->first();
            $this->assertNotNull($created);
            $this->assertSame('9002', $created->codigo);

            $this->assertNull(
                EspecialidadMedica::query()->where('nombre', 'OMITIDA POR OBS')->value('id')
            );
        } finally {
            @unlink($path);
        }
    }
}
