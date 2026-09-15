<?php

namespace App\Application\Bioestadistica\Sync;

use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Exporta/importa el diccionario canónico (variables → detalles → puentes)
 * sin tocar record_values ni cargas SP.
 *
 * Sirve para igualar producción con desarrollo cuando el diccionario
 * se armó desde varias planillas (variables salud, Formularios SP, etc.).
 */
class DictionarySnapshotService
{
    public function __construct(
        private HealthVariableDictionary $dictionary = new HealthVariableDictionary
    ) {}

    /**
     * @return array{
     *     version: int,
     *     exported_at: string,
     *     variables: list<array{
     *         codigo: string,
     *         nombre: string,
     *         activo: bool,
     *         detalles: list<array{
     *             nombre: string,
     *             activo: bool,
     *             catalogo_tipo: ?string,
     *             layout_captura: ?string,
     *             items: list<array{nombre: string, catalogo_tipo: string, orden: int, activo: bool}>
     *         }>
     *     }>
     * }
     */
    public function export(): array
    {
        $variables = Variable::query()
            ->with(['detalles' => fn ($q) => $q->orderBy('orden')->orderBy('nombre')])
            ->orderByRaw("CASE WHEN codigo ~ '^[0-9]+$' THEN CAST(codigo AS integer) ELSE 999 END")
            ->orderBy('nombre')
            ->get();

        $payload = [
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'variables' => [],
        ];

        foreach ($variables as $variable) {
            $detalles = [];
            foreach ($variable->detalles as $detalle) {
                $items = [];
                $bridges = DetalleCatalogoItem::query()
                    ->where('variable_detalle_id', $detalle->id)
                    ->where('activo', true)
                    ->orderBy('orden')
                    ->get();

                foreach ($bridges as $bridge) {
                    $catalog = $bridge->resolveCatalogItem();
                    $nombre = $catalog?->nombre ?? null;
                    if ($nombre === null || trim((string) $nombre) === '') {
                        continue;
                    }
                    $items[] = [
                        'nombre' => (string) $nombre,
                        'catalogo_tipo' => (string) $bridge->catalogo_tipo,
                        'orden' => (int) ($bridge->orden ?? 0),
                        'activo' => (bool) $bridge->activo,
                    ];
                }

                $detalles[] = [
                    'nombre' => (string) $detalle->nombre,
                    'activo' => (bool) $detalle->activo,
                    'catalogo_tipo' => $detalle->catalogo_tipo,
                    'layout_captura' => $detalle->layout_captura,
                    'items' => $items,
                ];
            }

            $payload['variables'][] = [
                'codigo' => (string) $variable->codigo,
                'nombre' => (string) $variable->nombre,
                'activo' => (bool) $variable->activo,
                'detalles' => $detalles,
            ];
        }

        return $payload;
    }

    public function exportToFile(string $path): array
    {
        $payload = $this->export();
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        return $payload;
    }

    /**
     * Upsert desde snapshot. No borra record_values.
     * Si $pruneExtraX, soft-delete variables dominio x que no estén en el snapshot.
     *
     * @return array{variables: int, detalles: int, items: int, pruned: list<string>, skipped: list<string>}
     */
    public function importFromFile(string $path, bool $pruneExtraX = true): array
    {
        if (! is_file($path)) {
            throw new \InvalidArgumentException("No existe el snapshot: {$path}");
        }

        $payload = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($payload) || ! isset($payload['variables']) || ! is_array($payload['variables'])) {
            throw new \InvalidArgumentException('Snapshot inválido: falta variables[].');
        }

        return $this->import($payload, $pruneExtraX);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{variables: int, detalles: int, items: int, pruned: list<string>, skipped: list<string>}
     */
    public function import(array $payload, bool $pruneExtraX = true): array
    {
        $variables = 0;
        $detalles = 0;
        $items = 0;
        $keepXNames = [];

        foreach ($payload['variables'] as $variableRow) {
            $codigo = trim((string) ($variableRow['codigo'] ?? ''));
            $nombreVar = trim((string) ($variableRow['nombre'] ?? ''));
            if ($codigo === '' || $nombreVar === '') {
                continue;
            }
            if (strcasecmp($codigo, 'x') === 0) {
                $keepXNames[] = Str::upper($nombreVar);
            }

            $variables++;
            foreach ($variableRow['detalles'] ?? [] as $detalleRow) {
                $nombreDetalle = trim((string) ($detalleRow['nombre'] ?? ''));
                if ($nombreDetalle === '') {
                    continue;
                }
                $detalles++;

                $itemRows = $detalleRow['items'] ?? [];
                if ($itemRows === []) {
                    // Detalle sin ítems: asegurar existencia vía remember con placeholder omitido —
                    // creamos/actualizamos el detalle directamente.
                    $this->ensureDetalle($codigo, $nombreVar, $nombreDetalle, $detalleRow);
                    continue;
                }

                foreach ($itemRows as $item) {
                    $nombreItem = trim((string) ($item['nombre'] ?? ''));
                    if ($nombreItem === '') {
                        continue;
                    }
                    $this->dictionary->remember(
                        $codigo,
                        $nombreVar,
                        $nombreDetalle,
                        $nombreItem,
                        (int) ($item['orden'] ?? 0)
                    );
                    $items++;
                }

                $this->applyDetalleMeta($codigo, $nombreVar, $nombreDetalle, $detalleRow);
            }
        }

        $pruned = [];
        $skipped = [];
        if ($pruneExtraX) {
            [$pruned, $skipped] = $this->pruneExtraX($keepXNames);
        }

        return compact('variables', 'detalles', 'items', 'pruned', 'skipped');
    }

