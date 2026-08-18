<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\RecordValue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HospitalizacionServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_save_derives_period_encrypts_cedula_and_consolidates(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        if (! $user || ! $establecimiento || ! Formulario::where('codigo', 'SP10')->exists()) {
            $this->markTestSkipped('Faltan usuario, establecimiento o SP10 para la prueba de consolidación.');
        }

        $episodio = app(HospitalizationService::class)->save([
            'establecimiento_id' => $establecimiento->id,
            'cedula' => '4.567.890',
            'sexo' => 'F',
            'edad' => 28,
            'fecha_ingreso' => '2026-07-01',
            'fecha_egreso' => '2026-07-04',
            'servicio' => 'MATERNIDAD',
            'cie10' => 'O80.0',
            'tipo_alta' => 'MEJORADO',
            'cesarea' => true,
            'recien_nacido' => true,
        ], $user);

        $this->assertSame(2026, $episodio->periodo_anio);
        $this->assertSame(7, $episodio->periodo_mes);
        $this->assertSame(3, $episodio->stayDays());
        $this->assertSame('4567890', $episodio->cedula);
        $this->assertNotSame('4567890', $episodio->getRawOriginal('cedula'));
        $this->assertSame('••••890', $episodio->visibleCedula(new User()));
        $this->assertSame(HospEpisodio::hashCedula('4567890'), $episodio->cedula_hash);

        $record = Record::where('formulario_id', Formulario::where('codigo', 'SP10')->value('id'))
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', 2026)
            ->where('periodo_mes', 7)
            ->first();
        $this->assertNotNull($record);
        $values = $record->values->keyBy(fn ($value) => $value->field->code);
        $this->assertSame(1.0, (float) $values['egresos_total']->value_num);
        $this->assertSame(1.0, (float) $values['cesareas']->value_num);
        $this->assertSame(3.0, (float) $values['dias_estancia']->value_num);
    }

    public function test_capture_rejects_nominative_manual_edit(): void
    {
        $formulario = new Formulario(['codigo' => 'SP10', 'layout_type' => 'nominativo']);
        $record = new Record(['estado' => Record::ESTADO_BORRADOR]);
        $record->setRelation('formulario', $formulario);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(RecordCaptureService::class)->save($record, ['egresos_total' => 1]);
    }

    public function test_period_can_be_overridden_independently_from_clinical_dates(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        if (! $user || ! $establecimiento || ! Formulario::where('codigo', 'SP10')->exists()) {
            $this->markTestSkipped('Faltan usuario, establecimiento o SP10.');
        }

        $episodio = app(HospitalizationService::class)->save([
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 1994,
            'periodo_mes' => 3,
            'cedula' => 'F7-'.bin2hex(random_bytes(4)),
            'fecha_ingreso' => '2026-08-01',
            'fecha_egreso' => '2026-08-02',
            'tipo_alta' => 'MEJORADO',
        ], $user);

        $this->assertSame(1994, $episodio->periodo_anio);
        $this->assertSame(3, $episodio->periodo_mes);
        $this->assertSame(
            1,
            app(HospitalizationService::class)->metrics(
                (int) $establecimiento->id,
                1994,
                3
            )['egresos_total']
        );
    }

    public function test_spreadsheet_batch_creates_multiple_rows_and_consolidates_once(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        if (! $user || ! $establecimiento || ! Formulario::where('codigo', 'SP10')->exists()) {
            $this->markTestSkipped('Faltan usuario, establecimiento o SP10.');
        }

        $summary = app(HospitalizationService::class)->saveBatch(
            (int) $establecimiento->id,
            1995,
            4,
            [
                [
                    'cedula' => 'F7A'.bin2hex(random_bytes(4)),
                    'sexo' => 'F',
                    'fecha_ingreso' => '2026-08-01',
                    'fecha_egreso' => '2026-08-03',
                    'servicio' => 'MATERNIDAD',
                    'tipo_alta' => 'MEJORADO',
                    'cesarea' => true,
                ],
                [
                    'cedula' => 'F7B'.bin2hex(random_bytes(4)),
                    'sexo' => 'M',
                    'fecha_ingreso' => '2026-08-02',
                    'fecha_egreso' => '2026-08-04',
                    'servicio' => 'CLINICA_MEDICA',
                    'tipo_alta' => 'CURADO',
                ],
            ],
            $user
        );

        $this->assertSame(2, $summary['creados']);
        $this->assertSame(0, $summary['actualizados']);
        $this->assertSame(0, $summary['eliminados']);
        $this->assertSame(
            2,
            HospEpisodio::where('establecimiento_id', $establecimiento->id)
                ->where('periodo_anio', 1995)
                ->where('periodo_mes', 4)
                ->count()
        );
        $record = Record::where('formulario_id', Formulario::where('codigo', 'SP10')->value('id'))
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', 1995)
            ->where('periodo_mes', 4)
            ->first();
        $this->assertNotNull($record);
        $this->assertSame(
            2.0,
            (float) $record->values->first(
                fn ($value) => $value->field->code === 'egresos_total'
            )->value_num
        );
    }

    public function test_period_can_be_edited_for_non_nominative_sp_records(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        $formulario = Formulario::where('codigo', 'like', 'SP%')
            ->where('layout_type', '<>', 'nominativo')
            ->first();
        if (! $user || ! $establecimiento || ! $formulario) {
            $this->markTestSkipped('Faltan usuario, establecimiento o formulario SP editable.');
        }

        $year = 2098;
        $sourceMonth = 1;
        $targetMonth = 2;
        Record::where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->whereIn('periodo_mes', [$sourceMonth, $targetMonth])
            ->delete();

        $record = Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => $year,
            'periodo_mes' => $sourceMonth,
            'estado' => Record::ESTADO_APROBADO,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->put(route('bioestadistica.captura.period.update', $record), [
                'periodo_anio' => $year,
                'periodo_mes' => $targetMonth,
            ])
            ->assertRedirect();

        $record->refresh();
        $this->assertSame($year, (int) $record->periodo_anio);
        $this->assertSame($targetMonth, (int) $record->periodo_mes);
    }

    public function test_sp11_period_change_rejects_values_on_invalid_days(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        $formulario = Formulario::where('codigo', 'SP11')->with('secciones.fields')->first();
        $field = $formulario?->secciones->flatMap->fields->first(function ($item) {
            return $item->type === 'matriz'
                || ($item->config['cols'] ?? null) === 'dias_mes'
                || ($item->config['contract'] ?? null) === 'sp11_v1';
        });
        if (! $user || ! $establecimiento || ! $formulario || ! $field) {
            $this->markTestSkipped('Faltan usuario, establecimiento o campo matriz SP11.');
        }

        $year = 2097;
        Record::where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->whereIn('periodo_mes', [2, 3])
            ->delete();

        $record = Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => $year,
            'periodo_mes' => 3,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $user->id,
        ]);
        RecordValue::create([
            'record_id' => $record->id,
            'field_id' => $field->id,
            'value_json' => [
                'rows' => [
                    'ingresos' => ['1' => 1, '31' => 4, 'total' => 5],
                    'pacientes_dia' => ['1' => 2, 'total' => 2],
                    'camas_operativas' => ['1' => 10, 'total' => 10],
                ],
            ],
        ]);

        $this->actingAs($user)
            ->from(route('bioestadistica.captura.edit', $record))
            ->put(route('bioestadistica.captura.period.update', $record), [
                'periodo_anio' => $year,
                'periodo_mes' => 2,
            ])
            ->assertRedirect(route('bioestadistica.captura.edit', $record))
            ->assertSessionHasErrors('periodo_mes');

        $record->refresh();
        $this->assertSame(3, (int) $record->periodo_mes);
    }

    public function test_sp11_period_change_strips_empty_extra_days(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        $formulario = Formulario::where('codigo', 'SP11')->with('secciones.fields')->first();
        $field = $formulario?->secciones->flatMap->fields->first(function ($item) {
            return $item->type === 'matriz'
                || ($item->config['cols'] ?? null) === 'dias_mes'
                || ($item->config['contract'] ?? null) === 'sp11_v1';
        });
        if (! $user || ! $establecimiento || ! $formulario || ! $field) {
            $this->markTestSkipped('Faltan usuario, establecimiento o campo matriz SP11.');
        }

        $year = 2096;
        Record::where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $establecimiento->id)
            ->where('periodo_anio', $year)
            ->whereIn('periodo_mes', [2, 3])
            ->delete();

        $record = Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => $year,
            'periodo_mes' => 3,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $user->id,
        ]);
        RecordValue::create([
            'record_id' => $record->id,
            'field_id' => $field->id,
            'value_json' => [
                'rows' => [
                    'ingresos' => ['1' => 1, '31' => 0, 'total' => 1],
                    'pacientes_dia' => ['1' => 2, 'total' => 2],
                    'camas_operativas' => ['1' => 10, 'total' => 10],
                ],
            ],
        ]);

        $this->actingAs($user)
            ->put(route('bioestadistica.captura.period.update', $record), [
                'periodo_anio' => $year,
                'periodo_mes' => 2,
            ])
            ->assertRedirect();

        $record->refresh();
        $this->assertSame(2, (int) $record->periodo_mes);
        $payload = $record->values()->where('field_id', $field->id)->first()?->value_json;
        $this->assertArrayNotHasKey('31', $payload['rows']['ingresos'] ?? []);
        $this->assertSame(1, (int) ($payload['rows']['ingresos']['total'] ?? 0));
    }

    public function test_spreadsheet_page_is_available_to_hospitalization_managers(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        if (! $user || ! $establecimiento) {
            $this->markTestSkipped('Faltan usuario o establecimiento.');
        }

        $this->actingAs($user)
            ->get(route('bioestadistica.hospitalizacion.spreadsheet', [
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => 2026,
                'periodo_mes' => 8,
            ]))
            ->assertOk()
            ->assertSee('Planilla de carga SP10')
            ->assertSee('Guardar planilla');
    }

    public function test_spreadsheet_http_save_accepts_multiple_form_rows(): void
    {
        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::first();
        if (! $user || ! $establecimiento || ! Formulario::where('codigo', 'SP10')->exists()) {
            $this->markTestSkipped('Faltan usuario, establecimiento o SP10.');
        }
        $cedula = (string) random_int(10000000, 99999999);

        $response = $this->actingAs($user)
            ->post(route('bioestadistica.hospitalizacion.spreadsheet.save'), [
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => 2097,
                'periodo_mes' => 6,
                'rows' => [[
                    'cedula' => $cedula,
                    'sexo' => 'F',
                    'edad' => 30,
                    'fecha_ingreso' => '2026-08-10',
                    'fecha_egreso' => '2026-08-11',
                    'servicio' => 'CLINICA_MEDICA',
                    'tipo_alta' => 'MEJORADO',
                    'cirugia' => '0',
                    'cesarea' => '0',
                    'recien_nacido' => '0',
                    'eliminar' => '0',
                ]],
            ]);

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertTrue(
            HospEpisodio::where('cedula_hash', HospEpisodio::hashCedula($cedula))
                ->where('periodo_anio', 2097)
                ->where('periodo_mes', 6)
                ->exists()
        );
    }

    public function test_hosp_count_formula_is_accepted(): void
    {
        $ast = ['op' => 'hosp_count', 'metric' => 'egresos'];
        $this->assertSame($ast, app(FormulaAstValidator::class)->validate($ast));
    }
}
