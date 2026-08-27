<?php

namespace Tests\Feature;

use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\IndicadorFormula;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class BioestadisticaIndicadorFormulaVersionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_saving_new_formula_without_dates_closes_previous_open_formula(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.indicador_formulas')) {
            $this->markTestSkipped('Falta el esquema de indicadores.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user || ! $user->can('bio.indicator.manage')) {
            $this->markTestSkipped('Falta usuario con permiso de indicadores.');
        }

        $indicador = Indicador::create([
            'codigo' => 'TST_'.Str::upper(Str::random(6)),
            'nombre' => 'Test versionado',
            'unidad' => 'n',
            'ambito' => 'establecimiento',
            'decimales' => 0,
            'activo' => true,
        ]);

        $expression = [
            'op' => 'sum',
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
        ];

        $previous = IndicadorFormula::create([
            'indicador_id' => $indicador->id,
            'expresion' => $expression,
            'vigente_desde' => null,
            'vigente_hasta' => null,
        ]);

        $payload = [
            'formula_mode' => 'advanced',
            'expresion' => json_encode($expression, JSON_UNESCAPED_UNICODE),
        ];

        $this->actingAs($user)
            ->post(route('bioestadistica.indicadores.formulas.store', $indicador), $payload)
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $previous->refresh();
        $this->assertNotNull($previous->vigente_hasta);
        $this->assertSame(
            now()->subDay()->toDateString(),
            $previous->vigente_hasta->toDateString()
        );

        $latest = $indicador->formulas()->where('id', '<>', $previous->id)->latest('id')->first();
        $this->assertNotNull($latest);
        $this->assertSame(now()->toDateString(), $latest->vigente_desde->toDateString());
        $this->assertNull($latest->vigente_hasta);
    }

    public function test_updating_current_formula_keeps_same_id(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.indicador_formulas')) {
            $this->markTestSkipped('Falta el esquema de indicadores.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user || ! $user->can('bio.indicator.manage')) {
            $this->markTestSkipped('Falta usuario con permiso de indicadores.');
        }

        $indicador = Indicador::create([
            'codigo' => 'UPD_'.Str::upper(Str::random(6)),
            'nombre' => 'Test update in place',
            'unidad' => 'n',
            'ambito' => 'establecimiento',
            'decimales' => 0,
            'activo' => true,
        ]);

        $expression = [
            'op' => 'sum',
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
        ];
        $formula = IndicadorFormula::create([
            'indicador_id' => $indicador->id,
            'expresion' => $expression,
            'vigente_desde' => null,
            'vigente_hasta' => null,
        ]);

        $updated = $expression;
        $updated['op'] = 'avg';

        $this->actingAs($user)
            ->put(route('bioestadistica.indicadores.formulas.update', [$indicador, $formula]), [
                'formula_mode' => 'advanced',
                'expresion' => json_encode($updated, JSON_UNESCAPED_UNICODE),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $formula->refresh();
        $this->assertSame('avg', $formula->expresion['op'] ?? null);
        $this->assertSame(1, $indicador->formulas()->count());
    }
}
