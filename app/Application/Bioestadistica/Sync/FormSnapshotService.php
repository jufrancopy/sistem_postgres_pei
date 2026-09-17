<?php

namespace App\Application\Bioestadistica\Sync;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\FormSeccion;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Facades\File;

/**
 * Exporta/importa la estructura de formularios SP (secciones + fields + config)
 * resolviendo detalle por dominio+nombre (no por id numérico).
 * No toca record_values.
 */
class FormSnapshotService
{
    public const DEFAULT_RELATIVE_PATH = 'app/bioestadistica/forms-snapshot.json';

    public static function defaultPath(): string
    {
        return storage_path(self::DEFAULT_RELATIVE_PATH);
    }

    /**
     * Ruta versionada en el repo (para el seeder de producción).
     */
    public static function seedDataPath(): string
    {
        return database_path('data/bioestadistica/forms-snapshot.json');
    }

    /**
     * @return array<string, mixed>
     */
    public function export(): array
    {
        $formularios = Formulario::query()
            ->ordenSp()
            ->with(['secciones.fields' => fn ($q) => $q->withTrashed()->orderBy('orden')->orderBy('id')])
            ->get();

        $payload = [
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'formularios' => [],
        ];

        foreach ($formularios as $formulario) {
            $secciones = [];
            foreach ($formulario->secciones as $seccion) {
                $fields = [];
                foreach ($seccion->fields as $field) {
                    if ($field->trashed()) {
                        continue;
                    }
                    $fields[] = $this->exportField($field, $seccion->fields);
                }
                $secciones[] = [
                    'titulo' => (string) $seccion->titulo,
                    'descripcion' => $seccion->descripcion,
                    'orden' => (int) ($seccion->orden ?? 0),
                    'fields' => $fields,
                ];
            }

            $payload['formularios'][] = [
                'codigo' => (string) $formulario->codigo,
                'nombre' => (string) $formulario->nombre,
                'descripcion' => $formulario->descripcion,
                'estado' => (string) ($formulario->estado ?? 'activo'),
                'periodicidad' => $formulario->periodicidad ?? null,
                'layout_type' => $formulario->layout_type ?? null,
                'secciones' => $secciones,
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
     * @return array{
     *     formularios: int,
     *     secciones: int,
     *     fields: int,
     *     pruned_fields: int,
     *     skipped: list<string>
     * }
     */
    public function importFromFile(string $path, bool $pruneExtraFields = true): array
    {
        if (! is_file($path)) {
            throw new \InvalidArgumentException("No existe el snapshot de formularios: {$path}");
        }

        $payload = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($payload) || ! isset($payload['formularios']) || ! is_array($payload['formularios'])) {
            throw new \InvalidArgumentException('Snapshot inválido: falta formularios[].');
        }

        return $this->import($payload, $pruneExtraFields);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{
     *     formularios: int,
     *     secciones: int,
     *     fields: int,
     *     pruned_fields: int,
     *     skipped: list<string>
     * }
     */
    public function import(array $payload, bool $pruneExtraFields = true): array
    {
        $formularios = 0;
        $secciones = 0;
        $fields = 0;
        $prunedFields = 0;
        $skipped = [];

        foreach ($payload['formularios'] as $formRow) {
            $codigo = trim((string) ($formRow['codigo'] ?? ''));
            if ($codigo === '') {
                continue;
            }

            $formulario = Formulario::withTrashed()->firstOrNew(['codigo' => $codigo]);
            if ($formulario->trashed()) {
                $formulario->restore();
            }
            $formulario->fill([
                'nombre' => (string) ($formRow['nombre'] ?? $formulario->nombre ?? $codigo),
                'descripcion' => $formRow['descripcion'] ?? $formulario->descripcion,
                'estado' => (string) ($formRow['estado'] ?? 'activo'),
                'periodicidad' => $formRow['periodicidad'] ?? $formulario->periodicidad,
                'layout_type' => $formRow['layout_type'] ?? $formRow['layout'] ?? $formulario->layout_type,
            ])->save();
            $formularios++;

            $keepFieldIds = [];
            $pendingParents = [];

            foreach ($formRow['secciones'] ?? [] as $secRow) {
                $titulo = trim((string) ($secRow['titulo'] ?? ''));
                if ($titulo === '') {
                    continue;
                }

                $seccion = $formulario->secciones()->withTrashed()->firstOrNew(['titulo' => $titulo]);
                if (method_exists($seccion, 'trashed') && $seccion->trashed()) {
                    $seccion->restore();
                }
                $seccion->fill([
                    'descripcion' => $secRow['descripcion'] ?? null,
                    'orden' => (int) ($secRow['orden'] ?? 0),
                ])->save();
                $secciones++;

                foreach ($secRow['fields'] ?? [] as $fieldRow) {
                    $code = trim((string) ($fieldRow['code'] ?? ''));
                    if ($code === '') {
                        continue;
                    }

                    $detalle = $this->resolveDetalle($fieldRow['detalle'] ?? null);
                    if (($fieldRow['detalle']['variable_codigo'] ?? null) && ! $detalle) {
                        $skipped[] = "{$codigo}/{$code}: detalle no encontrado "
                            .($fieldRow['detalle']['variable_codigo'] ?? '?')
                            .' / '
                            .($fieldRow['detalle']['nombre'] ?? '?');
                    }

                    $config = is_array($fieldRow['config'] ?? null) ? $fieldRow['config'] : [];
                    if ($detalle) {
                        $config = $this->remapConfigDetalleIds($config, $detalle);
                    }

                    $field = Field::withTrashed()
                        ->where('seccion_id', $seccion->id)
                        ->where('code', $code)
                        ->first();
                    if (! $field) {
                        $field = Field::withTrashed()->firstOrNew([
                            'seccion_id' => $seccion->id,
                            'code' => $code,
                        ]);
                    }
                    if ($field->trashed()) {
                        $field->restore();
                    }

                    $field->fill([
                        'seccion_id' => $seccion->id,
                        'code' => $code,
                        'label' => (string) ($fieldRow['label'] ?? $code),
                        'type' => (string) ($fieldRow['type'] ?? 'text'),
                        'required' => (bool) ($fieldRow['required'] ?? false),
                        'min_value' => $fieldRow['min_value'] ?? null,
                        'max_value' => $fieldRow['max_value'] ?? null,
                        'validation_regex' => $fieldRow['validation_regex'] ?? null,
                        'tooltip' => $fieldRow['tooltip'] ?? null,
                        'help_text' => $fieldRow['help_text'] ?? null,
                        'config' => $config,
                        'orden' => (int) ($fieldRow['orden'] ?? 0),
                        'detalle_id' => $detalle?->id,
                    ])->save();

                    $fields++;
                    $keepFieldIds[] = $field->id;

                    $parentCode = trim((string) ($fieldRow['parent_code'] ?? ''));
                    if ($parentCode !== '') {
                        $pendingParents[] = [$field->id, $seccion->id, $parentCode];
                    } elseif ($field->parent_field_id) {
                        $field->forceFill(['parent_field_id' => null])->save();
                    }
                }
            }

            foreach ($pendingParents as [$fieldId, $seccionId, $parentCode]) {
                $parent = Field::query()
                    ->where('seccion_id', $seccionId)
                    ->where('code', $parentCode)
                    ->first();
                if ($parent) {
                    Field::whereKey($fieldId)->update(['parent_field_id' => $parent->id]);
                } else {
                    $skipped[] = "{$codigo}: parent «{$parentCode}» no encontrado";
                }
            }

            if ($pruneExtraFields) {
                $prunedFields += $this->pruneFormFields($formulario, $keepFieldIds);
            }
        }

        return [
            'formularios' => $formularios,
            'secciones' => $secciones,
            'fields' => $fields,
            'pruned_fields' => $prunedFields,
            'skipped' => $skipped,
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Field>  $siblings
     * @return array<string, mixed>
     */
    private function exportField(Field $field, $siblings): array
    {
        $detalleRef = null;
        if ($field->detalle_id) {
            $detalle = VariableDetalle::withTrashed()->with('variable')->find($field->detalle_id);
            if ($detalle?->variable) {
                $detalleRef = [
                    'variable_codigo' => (string) $detalle->variable->codigo,
                    'nombre' => (string) $detalle->nombre,
                    'slug' => DictionaryCodes::slug($detalle),
                ];
            }
        }

        $parentCode = null;
        if ($field->parent_field_id) {
            $parentCode = optional($siblings->firstWhere('id', $field->parent_field_id))->code;
        }

        $config = is_array($field->config) ? $field->config : [];
        // No persistir ids locales en el snapshot; se remapean al importar.
        if (isset($config['row_detalle_id'])) {
            unset($config['row_detalle_id']);
        }

        return [
            'code' => (string) $field->code,
            'label' => (string) $field->label,
            'type' => (string) $field->type,
            'required' => (bool) $field->required,
            'min_value' => $field->min_value,
            'max_value' => $field->max_value,
            'validation_regex' => $field->validation_regex,
            'tooltip' => $field->tooltip,
            'help_text' => $field->help_text,
            'orden' => (int) ($field->orden ?? 0),
            'parent_code' => $parentCode,
            'detalle' => $detalleRef,
            'config' => $config,
        ];
    }

    /**
     * @param  array<string, mixed>|null  $ref
     */
    private function resolveDetalle(?array $ref): ?VariableDetalle
    {
        if (! is_array($ref)) {
            return null;
        }

        $variableCodigo = trim((string) ($ref['variable_codigo'] ?? ''));
        $nombre = trim((string) ($ref['nombre'] ?? ''));
        if ($variableCodigo === '' || $nombre === '') {
            return null;
        }

        $variable = Variable::query()->where('codigo', $variableCodigo)->first();
        if (! $variable) {
            return null;
        }

        $detalle = VariableDetalle::query()
            ->where('variable_id', $variable->id)
            ->whereRaw('upper(trim(nombre)) = ?', [mb_strtoupper($nombre)])
            ->first();

        if ($detalle) {
            return $detalle;
        }

        // Fallback por slug si el nombre cambió levemente.
        $slug = trim((string) ($ref['slug'] ?? ''));
        if ($slug === '') {
            return null;
        }

        return VariableDetalle::query()
            ->where('variable_id', $variable->id)
            ->get()
            ->first(fn (VariableDetalle $d) => DictionaryCodes::slug($d) === $slug);
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private function remapConfigDetalleIds(array $config, VariableDetalle $detalle): array
    {
        if (array_key_exists('row_detalle_id', $config) || ($config['row_source'] ?? null) === 'detalle_catalogo') {
            $config['row_detalle_id'] = $detalle->id;
        }

        return $config;
    }

    /**
     * @param  list<int>  $keepFieldIds
     */
    private function pruneFormFields(Formulario $formulario, array $keepFieldIds): int
    {
        $pruned = 0;
        $query = Field::query()
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $formulario->id))
            ->where('type', 'tabla');

        if ($keepFieldIds !== []) {
            $query->whereNotIn('id', $keepFieldIds);
        }

        foreach ($query->get() as $field) {
            $field->delete();
            $pruned++;
        }

        return $pruned;
    }
}
