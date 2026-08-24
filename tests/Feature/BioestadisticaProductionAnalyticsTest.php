<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Indicators\IndicatorEngine;
use App\Application\Bioestadistica\Reports\ReportBuilder;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Reporte;
use App\Models\User;
use Database\Seeders\BioestadisticaHospitalizacionSeeder;
use Database\Seeders\BioestadisticaIndicadoresSeeder;
use Database\Seeders\BioestadisticaReportesDashboardsSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BioestadisticaProductionAnalyticsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_production_indicators_reports_and_dashboards_are_available(): void
    {
        $this->seed([
            BioestadisticaIndicadoresSeeder::class,
            BioestadisticaReportesDashboardsSeeder::class,
            BioestadisticaHospitalizacionSeeder::class,
        ]);

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario administrador.');
        }

        foreach ([
            'TOTAL_CONSULTAS', 'TOTAL_URGENCIAS', 'TOTAL_ENFERMERIA',
            'TOTAL_DETERMINACIONES_LAB', 'TOTAL_ODONTOLOGIA', 'EGRESOS_HOSP',
        ] as $code) {
            $this->assertTrue(
                Indicador::activos()->where('codigo', $code)->exists(),
                "Falta el indicador {$code}."
            );
        }

        foreach (['CONSULTAS_SP1', 'CONSULTAS_POR_PRESTACION', 'EGRESOS_ESTABLECIMIENTO', 'SALUD_CONSOLIDADO'] as $code) {
            $this->assertTrue(Reporte::where('codigo', $code)->exists(), "Falta el reporte {$code}.");
        }

        $ambulatorio = Dashboard::query()->where('codigo', 'AMBULATORIO')->whereNull('user_id')->first();
        $hospitalario = Dashboard::query()->where('codigo', 'HOSPITALARIO')->whereNull('user_id')->first();
        $this->assertNotNull($ambulatorio);
        $this->assertGreaterThan(5, $ambulatorio->widgets()->count());
        $this->assertNotNull($hospitalario);
        $this->assertGreaterThan(5, $hospitalario->widgets()->count());

        $period = ['anio' => 2091, 'mes' => 1];
        $evaluation = app(IndicatorEngine::class)->evaluate(
            Indicador::where('codigo', 'TOTAL_CONSULTAS')->first(),
            ['periodo_desde' => $period, 'periodo_hasta' => $period]
        );
        $this->assertArrayHasKey('valor', $evaluation);

        $report = app(ReportBuilder::class)->execute(
            Reporte::where('codigo', 'CONSULTAS_SP1')->first()->definicion,
            $user,
            ['periodo_desde' => $period, 'periodo_hasta' => $period]
        );
        $this->assertIsArray($report['rows']);
        $this->assertSame('sum', $report['meta']['agg']);

        $consolidadoDef = Reporte::where('codigo', 'SALUD_CONSOLIDADO')->first()->definicion;
        $this->assertTrue($consolidadoDef['consolidado'] ?? false);
        $consolidado = app(ReportBuilder::class)->execute(
            $consolidadoDef,
            $user,
            ['periodo_desde' => $period, 'periodo_hasta' => $period]
        );
        $this->assertIsArray($consolidado['rows']);
        $this->assertTrue($consolidado['meta']['consolidado']);
        $this->assertContains('variable', array_column($consolidado['columns'], 'key'));
        $this->assertContains('catalogo_item', array_column($consolidado['columns'], 'key'));
        $this->assertContains('prestador', array_column($consolidado['columns'], 'key'));
        $this->assertSame('Prestador', collect($consolidado['columns'])->firstWhere('key', 'prestador')['label'] ?? null);
        $this->assertSame('Cantidad', collect($consolidado['columns'])->firstWhere('key', 'valor')['label'] ?? null);
    }
}
