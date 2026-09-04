<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Imports\SpPlanillaImportService;
use App\Models\Bioestadistica\Formulario;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Sprint 3: planillas “sucias” (fixtures sintéticas) + pulido UX del flujo importar.
 */
class BioestadisticaSpPlanillaImportSprint3Test extends TestCase
{
    use DatabaseTransactions;

    private function dirtyWorkbookPath(): string
    {
        $path = storage_path('app/bioestadistica/sp-import-tmp/dirty-sprint3.xlsx');
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $spreadsheet = new Spreadsheet();

        // Hoja mal nombrada, sin «TABLA SP 1», encabezado corrido y sinónimo de columna.
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Consultas del mes');
        $sheet->setCellValue('A1', 'INSTITUTO DE PREVISION SOCIAL');
        $sheet->setCellValue('A3', 'DEPARTAMENTO: CENTRAL');
        $sheet->setCellValue('A4', 'ESTABLECIMIENTO: HOSPITAL DE PRUEBA');
        $sheet->setCellValue('A5', 'CODIGO: 73');
        $sheet->setCellValue('A6', 'PLANILLA ESTADISTICA DEL MES DE: ENERO / 2098');
        $sheet->setCellValue('A14', 'ID');
        $sheet->setCellValue('B14', 'ITEMS');
        $sheet->setCellValue('C14', 'Consultas'); // sinónimo de total consultas
        $sheet->setCellValue('A15', '1');
        $sheet->setCellValue('B15', 'CLINICA MEDICA');
        $sheet->setCellValue('C15', 12);
        $sheet->setCellValue('A16', '2');
        $sheet->setCellValue('B16', 'PEDIATRIA');
        $sheet->setCellValue('C16', 4);

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        return $path;
    }

    public function test_import_index_shows_checklist(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario.');
        }

        $this->actingAs($user)
            ->get(route('bioestadistica.captura.import.index'))
            ->assertOk()
            ->assertSee('Checklist mínimo antes de subir')
            ->assertSee('Ajustar mapeo');
    }

    public function test_dirty_workbook_mapping_recovers_sp1_rows(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $formulario = Formulario::where('codigo', 'SP1')->where('estado', 'activo')->first();
        if (! $user || ! $formulario) {
            $this->markTestSkipped('Faltan usuario o SP1.');
        }

        $path = $this->dirtyWorkbookPath();
        $service = app(SpPlanillaImportService::class);

        try {
            $file = new UploadedFile($path, 'dirty-sprint3.xlsx', null, null, true);
            $preview = $service->analyze($file, $user);
            $preview = $service->enrichWorkbookForSummary($preview);

            $this->assertNotEmpty($preview['workbook']['hojas'] ?? []);
            $hoja = $preview['workbook']['hojas'][0];
            $this->assertSame('Consultas del mes', $hoja['titulo'] ?? null);

            // Puede o no auto-detectar SP1 por título; el mapeo debe recuperar filas.
            $preview = $service->applySheetMapping($preview, (string) $hoja['titulo'], [
                'formulario_codigo' => 'SP1',
                'fila_encabezado' => 14,
                'columnas' => [
                    'cod' => 'A',
                    'label' => 'B',
                    'total_consultas' => 'C',
                ],
            ]);

            $this->assertSame('SP1', $preview['detectado']['formulario_codigo'] ?? $preview['formulario_codigo']);
            $this->assertGreaterThanOrEqual(2, $preview['detectado']['filas_detectadas'] ?? 0);
            $labels = collect($preview['detectado']['filas'] ?? [])->pluck('prestacion_label')->all();
            $this->assertContains('CLINICA MEDICA', $labels);
            $this->assertContains('PEDIATRIA', $labels);
            $this->assertSame(1, (int) ($preview['detectado']['periodo_mes'] ?? 0));
            $this->assertSame(2098, (int) ($preview['detectado']['periodo_anio'] ?? 0));
        } finally {
            if (! empty($preview['temp_path'] ?? null) && is_file($preview['temp_path'])) {
                @unlink($preview['temp_path']);
            }
            @unlink($path);
        }
    }

    public function test_preview_shows_match_filters_and_period_hint(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario.');
        }

        $path = $this->dirtyWorkbookPath();
        $service = app(SpPlanillaImportService::class);

        try {
            $file = new UploadedFile($path, 'dirty-sprint3.xlsx', null, null, true);
            $preview = $service->analyze($file, $user);
            $hoja = $preview['workbook']['hojas'][0]['titulo'] ?? 'Consultas del mes';
            $preview = $service->applySheetMapping($preview, (string) $hoja, [
                'formulario_codigo' => 'SP1',
                'fila_encabezado' => 14,
                'columnas' => [
                    'cod' => 'A',
                    'label' => 'B',
                    'total_consultas' => 'C',
                ],
            ]);
            // Período confirmado distinto al del Excel → hint.
            $preview = $service->applyPreviewContext($preview, [
                'periodo_anio' => 2099,
                'periodo_mes' => 2,
            ]);
            $service->storePreview($preview);

            $this->actingAs($user)
                ->get(route('bioestadistica.captura.import.preview', ['hoja' => $hoja]))
                ->assertOk()
                ->assertSee('bio-match-filters', false)
                ->assertSee('Sin match')
                ->assertSee('Sugeridas')
                ->assertSee('no coincide con el confirmado')
                ->assertSee('Ajustar mapeo');
        } finally {
            $service->forgetPreview();
            @unlink($path);
        }
    }

    public function test_calidad_motivos_are_actionable_without_sp(): void
    {
        $service = app(SpPlanillaImportService::class);
        $calidad = $service->computeCalidad([
            'sp_codigo' => null,
            'parseado' => false,
            'filas_detectadas' => 0,
        ], []);

        $this->assertTrue($calidad['requiere_asistente']);
        $this->assertTrue(
            collect($calidad['motivos'])->contains(fn ($m) => str_contains((string) $m, 'Ajustar mapeo'))
        );
    }
}
