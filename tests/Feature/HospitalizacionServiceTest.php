<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Record;
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

    public function test_hosp_count_formula_is_accepted(): void
    {
        $ast = ['op' => 'hosp_count', 'metric' => 'egresos'];
        $this->assertSame($ast, app(FormulaAstValidator::class)->validate($ast));
    }
}
