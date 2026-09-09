<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Dictionary\EspecialidadesCatalogNormalizer;
use App\Application\Bioestadistica\Dictionary\CatalogType;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EspecialidadesCatalogNormalizerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_consolidates_same_codigo_across_context_rows_when_column_exists(): void
    {
        if (! Schema::connection('pgsql')->hasColumn('bioestadistica.especialidades_medicas', 'contexto')) {
            $this->markTestSkipped('contexto ya eliminado; se valida con migración.');
        }

        $a = EspecialidadMedica::query()->create([
            'codigo' => '9123',
            'nombre' => 'ESP NORMALIZE A',
            'nombre_normalizado' => 'esp normalize a',
            'contexto' => 'ambulatorio',
            'especialidad_base' => 'ESP NORMALIZE A',
            'orden' => 9123,
            'activo' => true,
        ]);
        $b = EspecialidadMedica::query()->create([
            'codigo' => '9123',
            'nombre' => 'ESP NORMALIZE A',
            'nombre_normalizado' => 'esp normalize a',
            'contexto' => 'convenio',
            'especialidad_base' => 'ESP NORMALIZE A',
            'orden' => 9123,
            'activo' => true,
        ]);

        $variable = Variable::query()->firstOrCreate(['codigo' => '1', 'nombre' => 'AMBULATORIO'], ['activo' => true]);
        $detalle = VariableDetalle::query()->firstOrCreate(
            ['variable_id' => $variable->id, 'nombre' => 'NORMALIZE TEST TIPO'],
            ['activo' => true, 'catalogo_tipo' => CatalogType::EspecialidadMedica->value]
        );
        DetalleCatalogoItem::query()->create([
            'variable_detalle_id' => $detalle->id,
            'catalogo_tipo' => CatalogType::EspecialidadMedica->value,
            'catalogo_item_id' => $b->id,
            'orden' => 1,
            'activo' => true,
        ]);

        $summary = (new EspecialidadesCatalogNormalizer)->normalize();

        $this->assertNull(EspecialidadMedica::query()->find($b->id));
        $this->assertNotNull(EspecialidadMedica::query()->find($a->id));
        $this->assertTrue(
            DetalleCatalogoItem::query()
                ->where('variable_detalle_id', $detalle->id)
                ->where('catalogo_item_id', $a->id)
                ->exists()
        );
        $this->assertGreaterThanOrEqual(1, $summary['merged']);
    }
}
