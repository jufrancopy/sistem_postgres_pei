<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP2 con todos los tipos de registro del dominio 13 (Servicios de enfermería).
 * La planilla impresa es ejemplo base (lista plana); el formulario del sistema usa
 * una tabla por tipo de registro con sus prestaciones del diccionario.
 */
class BioestadisticaSp2Seeder extends Seeder
{
    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP2')->first();
        if (! $formulario) {
            $this->command?->warn('SP2 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesEnfermeria();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 13 sin tipos con prestaciones; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Servicios de enfermería del período, agrupados por tipo de registro del dominio 13.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Servicios de enfermería'],
            [
                'descripcion' => 'Tipos de registro del dominio Servicios de enfermería. Cada bloque usa su lista de prestaciones del diccionario.',
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

        // Quitar tablas huérfanas de tipos que ya no existen / sin ítems
        $keepCodes = $detalles->map(fn (VariableDetalle $d) => DictionaryCodes::fieldCode($d))->all();
        $seccion->fields()
            ->where('type', 'tabla')
            ->whereNotIn('code', $keepCodes)
            ->each(function ($field) {
                $field->delete();
            });

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

        $this->command?->info('SP2 publicado con '.$detalles->count().' tablas / tipos de enfermería.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesEnfermeria(): Collection
    {
        return VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '13')->where('activo', true))
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
                    [
                        'code' => 'total',
                        'label' => 'Total',
                        'type' => 'integer',
                        'min' => 0,
                    ],
                ],
            ],
            'orden' => $orden,
        ])->save();
    }
}
