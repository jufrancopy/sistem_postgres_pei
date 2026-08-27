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

class BioestadisticaRecordActorTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_persists_created_by_and_updated_by(): void
    {
        [$actor, $formulario, $establecimiento] = $this->prepareContext('CR');

        $this->actingAs($actor)
            ->post(route('bioestadistica.captura.store'), [
                'formulario_id' => $formulario->id,
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => 2097,
                'periodo_mes' => 1,
            ])
            ->assertRedirect();

        $record = $this->findRecord($formulario->id, $establecimiento->id, 2097, 1);

        $this->assertSame((int) $actor->id, (int) $record->created_by);
        $this->assertSame((int) $actor->id, (int) $record->updated_by);
        $this->assertSame(Record::ESTADO_BORRADOR, $record->estado);
    }

    public function test_save_persists_updated_by_on_record_and_value_actors(): void
    {
        [$actor, $formulario, $establecimiento] = $this->prepareContext('SV');
        $record = $this->createDraft($formulario, $establecimiento, $actor, 2097, 2);

        $this->actingAs($actor)
            ->put(route('bioestadistica.captura.update', $record), [
                'values' => ['nota' => 'guardado'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $record->refresh();
        $this->assertSame((int) $actor->id, (int) $record->updated_by);
        $this->assertSame((int) $actor->id, (int) $record->created_by);

        $value = $this->valueFor($record, 'nota');
        $this->assertSame((int) $actor->id, (int) $value->created_by);
        $this->assertSame((int) $actor->id, (int) $value->updated_by);
    }

    public function test_autosave_persists_value_created_by_and_later_updated_by(): void
    {
        [$creator, $formulario, $establecimiento] = $this->prepareContext('AS');
        $editor = User::role('Administrador')->where('id', '<>', $creator->id)->first() ?? $creator;
        $record = $this->createDraft($formulario, $establecimiento, $creator, 2097, 3);

        $this->actingAs($creator)
            ->postJson(route('bioestadistica.captura.autosave', $record), [
                'values' => ['nota' => 'parcial'],
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $value = $this->valueFor($record, 'nota');
        $this->assertSame((int) $creator->id, (int) $value->created_by);
        $this->assertSame((int) $creator->id, (int) $value->updated_by);
        $this->assertSame((int) $creator->id, (int) $record->fresh()->updated_by);

        $this->actingAs($editor)
            ->postJson(route('bioestadistica.captura.autosave', $record), [
                'values' => ['nota' => 'editado'],
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $value->refresh();
        $this->assertSame((int) $creator->id, (int) $value->created_by);
        $this->assertSame((int) $editor->id, (int) $value->updated_by);
        $this->assertSame((int) $editor->id, (int) $record->fresh()->updated_by);
    }

    public function test_submit_persists_submitted_by_and_updated_by(): void
    {
        [$actor, $formulario, $establecimiento] = $this->prepareContext('SB');
        $record = $this->createDraft($formulario, $establecimiento, $actor, 2097, 4);

        $this->actingAs($actor)
            ->put(route('bioestadistica.captura.update', $record), [
                'values' => ['nota' => 'listo para enviar'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($actor)
            ->post(route('bioestadistica.captura.submit', $record))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $record->refresh();
        $this->assertSame(Record::ESTADO_ENVIADO, $record->estado);
        $this->assertSame((int) $actor->id, (int) $record->submitted_by);
        $this->assertSame((int) $actor->id, (int) $record->updated_by);
        $this->assertNotNull($record->submitted_at);
    }

    public function test_approve_persists_approved_by_and_updated_by(): void
    {
        [$actor, $formulario, $establecimiento] = $this->prepareContext('AP');
        $record = $this->createDraft($formulario, $establecimiento, $actor, 2097, 5);
        $this->actingAs($actor)
            ->put(route('bioestadistica.captura.update', $record), [
                'values' => ['nota' => 'para aprobar'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->actingAs($actor)
            ->post(route('bioestadistica.captura.submit', $record))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($actor)
            ->post(route('bioestadistica.captura.approve', $record))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $record->refresh();
        $this->assertSame(Record::ESTADO_APROBADO, $record->estado);
        $this->assertSame((int) $actor->id, (int) $record->approved_by);
        $this->assertSame((int) $actor->id, (int) $record->updated_by);
        $this->assertNotNull($record->approved_at);
    }

    public function test_reject_persists_approved_by_and_updated_by(): void
    {
        [$actor, $formulario, $establecimiento] = $this->prepareContext('RJ');
        $record = $this->createDraft($formulario, $establecimiento, $actor, 2097, 6);
        $this->actingAs($actor)
            ->put(route('bioestadistica.captura.update', $record), [
                'values' => ['nota' => 'para objetar'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->actingAs($actor)
            ->post(route('bioestadistica.captura.submit', $record))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($actor)
            ->post(route('bioestadistica.captura.reject', $record), [
                'observacion' => 'Corregir datos',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $record->refresh();
        $this->assertSame(Record::ESTADO_OBJETADO, $record->estado);
        $this->assertSame((int) $actor->id, (int) $record->approved_by);
        $this->assertSame((int) $actor->id, (int) $record->updated_by);
        $this->assertSame('Corregir datos', $record->observacion);
    }

    /**
     * @return array{0: User, 1: Formulario, 2: Establecimiento}
     */
    private function prepareContext(string $prefix): array
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            $this->markTestSkipped('Falta el esquema de captura bioestadística.');
        }
        if (! Schema::connection('pgsql')->hasColumn('bioestadistica.records', 'updated_by')
            || ! Schema::connection('pgsql')->hasColumn('bioestadistica.record_values', 'created_by')
            || ! Schema::connection('pgsql')->hasColumn('bioestadistica.record_values', 'updated_by')) {
            $this->markTestSkipped('Faltan columnas de actor en records/record_values.');
        }

        $actor = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        if (! $actor || ! $establecimiento) {
            $this->markTestSkipped('Faltan usuario o establecimiento.');
        }

        $formulario = Formulario::create([
            'codigo' => $prefix.Str::upper(Str::random(4)),
            'nombre' => "Actor {$prefix}",
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
            'code' => 'nota',
            'label' => 'Nota',
            'type' => 'text',
            'required' => true,
            'orden' => 1,
        ]);

        return [$actor, $formulario, $establecimiento];
    }

    private function createDraft(
        Formulario $formulario,
        Establecimiento $establecimiento,
        User $actor,
        int $year,
        int $month
    ): Record {
        return Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $actor->id,
            'updated_by' => $actor->id,
        ]);
    }

    private function findRecord(int $formularioId, int $establecimientoId, int $year, int $month): Record
    {
        return Record::query()
            ->where('formulario_id', $formularioId)
            ->where('establecimiento_id', $establecimientoId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->firstOrFail();
    }

    private function valueFor(Record $record, string $code): RecordValue
    {
        return RecordValue::query()
            ->where('record_id', $record->id)
            ->whereHas('field', fn ($q) => $q->where('code', $code))
            ->firstOrFail();
    }
}