    /**
     * @param  array<string, mixed>  $detalleRow
     */
    private function ensureDetalle(string $codigo, string $nombreVar, string $nombreDetalle, array $detalleRow): void
    {
        $variable = Variable::withTrashed()->firstOrNew([
            'codigo' => $codigo,
            'nombre' => $nombreVar,
        ]);
        if ($variable->trashed()) {
            $variable->restore();
        }
        $variable->fill(['activo' => (bool) ($detalleRow['activo'] ?? true)])->save();
        // Re-activate variable flag from parent row if needed
        if (! $variable->activo) {
            $variable->update(['activo' => true]);
        }

        $detalle = VariableDetalle::withTrashed()->firstOrNew([
            'variable_id' => $variable->id,
            'nombre' => $nombreDetalle,
        ]);
        if ($detalle->trashed()) {
            $detalle->restore();
        }
        $detalle->fill([
            'activo' => (bool) ($detalleRow['activo'] ?? true),
            'catalogo_tipo' => $detalleRow['catalogo_tipo'] ?? $detalle->catalogo_tipo,
            'layout_captura' => $detalleRow['layout_captura'] ?? $detalle->layout_captura,
        ])->save();
    }

    /**
     * @param  array<string, mixed>  $detalleRow
     */
    private function applyDetalleMeta(string $codigo, string $nombreVar, string $nombreDetalle, array $detalleRow): void
    {
        $variable = Variable::query()->where('codigo', $codigo)->where('nombre', $nombreVar)->first();
        if (! $variable) {
            return;
        }
        $detalle = VariableDetalle::query()
            ->where('variable_id', $variable->id)
            ->where('nombre', $nombreDetalle)
            ->first();
        if (! $detalle) {
            return;
        }

        $fill = ['activo' => (bool) ($detalleRow['activo'] ?? true)];
        if (array_key_exists('catalogo_tipo', $detalleRow)) {
            $fill['catalogo_tipo'] = $detalleRow['catalogo_tipo'];
        }
        if (array_key_exists('layout_captura', $detalleRow)) {
            $fill['layout_captura'] = $detalleRow['layout_captura'];
        }
        $detalle->fill($fill)->save();
    }

    /**
     * @param  list<string>  $keepXNames  uppercased
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneExtraX(array $keepXNames): array
    {
        $pruned = [];
        $skipped = [];
        $keepXNames = array_values(array_unique($keepXNames));

        $orphans = Variable::query()
            ->where('codigo', 'x')
            ->get()
            ->filter(fn (Variable $v) => ! in_array(Str::upper(trim((string) $v->nombre)), $keepXNames, true));

        foreach ($orphans as $variable) {
            $label = $variable->codigo.' — '.$variable->nombre.' #'.$variable->id;
            foreach ($variable->detalles as $detalle) {
                $detalleLabel = $label.' / '.$detalle->nombre;
                if (Field::withTrashed()->where('detalle_id', $detalle->id)->exists()) {
                    if ($detalle->activo) {
                        $detalle->update(['activo' => false]);
                    }
                    $skipped[] = "detalle x en uso (desactivado): {$detalleLabel}";
                    continue;
                }
                DetalleCatalogoItem::query()
                    ->where('variable_detalle_id', $detalle->id)
                    ->get()
                    ->each->delete();
                $detalle->delete();
                $pruned[] = "detalle x: {$detalleLabel}";
            }
            $variable->refresh();
            if ($variable->detalles()->count() > 0) {
                if ($variable->activo) {
                    $variable->update(['activo' => false]);
                }
                $skipped[] = "variable x retenida (desactivada): {$label}";
                continue;
            }
            $variable->delete();
            $pruned[] = "variable x: {$label}";
        }

        return [$pruned, $skipped];
    }
}
