<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP5 con todos los tipos del dominio 10 (Laboratorio / análisis clínicos).
 */
class BioestadisticaSp5Seeder extends Seeder
{
    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP5')->first();
        if (! $formulario) {
            $this->command?->warn('SP5 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesLaboratorio();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 10 sin tipos con ítems; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Determinaciones de laboratorio del período, por tipo de registro.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Análisis clínicos'],
            [
                'descripcion' => 'Tipos de registro del dominio Laboratorio. Cada bloque usa su lista del diccionario.',
                'orden' => 1,
            ]
        );

        $orden = 1;
        $published = [];
        foreach ($detalles as $index => $detalle) {
            $code = DictionaryCodes::fieldCode($detalle);
            $this->upsertTable($seccion, $code, $detalle, $orden++, $index === 0);
            $published[] = $detalle->nombre.' ('.$detalle->catalogo_items_count.')';
        }

        $keepCodes = $detalles->map(fn (VariableDetalle $d) => DictionaryCodes::fieldCode($d))->all();
        $seccion->fields()
            ->where('type', 'tabla')
            ->whereNotIn('code', $keepCodes)
            ->each(fn ($field) => $field->delete());

        $observaciones = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Observaciones'],
            [
                'descripcion' => 'Comentarios del establecimiento sobre el período informado.',
                'orden' => 2,
            ]
        );

        $obsField = $observaciones->fields()->withTrashed()->firstOrNew(['code' => 'observaciones']);
        if ($obsField->trashed()) {
            $obsField->restore();
        }
        $obsField->fill([
            'label' => 'Observaciones de la planilla',
            'type' => 'textarea',
            'required' => false,
            'orden' => 1,
        ])->save();

        $this->command?->info('SP5 publicado con '.$detalles->count().' tablas / tipos de laboratorio.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesLaboratorio(): Collection
    {
        return VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '10')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0)
            ->sortBy(fn (VariableDetalle $detalle) => DictionaryCodes::slug($detalle))
            ->values();
    }

    private function upsertTable($seccion, string $code, VariableDetalle $detalle, int $orden, bool $required): void
    {
        $field = $seccion->fields()->withTrashed()->firstOrNew(['code' => $code]);
        if ($field->trashed()) {
            $field->restore();
        }

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => 'Las prestaciones sin actividad pueden quedar vacías.',
            'config' => [
                'row_source' => 'detalle_catalogo',
                'row_detalle_id' => $detalle->id,
                'row_label' => 'Prestación',
                'totals' => true,
                'columns' => [
                    ['code' => 'total', 'label' => 'Total', 'type' => 'integer', 'min' => 0],
                ],
            ],
            'orden' => $orden,
        ])->save();
    }
}
