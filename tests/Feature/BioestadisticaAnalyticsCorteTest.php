<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Dashboards\DashboardService;
use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Indicators\IndicatorEngine;
use App\Application\Bioestadistica\Reports\ReportBuilder;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoServicio;
use App\Models\Bioestadistica\EstructuraDepartamento;
use App\Models\Bioestadistica\EstructuraServicio;
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

    public function test_indicators_reports_and_dashboards_sum_servicios_without_inflating_coverage(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        $sp1 = Formulario::where('codigo', 'SP1')->first();
        $field = Field::where('code', 'consultas_por_especialidad')->first();
        $indicator = Indicador::where('codigo', 'TOTAL_CONSULTAS')->first();
        $departamento = EstructuraDepartamento::query()->first();
        $servicios = $departamento
            ? EstructuraServicio::query()->where('departamento_id', $departamento->id)->limit(2)->get()
            : collect();
        if (! $user || ! $establecimiento || ! $sp1 || ! $field || ! $indicator || $servicios->count() < 2) {
            $this->markTestSkipped('Faltan datos piloto (SP1, TOTAL_CONSULTAS o dos servicios).');
        }

        $year = 2093;
        $month = 5;
        Record::where('formulario_id', $sp1->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->delete();
        EstablecimientoServicio::where('establecimiento_id', $establecimiento->id)->delete();

        foreach ($servicios as $index => $servicio) {
            EstablecimientoServicio::create([
                'establecimiento_id' => $establecimiento->id,
                'departamento_id' => $departamento->id,
                'servicio_id' => $servicio->id,
            ]);
            $record = Record::create([
                'formulario_id' => $sp1->id,
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => $year,
                'periodo_mes' => $month,
                'estructura_departamento_id' => $departamento->id,
                'estructura_servicio_id' => $servicio->id,
                'estado' => Record::ESTADO_APROBADO,
                'created_by' => $user->id,
            ]);
            RecordValue::create([
                'record_id' => $record->id,
                'field_id' => $field->id,
                'value_json' => [
                    'rows' => [
                        '1' => ['total_consultas' => $index === 0 ? 10 : 25],
                    ],
                ],
            ]);
        }

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

    public function test_sp11_hospital_metrics_sum_all_servicio_matrices(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        $sp11 = Formulario::where('codigo', 'SP11')->first();
        $field = Field::where('code', 'paciente_dia')->first();
        $departamento = EstructuraDepartamento::query()->first();
        $servicios = $departamento
            ? EstructuraServicio::query()->where('departamento_id', $departamento->id)->limit(2)->get()
            : collect();
        if (! $user || ! $establecimiento || ! $sp11 || ! $field || $servicios->count() < 2) {
            $this->markTestSkipped('Faltan SP11, campo paciente_dia o dos servicios.');
        }

        $year = 2092;
        $month = 6;
        Record::where('formulario_id', $sp11->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->delete();

        foreach ($servicios as $index => $servicio) {
            $record = Record::create([
                'formulario_id' => $sp11->id,
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => $year,
                'periodo_mes' => $month,
                'estructura_departamento_id' => $departamento->id,
                'estructura_servicio_id' => $servicio->id,
                'estado' => Record::ESTADO_APROBADO,
                'created_by' => $user->id,
            ]);
            $pacientes = $index === 0 ? 4 : 6;
            RecordValue::create([
                'record_id' => $record->id,
                'field_id' => $field->id,
                'value_json' => [
                    'rows' => [
                        'pacientes_dia' => ['1' => $pacientes, 'total' => $pacientes],
                        'camas_operativas' => ['1' => 2, 'total' => 2],
                        'camas_disponibles' => ['1' => 2, 'total' => 2],
                    ],
                ],
            ]);
        }

        $metrics = app(HospitalizationService::class)->metrics(
            (int) $establecimiento->id,
            $year,
            $month
        );

        $this->assertSame(10, $metrics['pacientes_dia']);
        $this->assertSame(4, $metrics['camas_operativas']);
    }
}
