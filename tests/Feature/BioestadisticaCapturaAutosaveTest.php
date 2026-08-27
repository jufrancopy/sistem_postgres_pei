<?php

namespace Tests\Feature;

use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\FormSeccion;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\RecordValue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class BioestadisticaCapturaAutosaveTest extends TestCase
{
    use DatabaseTransactions;

    public function test_autosave_persists_partial_draft_without_required_fields(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            $this->markTestSkipped('Falta el esquema de captura bioestadística.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        if (! $user || ! $establecimiento) {
            $this->markTestSkipped('Faltan usuario o establecimiento.');
        }

        $formulario = Formulario::create([
            'codigo' => 'AS'.Str::upper(Str::random(4)),
            'nombre' => 'Autosave test',
            'estado' => 'activo',
            'layout_type' => 'tabular',
            'version' => 1,
        ]);
        $seccion = FormSeccion::create([
            'formulario_id' => $formulario->id,
            'titulo' => 'Datos',
            'orden' => 1,
        ]);
        $optional = Field::create([
            'seccion_id' => $seccion->id,
            'code' => 'nota',
            'label' => 'Nota',
            'type' => 'text',
            'required' => false,
            'orden' => 1,
        ]);
        Field::create([
            'seccion_id' => $seccion->id,
            'code' => 'total',
            'label' => 'Total obligatorio',
            'type' => 'integer',
            'required' => true,
            'orden' => 2,
            'min_value' => 0,
        ]);

        $record = Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 2099,
            'periodo_mes' => 1,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->postJson(route('bioestadistica.captura.autosave', $record), [
                'values' => ['nota' => 'parcial'],
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $record->refresh();
        $this->assertSame(
            'parcial',
            RecordValue::where('record_id', $record->id)->where('field_id', $optional->id)->value('value_text')
        );
        $this->assertFalse(
            RecordValue::where('record_id', $record->id)
                ->whereHas('field', fn ($q) => $q->where('code', 'total'))
                ->exists()
        );
    }

    public function test_manual_save_still_requires_mandatory_fields(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            $this->markTestSkipped('Falta el esquema de captura bioestadística.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        if (! $user || ! $establecimiento) {
            $this->markTestSkipped('Faltan usuario o establecimiento.');
        }

        $formulario = Formulario::create([
            'codigo' => 'RQ'.Str::upper(Str::random(4)),
            'nombre' => 'Required test',
            'estado' => 'activo',
            'layout_type' => 'tabular',
            'version' => 1,
        ]);
        $seccion = FormSeccion::create([
            'formulario_id' => $formulario->id,
            'titulo' => 'Datos',
            'orden' => 1,
        ]);
        Field::create([
            'seccion_id' => $seccion->id,
            'code' => 'total',
            'label' => 'Total obligatorio',
            'type' => 'integer',
            'required' => true,
            'orden' => 1,
            'min_value' => 0,
        ]);

        $record = Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 2099,
            'periodo_mes' => 2,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->from(route('bioestadistica.captura.edit', $record))
            ->put(route('bioestadistica.captura.update', $record), [
                'values' => [],
            ])
            ->assertRedirect()
            ->assertSessionHasErrors();
    }
}
