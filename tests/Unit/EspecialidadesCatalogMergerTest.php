<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\EspecialidadesCatalogMerger;
use App\Models\Bioestadistica\EspecialidadMedica;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EspecialidadesCatalogMergerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_psiquiatria_keeps_name_and_receives_codigo(): void
    {
        $psi = EspecialidadMedica::query()->where('nombre_normalizado', 'psiquiatria')->first()
            ?? EspecialidadMedica::query()->create([
                'nombre' => 'PSIQUIATRIA',
                'nombre_normalizado' => 'psiquiatria',
                'activo' => true,
                'orden' => 0,
            ]);
        $psi->codigo = null;
        $psi->save();

        $siq = EspecialidadMedica::query()->firstOrCreate(
            ['nombre' => 'SIQUIATRIA'],
            ['nombre_normalizado' => 'siquiatria', 'activo' => true, 'orden' => 0]
        );
        $siq->codigo = null;
        $siq->save();

        (new EspecialidadesCatalogMerger)->applyAgreedAliases();

        $psi->refresh();
        $this->assertSame('7', $psi->codigo);
        $this->assertNull(EspecialidadMedica::query()->where('nombre', 'SIQUIATRIA')->first());
    }

    public function test_anestesioloia_merges_into_anestesiologia(): void
    {
        $ok = EspecialidadMedica::query()->where('nombre_normalizado', 'anestesiologia')->first()
            ?? EspecialidadMedica::query()->create([
                'nombre' => 'ANESTESIOLOGIA',
                'nombre_normalizado' => 'anestesiologia',
                'codigo' => '51',
                'activo' => true,
                'orden' => 51,
            ]);
        $ok->codigo = '51';
        $ok->save();

        EspecialidadMedica::query()->firstOrCreate(
            ['nombre' => 'ANESTESIOLOIA'],
            ['nombre_normalizado' => 'anestesioloia', 'activo' => true, 'orden' => 0]
        );

        (new EspecialidadesCatalogMerger)->applyAgreedAliases();

        $this->assertNull(EspecialidadMedica::query()->where('nombre', 'ANESTESIOLOIA')->first());
        $ok->refresh();
        $this->assertSame('ANESTESIOLOGIA', $ok->nombre);
        $this->assertSame('51', $ok->codigo);
    }

    public function test_psicologia_pediatrica_merges_into_ninos_and_otras_is_removed(): void
    {
        $ninos = EspecialidadMedica::query()->where('nombre_normalizado', 'psicologia ninos')->first()
            ?? EspecialidadMedica::query()->create([
                'nombre' => 'PSICOLOGIA NIÑOS',
                'nombre_normalizado' => 'psicologia ninos',
                'codigo' => '38',
                'activo' => true,
                'orden' => 38,
            ]);
        $ninos->codigo = '38';
        $ninos->save();

        EspecialidadMedica::query()->firstOrCreate(
            ['nombre' => 'PSICOLOGIA PEDIATRICA'],
            ['nombre_normalizado' => 'psicologia pediatrica', 'activo' => true, 'orden' => 0]
        );

        EspecialidadMedica::query()->firstOrCreate(
            ['nombre' => 'OTRAS ESPECIALIDADES'],
            ['nombre_normalizado' => 'otras especialidades', 'activo' => true, 'orden' => 0]
        );

        $psico = EspecialidadMedica::query()->where('nombre_normalizado', 'psicologia')->first()
            ?? EspecialidadMedica::query()->create([
                'nombre' => 'PSICOLOGIA',
                'nombre_normalizado' => 'psicologia',
                'codigo' => '70',
                'activo' => true,
                'orden' => 70,
            ]);
        $psico->codigo = '70';
        $psico->save();

        (new EspecialidadesCatalogMerger)->applyFollowUpAliases();

        $this->assertNull(EspecialidadMedica::query()->where('nombre', 'PSICOLOGIA PEDIATRICA')->first());
        $ninos->refresh();
        $this->assertSame('38', $ninos->codigo);
        $psico->refresh();
        $this->assertSame('70', $psico->codigo);
        $this->assertNull(EspecialidadMedica::query()->where('nombre', 'OTRAS ESPECIALIDADES')->first());
    }
}
