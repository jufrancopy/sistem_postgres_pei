<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\Variable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BioestadisticaDictionaryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dictionary_keeps_variable_detalle_and_catalog_bridge(): void
    {
        if (! \Illuminate\Support\Facades\Schema::connection('pgsql')->hasTable('bioestadistica.variables')) {
            $this->markTestSkipped('Falta la migración del diccionario de variables.');
        }

        $suffix = 'TEST'.uniqid();
        $first = (new HealthVariableDictionary)->remember('z', "VAR {$suffix}", "Detalle {$suffix}", "Prestacion {$suffix}");
        $again = (new HealthVariableDictionary)->remember('z', "VAR {$suffix}", "Detalle {$suffix}", "Prestacion {$suffix}");
        $otherDomain = (new HealthVariableDictionary)->remember('z', "MED {$suffix}", 'Entrega', 'Amoxicilina '.$suffix);
        $otherX = (new HealthVariableDictionary)->remember('z', "ATN {$suffix}", 'Consulta', 'Control '.$suffix);

        $this->assertTrue($first['created']['variable']);
        $this->assertFalse($again['created']['variable']);
        $this->assertNotNull($first['catalog_item']);
        $this->assertNotNull($first['bridge']);
        $this->assertFalse($again['created']['bridge']);
        $this->assertNotSame($otherDomain['variable']->id, $otherX['variable']->id);
        $this->assertSame(1, Variable::where('codigo', 'z')->where('nombre', "VAR {$suffix}")->count());
        $this->assertSame(1, DetalleCatalogoItem::where('variable_detalle_id', $first['detalle']->id)->count());
    }

    public function test_geografia_labels_use_departamento_region(): void
    {
        $index = file_get_contents(resource_path('views/admin/bioestadistica/geografia/index.blade.php'));
        $form = file_get_contents(resource_path('views/admin/bioestadistica/geografia/_establecimiento-form.blade.php'));

        $this->assertStringContainsString('Departamento/región', $index);
        $this->assertStringContainsString('Departamento/región', $form);
        $this->assertStringContainsString('<label>Distrito *</label>', $form);
        $this->assertStringNotContainsString('orgánico', $index.$form);
        $this->assertStringNotContainsString('organico', $index.$form);
    }
}
