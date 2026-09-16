<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Dictionary\Sp7ProcedimientosDictionarySync;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP7 alineado a la planilla Formularios (grupos consolidados).
 */
class BioestadisticaSp7Seeder extends Seeder
{
    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP7')->first();
        if (! $formulario) {
            $this->command?->warn('SP7 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesSp7();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Sin tipos SP7 consolidados; ejecute BioestadisticaSp7ProcedimientosDictionarySeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Procedimientos SP7: anestesia, banco de sangre, cirugías, diálisis, fisioterapia, planificación familiar, salud mental/ocupacional, neoadyuvante, U.T.I. y otros.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->orderBy('orden')->orderBy('id')->first();
        if (! $seccion) {
            $seccion = $formulario->secciones()->create([
                'titulo' => 'Procedimientos',
                'descripcion' => 'Grupos de la planilla Formularios SP7.',
                'orden' => 1,
            ]);
        } else {
            $seccion->update([
                'titulo' => 'Procedimientos',
                'descripcion' => 'Grupos de la planilla Formularios SP7.',
                'orden' => 1,
            ]);
        }

        $formulario->secciones()
            ->where('id', '<>', $seccion->id)
            ->where('titulo', '<>', 'Observaciones')
            ->get()
            ->each(function ($extra) {
                $extra->fields()->withTrashed()->where('type', 'tabla')->get()->each->delete();
                if ($extra->fields()->withTrashed()->count() === 0) {
                    $extra->delete();
                }
            });

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

        $this->command?->info('SP7 publicado con '.$detalles->count().' tablas agrupadas.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesSp7(): Collection
    {
        $ordered = collect();
        foreach (Sp7ProcedimientosDictionarySync::SP7_TIPOS as $meta) {
            $detalle = VariableDetalle::query()
                ->where('activo', true)
                ->where('nombre', $meta['tipo'])
                ->whereHas('variable', fn ($q) => $q->where('codigo', $meta['codigo'])->where('activo', true))
                ->with('variable')
                ->withCount(['catalogoItems as catalogo_items_count' => fn ($q) => $q->where('activo', true)])
                ->first();
            if ($detalle && $detalle->catalogo_items_count > 0) {
                $ordered->push($detalle);
            }
        }

        return $ordered->values();
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
            'help_text' => TablaIpsConvenioColumns::helpText(),
            'config' => TablaIpsConvenioColumns::config((int) $detalle->id, 'Prestación'),
            'orden' => $orden,
        ])->save();
    }
}
