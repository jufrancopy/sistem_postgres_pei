<?php

namespace Tests\Feature;

use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Variable;
use Database\Seeders\BioestadisticaFormulariosSpSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BioestadisticaFormulariosSpSeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_remaining_sp_forms_are_published_with_dictionary_tables(): void
    {
        if (! Variable::where('codigo', '13')->exists()) {
            $this->markTestSkipped('Falta el diccionario de variables para configurar los formularios SP.');
        }

        $this->seed(BioestadisticaFormulariosSpSeeder::class);

        $codes = ['SP2', 'SP3', 'SP4', 'SP5', 'SP6', 'SP7', 'SP8', 'SP9', 'SP12', 'SP13', 'SP14'];
        foreach ($codes as $code) {
            $formulario = Formulario::where('codigo', $code)->with('secciones.fields.detalle')->first();
            $this->assertNotNull($formulario, "Falta el formulario {$code}.");
            $this->assertSame('activo', $formulario->estado, "{$code} debe publicarse.");
            $tables = $formulario->secciones->flatMap->fields->where('type', 'tabla');
            $this->assertGreaterThan(0, $tables->count(), "{$code} debe tener al menos una tabla de captura.");
            $this->assertTrue(
                $tables->every(fn ($field) => $field->detalle_id && ($field->config['columns'] ?? []) !== []),
                "{$code} debe enlazar un detalle del diccionario y columnas métricas."
            );
        }

        $sp8 = Formulario::where('codigo', 'SP8')->with('secciones.fields')->first();
        $vaccineField = $sp8?->secciones->flatMap->fields->firstWhere('type', 'tabla');
        $this->assertNotNull($vaccineField);
        $columnCodes = collect($vaccineField->config['columns'] ?? [])->pluck('code')->all();
        $this->assertSame('total', $columnCodes[0] ?? null);
        $this->assertGreaterThan(2, count($columnCodes));
        $this->assertSame('total', $vaccineField->config['row_total']['code'] ?? null);

        $sp9 = Formulario::where('codigo', 'SP9')->with('secciones.fields')->first();
        $tables = $sp9?->secciones->flatMap->fields->where('type', 'tabla') ?? collect();
        $this->assertGreaterThanOrEqual(3, $tables->count());
        foreach ($tables as $table) {
            $columnCodes = collect($table->config['columns'] ?? [])->pluck('code')->all();
            $this->assertSame(
                ['consultas', 'observacion', 'procedimiento', 'total'],
                $columnCodes,
                "La tabla {$table->code} debe tener consultas, observación, procedimiento y total."
            );
            $this->assertSame('total', $table->config['row_total']['code'] ?? null);
            $this->assertSame(
                ['consultas', 'observacion', 'procedimiento'],
                $table->config['row_total']['sum_columns'] ?? null
            );
        }
    }
}
