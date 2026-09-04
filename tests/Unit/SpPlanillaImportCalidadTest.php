<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\SpPlanillaImportService;
use Tests\TestCase;

class SpPlanillaImportCalidadTest extends TestCase
{
    public function test_compute_calidad_marks_missing_sp_as_requiring_assistant(): void
    {
        $service = app(SpPlanillaImportService::class);
        $calidad = $service->computeCalidad([
            'sp_codigo' => null,
            'parseado' => false,
            'filas_detectadas' => 0,
            'error' => 'No se detectó código SP en la hoja.',
        ]);

        $this->assertTrue($calidad['requiere_asistente']);
        $this->assertSame('fallido', $calidad['nivel']);
        $this->assertNotEmpty($calidad['motivos']);
    }

    public function test_mappable_sp_codes_cover_sprint_one(): void
    {
        $codes = app(SpPlanillaImportService::class)->mappableSpCodes();
        $this->assertSame(['SP1', 'SP2', 'SP3', 'SP4', 'SP5', 'SP6', 'SP7', 'SP8', 'SP9'], $codes);
        $this->assertNotEmpty(app(SpPlanillaImportService::class)->mappingRolesForSp('SP1'));
    }

    public function test_resolve_sheet_tolerates_whitespace_and_index(): void
    {
        $service = app(SpPlanillaImportService::class);
        $preview = [
            'workbook' => [
                'hojas' => [
                    ['titulo' => 'CONSULTAS MEDICAS', 'filas_detectadas' => 0],
                    ['titulo' => 'SP3', 'filas_detectadas' => 1],
                ],
            ],
        ];

        $byTitle = $service->resolveSheet($preview, '  consultas  medicas ');
        $this->assertNotNull($byTitle);
        $this->assertSame('CONSULTAS MEDICAS', $byTitle[1]);

        $byIndex = $service->resolveSheet($preview, 'titulo-incorrecto', 0);
        $this->assertNotNull($byIndex);
        $this->assertSame('CONSULTAS MEDICAS', $byIndex[1]);
    }

    public function test_mapping_roles_cover_sp5_to_sp9(): void
    {
        $service = app(SpPlanillaImportService::class);
        foreach (['SP5', 'SP6', 'SP8', 'SP9'] as $code) {
            $roles = $service->mappingRolesForSp($code);
            $this->assertNotEmpty($roles, $code);
            $this->assertTrue(collect($roles)->contains(fn (array $r) => $r['key'] === 'label'));
        }
        $sp9Keys = collect($service->mappingRolesForSp('SP9'))->pluck('key')->all();
        $this->assertContains('consultas', $sp9Keys);
        $this->assertContains('observacion', $sp9Keys);
    }
}
