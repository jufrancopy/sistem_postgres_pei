<?php

namespace Tests\Feature;

use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class BioestadisticaSeguimientoDatosTest extends TestCase
{
    use DatabaseTransactions;

    public function test_actividad_tab_lists_records_with_actors(): void
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
            'codigo' => 'SG'.Str::upper(Str::random(4)),
            'nombre' => 'Seguimiento actividad',
            'estado' => 'activo',
            'layout_type' => 'tabular',
            'version' => 1,
        ]);

        Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 2096,
            'periodo_mes' => 5,
            'estado' => Record::ESTADO_ENVIADO,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'submitted_by' => $user->id,
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('bioestadistica.seguimiento.index', [
                'tab' => 'actividad',
                'periodo_anio' => 2096,
                'periodo_mes' => 5,
                'formulario_ids' => [$formulario->id],
                'establecimiento_id' => $establecimiento->id,
            ]))
            ->assertOk()
            ->assertSee('Seguimiento de datos')
            ->assertSee('Actividad de usuarios')
            ->assertSee($formulario->codigo)
            ->assertSee($user->name);
    }

    public function test_pendientes_tab_marks_sin_abrir_and_sin_enviar(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            $this->markTestSkipped('Falta el esquema de captura bioestadística.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $establecimiento = Establecimiento::query()->whereNotNull('distrito_id')->first();
        if (! $user || ! $establecimiento) {
            $this->markTestSkipped('Faltan usuario o establecimiento.');
        }

        $sinAbrir = Formulario::create([
            'codigo' => 'SA'.Str::upper(Str::random(4)),
            'nombre' => 'Sin abrir',
            'estado' => 'activo',
            'layout_type' => 'tabular',
            'version' => 1,
        ]);
        $sinEnviar = Formulario::create([
            'codigo' => 'SE'.Str::upper(Str::random(4)),
            'nombre' => 'Sin enviar',
            'estado' => 'activo',
            'layout_type' => 'tabular',
            'version' => 1,
        ]);
        $alDia = Formulario::create([
            'codigo' => 'AD'.Str::upper(Str::random(4)),
            'nombre' => 'Al dia',
            'estado' => 'activo',
            'layout_type' => 'tabular',
            'version' => 1,
        ]);

        Record::create([
            'formulario_id' => $sinEnviar->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 2096,
            'periodo_mes' => 6,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        Record::create([
            'formulario_id' => $alDia->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 2096,
            'periodo_mes' => 6,
            'estado' => Record::ESTADO_APROBADO,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'submitted_by' => $user->id,
            'approved_by' => $user->id,
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);

        $filters = [
            'periodo_anio' => 2096,
            'periodo_mes' => 6,
            'formulario_ids' => [$sinAbrir->id, $sinEnviar->id, $alDia->id],
            'establecimiento_id' => $establecimiento->id,
        ];

        $result = app(\App\Application\Bioestadistica\Seguimiento\SeguimientoDatosService::class)
            ->pendientes($user, $filters);

        $this->assertSame(1, $result['summary']['sin_abrir']);
        $this->assertSame(1, $result['summary']['sin_enviar']);
        $this->assertSame(1, $result['summary']['al_dia']);
        $this->assertSame(2, $result['summary']['pendientes']);
        $this->assertTrue($result['rows']->contains(fn ($row) => $row['formulario'] === $sinAbrir->codigo && $row['situacion'] === 'sin_abrir'));
        $this->assertTrue($result['rows']->contains(fn ($row) => $row['formulario'] === $sinEnviar->codigo && $row['situacion'] === 'sin_enviar'));
        $this->assertFalse($result['rows']->contains(fn ($row) => $row['formulario'] === $alDia->codigo));

        $this->actingAs($user)
            ->get(route('bioestadistica.seguimiento.index', $filters + ['tab' => 'pendientes']))
            ->assertOk()
            ->assertSee('Sin reportar')
            ->assertSee('Sin abrir')
            ->assertSee('Sin enviar')
            ->assertSee($sinAbrir->codigo)
            ->assertSee($sinEnviar->codigo);
    }

    public function test_export_csv_requires_export_permission_and_downloads(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            $this->markTestSkipped('Falta el esquema de captura bioestadística.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario administrador.');
        }

        $query = [
            'tab' => 'actividad',
            'periodo_anio' => 2096,
            'periodo_mes' => 5,
        ];

        $this->actingAs($user)
            ->get(route('bioestadistica.seguimiento.export.csv', $query))
            ->assertOk()
            ->assertHeader('content-disposition');

        $this->actingAs($user)
            ->get(route('bioestadistica.seguimiento.export.xlsx', $query))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('bioestadistica.seguimiento.export.pdf', $query))
            ->assertOk();
    }
}
