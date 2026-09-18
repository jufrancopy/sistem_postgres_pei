<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Adapta SP4 a columnas IPS / Convenio / Tercerizado / Total (como SP1/SP2/SP7).
 *
 * - No toca diccionario/variables ni record_values.
 * - En campos ya existentes solo actualiza config métrica + help_text
 *   (conserva label, required, orden, detalle_id).
 * - No poda campos custom ni exporta snapshot.
 */
class BioestadisticaSp4Seeder extends Seeder
{
    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP4')->first();
        if (! $formulario) {
            $this->command?->warn('SP4 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesAltaComplejidad();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 11 sin tipos con ítems; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        if (blank($formulario->descripcion)) {
            $formulario->update([
                'descripcion' => 'Estudios de alta complejidad por tipo de registro. Columnas según prestador (IPS / Convenio / Tercerizado).',
            ]);
        }
        if ($formulario->estado !== 'activo') {
            $formulario->update(['estado' => 'activo']);
        }

        $seccion = $formulario->secciones()->firstOrCreate(
            ['titulo' => 'Estudios de alta complejidad'],
            [
                'descripcion' => 'Tipos de registro del dominio Estudios de alta complejidad. Cada bloque usa su lista del diccionario.',
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

        $observaciones = $formulario->secciones()->firstOrCreate(
            ['titulo' => 'Observaciones'],
            [
                'descripcion' => 'Comentarios del establecimiento sobre el período informado.',
                'orden' => 2,
            ]
        );

        $obsField = $observaciones->fields()->withTrashed()->firstOrNew(['code' => 'observaciones']);
        if (! $obsField->exists) {
            $obsField->fill([
                'label' => 'Observaciones de la planilla',
                'type' => 'textarea',
                'required' => false,
                'orden' => 1,
            ])->save();
        } elseif ($obsField->trashed()) {
            $obsField->restore();
        }

        $this->command?->info('SP4: métricas IPS/Convenio/Tercerizado/Total en '.$detalles->count().' tablas (sin tocar diccionario ni valores).');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesAltaComplejidad(): Collection
    {
        return VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '11')->where('activo', true))
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

        $metric = TablaIpsConvenioColumns::config(
            (int) ($field->detalle_id ?: $detalle->id),
            is_array($field->config) && ! empty($field->config['row_label'])
                ? (string) $field->config['row_label']
                : 'Prestación'
        );

        if ($field->exists) {
            $existing = is_array($field->config) ? $field->config : [];
            $config = array_merge($existing, [
                'totals' => true,
                'metric_by_prestador' => true,
                'columns' => $metric['columns'],
                'row_total' => $metric['row_total'],
                'row_source' => $existing['row_source'] ?? 'detalle_catalogo',
                'row_detalle_id' => $existing['row_detalle_id'] ?? (int) $detalle->id,
                'row_label' => $existing['row_label'] ?? 'Prestación',
            ]);
            unset($config['metric_bases'], $config['row_totals']);

            $field->config = $config;
            $field->help_text = TablaIpsConvenioColumns::helpText();
            $field->save();

            return;
        }

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => TablaIpsConvenioColumns::helpText(),
            'config' => $metric,
            'orden' => $orden,
        ])->save();
    }
}
