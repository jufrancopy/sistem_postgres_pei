<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Application\Bioestadistica\Reports\ReportBuilder;
use App\Application\Bioestadistica\Reports\ReportDefinitionValidator;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\RecordValue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BioestadisticaReportPeriodoFormatTest extends TestCase
{
    use DatabaseTransactions;

    public function test_periodo_format_defaults_to_mm_yyyy_and_accepts_dd_mm_yyyy(): void
    {
        $validator = app(ReportDefinitionValidator::class);
        $base = [
            'consolidado' => true,
            'agg' => 'sum',
            'dimensions' => ['periodo'],
            'limit' => 100,
        ];

        $default = $validator->validate($base);
        $this->assertSame('mm/yyyy', $default['periodo_format']);

        $dated = $validator->validate($base + ['periodo_format' => 'dd/mm/yyyy']);
        $this->assertSame('dd/mm/yyyy', $dated['periodo_format']);

        $this->expectException(ValidationException::class);
        $validator->validate($base + ['periodo_format' => 'yyyy-mm-dd']);
    }

    public function test_end_of_month_helper_matches_last_calendar_day(): void
    {
        $this->assertSame('31/01/2026', PeriodContext::endOfMonthDate(['anio' => 2026, 'mes' => 1]));
        $this->assertSame('28/02/2026', PeriodContext::endOfMonthDate(['anio' => 2026, 'mes' => 2]));
        $this->assertSame('29/02/2024', PeriodContext::endOfMonthDate(['anio' => 2024, 'mes' => 2]));
    }

    public function test_report_rows_and_export_use_end_of_month_dd_mm_yyyy_when_configured(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        $sp1 = Formulario::where('codigo', 'SP1')->first();
        $field = Field::where('code', 'consultas_por_especialidad')->first();
        if (! $user || ! $establecimiento || ! $sp1 || ! $field) {
            $this->markTestSkipped('Faltan datos piloto SP1.');
        }

        $year = 2094;
        $month = 2; // no bisiesto → 28/02/2094
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
            'value_json' => ['rows' => ['1' => ['total_consultas' => 7]]],
        ]);

        $period = ['anio' => $year, 'mes' => $month];
        $result = app(ReportBuilder::class)->execute([
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
            'agg' => 'sum',
            'dimensions' => ['periodo'],
            'periodo_format' => 'dd/mm/yyyy',
            'limit' => 100,
            'totales' => true,
        ], $user, [
            'periodo_desde' => $period,
            'periodo_hasta' => $period,
        ]);

        $this->assertSame('dd/mm/yyyy', $result['meta']['periodo_format']);
        $this->assertNotEmpty($result['rows']);
        $this->assertSame('28/02/2094', $result['rows'][0]['periodo']);

        $default = app(ReportBuilder::class)->execute([
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
            'agg' => 'sum',
            'dimensions' => ['periodo'],
            'limit' => 100,
        ], $user, [
            'periodo_desde' => $period,
            'periodo_hasta' => $period,
        ]);
        $this->assertSame('02/2094', $default['rows'][0]['periodo']);
    }
}
