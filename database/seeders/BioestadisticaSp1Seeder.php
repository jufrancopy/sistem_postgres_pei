<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP1 con todos los tipos de registro del dominio 1 (Ambulatorio),
 * cada uno como tabla de especialidades vía detalle_catalogo_items.
 */
class BioestadisticaSp1Seeder extends Seeder
{
    /** Orden canónico de bloques en el formulario. */
    private const DETALLE_ORDER = [
        'VAR_1_CONSULTA_POR_ESPECIALIDAD',
        'VAR_1_CONVENIO_CONSULTAS_MEDICAS',
        'VAR_1_INTERCONSULTAS',
        'VAR_1_TELECONSULTAS',
    ];

    /** Códigos de field estables (compat captura/indicadores). */
    private const FIELD_CODE_ALIASES = [
        'VAR_1_CONSULTA_POR_ESPECIALIDAD' => 'consultas_por_especialidad',
    ];

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP1')->first();
        if (! $formulario) {
            $this->command?->warn('SP1 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesAmbulatorio();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 1 sin tipos con especialidades; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Consultas médicas ambulatorias por especialidad. En consultas se puede informar IPS, Convenio y/o total por fila; además interconsultas y teleconsultas.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Ambulatorio'],
            [
                'descripcion' => 'Tipos de registro del dominio Ambulatorio. En consultas por especialidad: columnas IPS / Convenio / Total (como desglose opcional + total de fila).',
                'orden' => 1,
            ]
        );

        // Compat: sección antigua del seeder previo
        $old = $formulario->secciones()->where('titulo', 'Consultas por especialidad')->first();
        if ($old && $old->id !== $seccion->id) {
            foreach ($old->fields()->withTrashed()->get() as $field) {
                if ($field->code === 'consultas_por_especialidad') {
                    $field->seccion_id = $seccion->id;
                    $field->save();
                }
            }
            if ($old->fields()->count() === 0) {
                $old->delete();
            }
        }

        $orden = 1;
        $published = [];
        foreach ($detalles as $index => $detalle) {
            $slug = DictionaryCodes::slug($detalle);
            $code = self::FIELD_CODE_ALIASES[$slug] ?? DictionaryCodes::fieldCode($detalle);
            $this->upsertTable($seccion, $code, $detalle, $orden++, $index === 0);
            $published[] = "{$code} (".$detalle->catalogoItems()->where('activo', true)->count().' esp.)';
        }

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

        $this->command?->info('SP1 publicado con '.$detalles->count().' tablas: '.implode('; ', $published));
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesAmbulatorio(): Collection
    {
        $detalles = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '1')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0)
            ->values();

        $ordered = collect();
        foreach (self::DETALLE_ORDER as $slug) {
            $hit = $detalles->first(fn (VariableDetalle $d) => DictionaryCodes::slug($d) === $slug);
            if ($hit) {
                $ordered->push($hit);
            }
        }

        // Cualquier otro tipo del dominio 1 con ítems (por si el diccionario crece)
        foreach ($detalles as $detalle) {
            if ($ordered->contains(fn (VariableDetalle $d) => $d->id === $detalle->id)) {
                continue;
            }
            $ordered->push($detalle);
        }

        return $ordered->values();
    }

    private function upsertTable($seccion, string $code, VariableDetalle $detalle, int $orden, bool $required): void
    {
        $field = $seccion->fields()->withTrashed()->firstOrNew(['code' => $code]);
        if ($field->trashed()) {
            $field->restore();
        }

        $helpText = TablaIpsConvenioColumns::helpText();
        if ($code === 'var_1_convenio_consultas_medicas') {
            $helpText = 'Bloque opcional. Si el Convenio ya figura en la tabla de consultas (columna Convenio), no es necesario repetirlo aquí.';
        }

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => $helpText,
            'config' => TablaIpsConvenioColumns::config((int) $detalle->id, 'Especialidad', 'total_consultas'),
            'orden' => $orden,
        ])->save();
    }
}
