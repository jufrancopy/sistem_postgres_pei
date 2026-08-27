<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Dashboards\DashboardService;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\DashboardWidget;
use App\Models\Bioestadistica\Reporte;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BioestadisticaDashboardFuenteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_widget_payload_includes_auto_and_override_fuente(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $dashboard = Dashboard::query()->whereNull('user_id')->first();
        if (! $user || ! $dashboard) {
            $this->markTestSkipped('Falta dashboard institucional o usuario.');
        }

        $service = app(DashboardService::class);
        $period = ['anio' => 2095, 'mes' => 1];

        $indicatorWidget = DashboardWidget::create([
            'dashboard_id' => $dashboard->id,
            'tipo' => 'kpi',
            'titulo' => 'Fuente indicator test',
            'query_config' => ['indicator' => 'TOTAL_CONSULTAS'],
            'pos_x' => 0,
            'pos_y' => 90,
            'ancho' => 3,
            'alto' => 2,
        ]);
        $payload = $service->widgetPayload($indicatorWidget, $user, [
            'periodo_desde' => $period,
            'periodo_hasta' => $period,
        ]);
        $this->assertSame('Indicador TOTAL_CONSULTAS', $payload['fuente']);

        $spWidget = DashboardWidget::create([
            'dashboard_id' => $dashboard->id,
            'tipo' => 'barras',
            'titulo' => 'Fuente SP test',
            'query_config' => [
                'form' => 'SP1',
                'field' => 'consultas_por_especialidad',
                'metric' => 'total_consultas',
                'agg' => 'sum',
                'dimension' => 'establecimiento',
            ],
            'pos_x' => 0,
            'pos_y' => 91,
            'ancho' => 6,
            'alto' => 3,
        ]);
        $spPayload = $service->widgetPayload($spWidget, $user, [
            'periodo_desde' => $period,
            'periodo_hasta' => $period,
        ]);
        $this->assertSame('SP1 · total_consultas', $spPayload['fuente']);

        $override = DashboardWidget::create([
            'dashboard_id' => $dashboard->id,
            'tipo' => 'kpi',
            'titulo' => 'Fuente override test',
            'query_config' => [
                'indicator' => 'TOTAL_CONSULTAS',
                'fuente' => 'Planilla SP1 piloto',
            ],
            'pos_x' => 0,
            'pos_y' => 92,
            'ancho' => 3,
            'alto' => 2,
        ]);
        $overridePayload = $service->widgetPayload($override, $user, [
            'periodo_desde' => $period,
            'periodo_hasta' => $period,
        ]);
        $this->assertSame('Planilla SP1 piloto', $overridePayload['fuente']);

        $reporte = Reporte::query()->first();
        if ($reporte) {
            $reporteWidget = DashboardWidget::create([
                'dashboard_id' => $dashboard->id,
                'tipo' => 'tabla',
                'titulo' => 'Fuente reporte test',
                'query_config' => ['reporte_id' => $reporte->id],
                'pos_x' => 0,
                'pos_y' => 93,
                'ancho' => 12,
                'alto' => 4,
            ]);
            $reportePayload = $service->widgetPayload($reporteWidget, $user, [
                'periodo_desde' => $period,
                'periodo_hasta' => $period,
            ]);
            $this->assertSame('Reporte '.$reporte->codigo, $reportePayload['fuente']);
        }
    }
}
