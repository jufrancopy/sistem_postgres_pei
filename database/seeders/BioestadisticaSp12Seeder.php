<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP12 alineado a la planilla Formularios (VIH, transmisión vertical, TB, ENT).
 */
class BioestadisticaSp12Seeder extends Seeder
{
    private const DETALLE_ORDER = [
        'VAR_17_PROGRAMA_DE_VIH_SIDA',
        'VAR_17_PREVENCION_DE_LA_TRANSMISION_VERTICAL',
        'VAR_17_PROGRAMA_DE_TUBERCULOSIS',
        'VAR_17_ENFERMEDADES_NO_TRANSMISIBLES',
    ];

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP12')->first();
        if (! $formulario) {
            $this->command?->warn('SP12 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesSp12();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 17 sin tipos SP12; ejecute BioestadisticaSp12ProgramasDictionarySeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Programas SP12: VIH/Sida, transmisión vertical, tuberculosis y enfermedades no transmisibles.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()
            ->where('titulo', 'VIH, tuberculosis y ENT')
            ->orderBy('orden')
            ->orderBy('id')
            ->first();
        if (! $seccion) {
            $seccion = $formulario->secciones()->orderBy('orden')->orderBy('id')->first();
        }
        if (! $seccion) {
            $seccion = $formulario->secciones()->create([
                'titulo' => 'VIH, tuberculosis y ENT',
                'descripcion' => 'Cuatro bloques de la planilla Formularios SP12.',
                'orden' => 1,
            ]);
        } else {
            $seccion->update([
                'titulo' => 'VIH, tuberculosis y ENT',
                'descripcion' => 'Cuatro bloques de la planilla Formularios SP12.',
                'orden' => 1,
            ]);
        }

        // Evita cabeceras duplicadas (secciones vacías o residuales del mismo título).
        $formulario->secciones()
            ->where('id', '<>', $seccion->id)
            ->where('titulo', '<>', 'Observaciones')
            ->get()
            ->each(function ($extra) {
                $extra->fields()->withTrashed()->get()->each->delete();
                $extra->delete();
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

        $this->command?->info('SP12 publicado con '.$detalles->count().' tablas.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesSp12(): Collection
    {
        $allowed = self::DETALLE_ORDER;

        $detalles = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '17')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(function (VariableDetalle $detalle) use ($allowed) {
                return $detalle->catalogo_items_count > 0
                    && in_array(DictionaryCodes::slug($detalle), $allowed, true);
            })
            ->values();

        $ordered = collect();
        foreach ($allowed as $slug) {
            $hit = $detalles->first(fn (VariableDetalle $d) => DictionaryCodes::slug($d) === $slug);
            if ($hit) {
                $ordered->push($hit);
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
