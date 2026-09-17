<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP14 (Medicamentos e insumos) desde dominio x — MEDICAMENTOS_PRESCRIPTOS.
 * Si hay más tipos del dominio x con ítems relacionados a medicamentos, se incluyen.
 */
class BioestadisticaSp14Seeder extends Seeder
{
    private const PREFERRED_SLUGS = [
        'VAR_X_MEDICAMENTOS_PRESCRIPTOS',
    ];

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP14')->first();
        if (! $formulario) {
            $this->command?->warn('SP14 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesSp14();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio x sin MEDICAMENTOS_PRESCRIPTOS; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Medicamentos prescritos e insumos entregados en el período.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Medicamentos e insumos'],
            [
                'descripcion' => 'Tipos de registro de medicamentos/insumos del dominio x.',
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

        $this->command?->info('SP14 publicado con '.$detalles->count().' tabla(s) de medicamentos/insumos.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesSp14(): Collection
    {
        $detalles = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', 'x')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0)
            ->values();

        // Prefer exact medicamentos; if missing, fall back to any x detalle with "MEDICAMENT"
        $ordered = collect();
        foreach (self::PREFERRED_SLUGS as $slug) {
            $hit = $detalles->first(fn (VariableDetalle $d) => DictionaryCodes::slug($d) === $slug);
            if ($hit) {
                $ordered->push($hit);
            }
        }

        if ($ordered->isEmpty()) {
            $ordered = $detalles->filter(function (VariableDetalle $d) {
                $slug = DictionaryCodes::slug($d);

                return str_contains($slug, 'MEDICAMENT') || str_contains($slug, 'INSUMO');
            })->values();
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
