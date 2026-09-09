<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP6 con el tipo odontológico del dominio 14.
 * (El resto de procedimientos del dominio 14 van a SP7.)
 */
class BioestadisticaSp6Seeder extends Seeder
{
    private const ODONTO_SLUG = 'VAR_14_PROCEDIMIENTOS_ODONTOLOGICOS';

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP6')->first();
        if (! $formulario) {
            $this->command?->warn('SP6 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesOdontologia();
        if ($detalles->isEmpty()) {
            $this->command?->warn('No hay PROCEDIMIENTOS ODONTOLOGICOS en dominio 14; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Prestaciones odontológicas del período (dominio 14 — procedimientos odontológicos).',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Odontología'],
            [
                'descripcion' => 'Tipo de registro de procedimientos odontológicos con su lista del diccionario.',
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

        $this->command?->info('SP6 publicado con '.$detalles->count().' tabla(s) de odontología.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesOdontologia(): Collection
    {
        return VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '14')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0
                && DictionaryCodes::slug($detalle) === self::ODONTO_SLUG)
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
