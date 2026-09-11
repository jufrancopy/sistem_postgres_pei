<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Dashboards\DashboardService;
use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Indicators\IndicatorEngine;
use App\Application\Bioestadistica\Reports\ReportBuilder;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\IndicadorCache;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\RecordValue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BioestadisticaAnalyticsCorteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_indicators_reports_and_dashboards_use_one_record_per_establishment_period(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        $sp1 = Formulario::where('codigo', 'SP1')->first();
        $field = Field::where('code', 'consultas_por_especialidad')->first();
        $indicator = Indicador::where('codigo', 'TOTAL_CONSULTAS')->first();
        if (! $user || ! $establecimiento || ! $sp1 || ! $field || ! $indicator) {
            $this->markTestSkipped('Faltan datos piloto (SP1 o TOTAL_CONSULTAS).');
        }

        $year = 2093;
        $month = 5;
        Record::where('formulario_id', $sp1->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->delete();

        $record = Record::create([
            'formulario_id' => $sp1->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'estado' => Record::ESTADO_APROBADO,
            'created_by' => $user->id,
        ]);
        RecordValue::create([
            'record_id' => $record->id,
            'field_id' => $field->id,
            'value_json' => [
                'rows' => [
                    '1' => ['total_consultas' => 35],
                ],
            ],
        ]);

        IndicadorCache::query()
            ->where('indicador_id', $indicator->id)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->where('establecimiento_id', $establecimiento->id)
            ->delete();

        $context = [
            'periodo_desde' => ['anio' => $year, 'mes' => $month],
            'periodo_hasta' => ['anio' => $year, 'mes' => $month],
            'establecimiento_id' => $establecimiento->id,
        ];
        $evaluation = app(IndicatorEngine::class)->evaluate($indicator, $context);

        $this->assertSame(35.0, $evaluation['valor']);
        $this->assertSame(1, $evaluation['cobertura']['esperados']);
        $this->assertSame(1, $evaluation['cobertura']['informados']);
        $this->assertSame(100.0, $evaluation['cobertura']['porcentaje']);

        $report = app(ReportBuilder::class)->execute([
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
            'agg' => 'sum',
            'dimensions' => ['establecimiento', 'periodo'],
            'filtros' => [
                'estado_record' => 'aprobado',
                'establecimiento_id' => $establecimiento->id,
                'periodo_desde' => ['anio' => $year, 'mes' => $month],
                'periodo_hasta' => ['anio' => $year, 'mes' => $month],
            ],
            'limit' => 50,
            'totales' => true,
            'label' => 'Consultas',
        ], $user);

        $this->assertSame(35.0, $report['totals']['valor']);
        $this->assertCount(1, $report['rows']);
        $this->assertSame(35.0, $report['rows'][0]['valor']);
        $this->assertSame(1, $report['meta']['cobertura']['informados']);
        $this->assertLessThanOrEqual(100, $report['meta']['cobertura']['porcentaje']);

        $dashboard = Dashboard::query()->with('widgets')->where('es_default', true)->first()
            ?? Dashboard::query()->with('widgets')->first();
        $widget = $dashboard?->widgets->firstWhere('tipo', 'kpi')
            ?? $dashboard?->widgets->first();
        if ($widget) {
            $payload = app(DashboardService::class)->widgetPayload($widget, $user, [
                'periodo_desde' => ['anio' => $year, 'mes' => $month],
                'periodo_hasta' => ['anio' => $year, 'mes' => $month],
                'establecimiento_id' => $establecimiento->id,
            ]);
            if ($payload['valor'] !== null) {
                $this->assertSame(35.0, $payload['valor']);
            }
            if ($payload['cobertura']['informados'] !== null) {
                $this->assertSame(1, $payload['cobertura']['informados']);
                $this->assertLessThanOrEqual(100, $payload['cobertura']['porcentaje']);
            }
        }
    }

    public function test_sp11_hospital_metrics_read_matrix_for_establishment_period(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        $sp11 = Formulario::where('codigo', 'SP11')->first();
        $field = Field::where('code', 'paciente_dia')->first();
        if (! $user || ! $establecimiento || ! $sp11 || ! $field) {
            $this->markTestSkipped('Faltan SP11 o campo paciente_dia.');
        }

        $year = 2092;
        $month = 6;
        Record::where('formulario_id', $sp11->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->delete();

        $record = Record::create([
            'formulario_id' => $sp11->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'estado' => Record::ESTADO_APROBADO,
            'created_by' => $user->id,
        ]);
        RecordValue::create([
            'record_id' => $record->id,
            'field_id' => $field->id,
            'value_json' => [
                'rows' => [
                    'principio_dia' => ['1' => 10, 'total' => 10],
                    'ingresos' => ['1' => 0, 'total' => 0],
                    'altas' => ['1' => 0, 'total' => 0],
                    'traslados' => ['1' => 0, 'total' => 0],
                    'obitos' => ['1' => 0, 'total' => 0],
                    'abandono' => ['1' => 0, 'total' => 0],
                    'total_pacientes_dia' => ['1' => 10, 'total' => 10],
                ],
            ],
        ]);

        $metrics = app(HospitalizationService::class)->metrics(
            (int) $establecimiento->id,
            $year,
            $month
        );

        $this->assertSame(10, $metrics['pacientes_dia']);
        $this->assertSame(0, $metrics['camas_operativas']);
    }
}
