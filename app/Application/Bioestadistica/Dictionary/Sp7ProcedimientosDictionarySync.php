<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Procedimiento;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Alinea dominios 5–9 y 14 con la hoja SP7 (grupos: Anestesia, Cirugías, etc.).
 */
class Sp7ProcedimientosDictionarySync
{
    public const SNAPSHOT_PATH = 'app/bioestadistica/sp7_procedimientos_snapshot.json';

    /** Tipos consolidados que publica SP7 (orden planilla). */
    public const SP7_TIPOS = [
        ['codigo' => '6', 'dominio' => 'ANESTESIA', 'tipo' => 'ANESTESIA'],
        ['codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'BANCO DE SANGRE'],
        ['codigo' => '5', 'dominio' => 'CIRUGIA', 'tipo' => 'CIRUGIAS'],
        ['codigo' => '8', 'dominio' => 'DIALISIS', 'tipo' => 'DIALISIS'],
        ['codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'FISIOTERAPIA Y REHABILITACION'],
        ['codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'PLANIFICACION FAMILIAR'],
        ['codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'SALUD MENTAL'],
        ['codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'SALUD OCUPACIONAL'],
        ['codigo' => '9', 'dominio' => 'TRATAMINETO NEOADYUVANTE', 'tipo' => 'TRATAMIENTO NEOADYUVANTE'],
        ['codigo' => '7', 'dominio' => 'U.T.I.', 'tipo' => 'U.T.I.'],
        ['codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'Z-OTROS PROCEDIMIENTOS'],
    ];

    public function __construct(private HealthVariableDictionary $dictionary = new HealthVariableDictionary)
    {
    }

    /**
     * @return array{snapshot: string, bridges: int, pruned: int}
     */
    public function apply(bool $forceSnapshot = false): array
    {
        $path = storage_path(self::SNAPSHOT_PATH);
        if ($forceSnapshot || ! File::exists($path)) {
            $this->writeSnapshot($path);
        }

        $bridges = 0;
        $orden = 3000;
        foreach ($this->catalog() as $meta) {
            foreach ($meta['items'] as $nombre) {
                $this->dictionary->remember($meta['codigo'], $meta['dominio'], $meta['tipo'], $nombre, $orden++);
                $bridges++;
            }
        }

        // Alias legacy: mover ítems de PLANIF FAMILIAR → PLANIFICACION FAMILIAR si existían.
        $this->migrateLegacyTipo('14', 'PLANIF FAMILIAR', 'PLANIFICACION FAMILIAR');
        $this->migrateLegacyTipo('14', 'OTROS PROCEDIMIENTOS', 'Z-OTROS PROCEDIMIENTOS');
        $this->migrateLegacyTipo('7', 'UTI', 'U.T.I.');
        $this->migrateLegacyAnestesias();
        $this->migrateLegacyCirugias();
        $this->migrateLegacyDialisis();
        $this->migrateLegacyNeoadyuvante();

        $pruned = $this->pruneToCatalog();

        return [
            'snapshot' => $path,
            'bridges' => $bridges,
            'pruned' => $pruned,
        ];
    }

    public function rollback(): bool
    {
        $path = storage_path(self::SNAPSHOT_PATH);
        if (! File::exists($path)) {
            return false;
        }

        $snapshot = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        DB::transaction(function () use ($snapshot) {
            $keepDetalleIds = collect($snapshot['detalles'] ?? [])->pluck('id')->filter()->all();
            $keepBridgeIds = collect($snapshot['bridges'] ?? [])->pluck('id')->filter()->all();
            $variableIds = collect($snapshot['variable_ids'] ?? [])->filter()->all();

            if ($variableIds === []) {
                return;
            }

            $consolidatedNames = collect(self::SP7_TIPOS)->pluck('tipo')->all();

            DetalleCatalogoItem::withTrashed()
                ->whereHas('detalle', fn ($q) => $q->whereIn('variable_id', $variableIds)->whereIn('nombre', $consolidatedNames))
                ->whereNotIn('id', $keepBridgeIds)
                ->get()
                ->each->delete();

            foreach ($snapshot['bridges'] ?? [] as $row) {
                $bridge = DetalleCatalogoItem::withTrashed()->find($row['id']);
                if (! $bridge) {
                    continue;
                }
                if (! empty($row['deleted_at'])) {
                    if (! $bridge->trashed()) {
                        $bridge->delete();
                    }
                    continue;
                }
                if ($bridge->trashed()) {
                    $bridge->restore();
                }
                $bridge->fill([
                    'variable_detalle_id' => $row['variable_detalle_id'],
                    'catalogo_tipo' => $row['catalogo_tipo'],
                    'catalogo_item_id' => $row['catalogo_item_id'],
                    'orden' => $row['orden'],
                    'activo' => (bool) $row['activo'],
                ])->save();
            }

            VariableDetalle::withTrashed()
                ->whereIn('variable_id', $variableIds)
                ->whereIn('nombre', $consolidatedNames)
                ->whereNotIn('id', $keepDetalleIds)
                ->get()
                ->each->delete();

            foreach ($snapshot['detalles'] ?? [] as $row) {
                $detalle = VariableDetalle::withTrashed()->find($row['id']);
                if (! $detalle) {
                    continue;
                }
                if (! empty($row['deleted_at'])) {
                    if (! $detalle->trashed()) {
                        $detalle->delete();
                    }
                    continue;
                }
                if ($detalle->trashed()) {
                    $detalle->restore();
                }
                $detalle->fill([
                    'nombre' => $row['nombre'],
                    'orden' => $row['orden'],
                    'activo' => (bool) $row['activo'],
                    'catalogo_tipo' => $row['catalogo_tipo'],
                    'layout_captura' => $row['layout_captura'],
                    'meta' => $row['meta'],
                ])->save();
            }

            $this->restoreSp7Fields($snapshot['sp7_fields'] ?? []);
        });

        return true;
    }

    public function snapshotExists(): bool
    {
        return File::exists(storage_path(self::SNAPSHOT_PATH));
    }

    /**
     * @return list<array{codigo:string,dominio:string,tipo:string,items:list<string>}>
     */
    public function catalog(): array
    {
        return [
            [
                'codigo' => '6', 'dominio' => 'ANESTESIA', 'tipo' => 'ANESTESIA',
                'items' => [
                    'ANESTESIA GENERAL',
                    'ANESTESIA LOCAL',
                    'ANESTESIA PERIDURAL',
                    'ANESTESIA RAQUIDEA',
                    'BLOQUEO OFTALMOLOGICA',
                    'BLOQUEO PERIFERICO',
                    'SEDACION',
                ],
            ],
            [
                'codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'BANCO DE SANGRE',
                'items' => [
                    'AFERESIS',
                    'COLECTA DE CELULAS MADRE P/ TRASPLANTE M. O.',
                    'EXPORTACION DE COMPONENTES SANGUINEOS',
                    'PROVISION DE HEMODERIVADOS',
                    'SANGRE - EXTRACCIONES (COLECTA)',
                    'SANGRE - TRANSFUCIONES',
                    'TCDEL',
                    'TIPIFICACION DONANTES',
                    'TIPIFICACION PACIENTES',
                ],
            ],
            [
                'codigo' => '5', 'dominio' => 'CIRUGIA', 'tipo' => 'CIRUGIAS',
                'items' => [
                    'DE ALTA COMPLEJIDAD',
                    'MAYOR',
                    'MENOR',
                ],
            ],
            [
                'codigo' => '8', 'dominio' => 'DIALISIS', 'tipo' => 'DIALISIS',
                'items' => [
                    'DIALISIS PERITONEAL',
                    'HEMODIALISIS',
                ],
            ],
            [
                'codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'FISIOTERAPIA Y REHABILITACION',
                'items' => [
                    'SECIONES FISIOTERAPIA Y REHABILITACION',
                ],
            ],
            [
                'codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'PLANIFICACION FAMILIAR',
                'items' => [
                    'DISPOSITIVO INTRAUTERINO',
                    'ETINIL ESTRADIO+LEVONORGESTREL',
                    'INY.VALERATO+ENANTATO',
                    'LINESTRENOL',
                    'OTROS P.F.',
                    'PRESERVATIVO',
                ],
            ],
            [
                'codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'SALUD MENTAL',
                'items' => [
                    'PSICOMETRIA',
                ],
            ],
            [
                'codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'SALUD OCUPACIONAL',
                'items' => [
                    'ADMISION AL SEGURO SOCIAL',
                ],
            ],
            [
                'codigo' => '9', 'dominio' => 'TRATAMINETO NEOADYUVANTE', 'tipo' => 'TRATAMIENTO NEOADYUVANTE',
                'items' => [
                    'QUIMIOTERAPIA',
                    'RADIOTERAPIA',
                ],
            ],
            [
                'codigo' => '7', 'dominio' => 'U.T.I.', 'tipo' => 'U.T.I.',
                'items' => [
                    'U.T.I. ADULTO',
                    'U.T.I. PEDIATRICO',
                    'REANIMACION',
                ],
            ],
            [
                'codigo' => '14', 'dominio' => 'PROCEDIMIENTOS', 'tipo' => 'Z-OTROS PROCEDIMIENTOS',
                'items' => [
                    'INVASIVO',
                    'NO INVASIVO',
                ],
            ],
        ];
    }

    private function writeSnapshot(string $path): void
    {
        File::ensureDirectoryExists(dirname($path));
        $variableIds = Variable::query()->whereIn('codigo', ['5', '6', '7', '8', '9', '14'])->pluck('id');
        $detalles = VariableDetalle::withTrashed()->whereIn('variable_id', $variableIds)->orderBy('id')->get();
        $bridges = DetalleCatalogoItem::withTrashed()->whereIn('variable_detalle_id', $detalles->pluck('id'))->orderBy('id')->get();

        $sp7 = Formulario::where('codigo', 'SP7')->with('secciones.fields')->first();
        $fields = [];
        foreach ($sp7?->secciones ?? [] as $section) {
            foreach ($section->fields as $field) {
                $fields[] = [
                    'id' => $field->id,
                    'seccion_id' => $field->seccion_id,
                    'code' => $field->code,
                    'label' => $field->label,
                    'type' => $field->type,
                    'detalle_id' => $field->detalle_id,
                    'config' => $field->config,
                    'orden' => $field->orden,
                    'required' => $field->required,
                    'deleted_at' => optional($field->deleted_at)?->toDateTimeString(),
                ];
            }
        }

        File::put($path, json_encode([
            'taken_at' => now()->toIso8601String(),
            'variable_ids' => $variableIds->values()->all(),
            'detalles' => $detalles->map(fn (VariableDetalle $d) => [
                'id' => $d->id,
                'nombre' => $d->nombre,
                'orden' => $d->orden,
                'activo' => $d->activo,
                'catalogo_tipo' => $d->catalogo_tipo,
                'layout_captura' => $d->layout_captura,
                'meta' => $d->meta,
                'deleted_at' => optional($d->deleted_at)?->toDateTimeString(),
            ])->values()->all(),
            'bridges' => $bridges->map(fn (DetalleCatalogoItem $b) => [
                'id' => $b->id,
                'variable_detalle_id' => $b->variable_detalle_id,
                'catalogo_tipo' => $b->catalogo_tipo,
                'catalogo_item_id' => $b->catalogo_item_id,
                'orden' => $b->orden,
                'activo' => $b->activo,
                'deleted_at' => optional($b->deleted_at)?->toDateTimeString(),
            ])->values()->all(),
            'sp7_fields' => $fields,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     */
    private function restoreSp7Fields(array $fields): void
    {
        if ($fields === []) {
            return;
        }
        $sp7 = Formulario::where('codigo', 'SP7')->first();
        if (! $sp7) {
            return;
        }
        $keepIds = collect($fields)->pluck('id')->filter()->all();
        Field::withTrashed()
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $sp7->id))
            ->where('type', 'tabla')
            ->whereNotIn('id', $keepIds)
            ->get()
            ->each->delete();

        foreach ($fields as $row) {
            $field = Field::withTrashed()->find($row['id'] ?? 0);
            if (! $field) {
                continue;
            }
            if (! empty($row['deleted_at'])) {
                if (! $field->trashed()) {
                    $field->delete();
                }
                continue;
            }
            if ($field->trashed()) {
                $field->restore();
            }
            $field->fill([
                'seccion_id' => $row['seccion_id'],
                'code' => $row['code'],
                'label' => $row['label'],
                'type' => $row['type'],
                'detalle_id' => $row['detalle_id'],
                'config' => $row['config'],
                'orden' => $row['orden'],
                'required' => (bool) $row['required'],
            ])->save();
        }
    }

    private function migrateLegacyTipo(string $codigo, string $from, string $to): void
    {
        $variable = Variable::query()->where('codigo', $codigo)->first();
        if (! $variable) {
            return;
        }
        $legacy = VariableDetalle::query()->where('variable_id', $variable->id)->where('nombre', $from)->first();
        $target = VariableDetalle::query()->where('variable_id', $variable->id)->where('nombre', $to)->first();
        if (! $legacy || ! $target) {
            return;
        }
        foreach (DetalleCatalogoItem::query()->where('variable_detalle_id', $legacy->id)->get() as $bridge) {
            $exists = DetalleCatalogoItem::withTrashed()
                ->where('variable_detalle_id', $target->id)
                ->where('catalogo_tipo', $bridge->catalogo_tipo)
                ->where('catalogo_item_id', $bridge->catalogo_item_id)
                ->first();
            if ($exists) {
                if ($exists->trashed()) {
                    $exists->restore();
                }
                $bridge->delete();
                continue;
            }
            $bridge->forceFill(['variable_detalle_id' => $target->id])->save();
        }
    }

    private function migrateLegacyAnestesias(): void
    {
        $variable = Variable::query()->where('codigo', '6')->first();
        $target = VariableDetalle::query()->where('variable_id', $variable?->id)->where('nombre', 'ANESTESIA')->first();
        if (! $variable || ! $target) {
            return;
        }
        $legacyNames = [
            'ANESTESIA GENERAL', 'ANESTESIA LOCAL', 'ANESTESIA PERIDURAL', 'ANESTESIA RAQUIDEA',
            'BLOQUEO OFTALMOLOGICA', 'BLOQUEO PERIFERICO', 'SEDACION',
        ];
        foreach (VariableDetalle::query()->where('variable_id', $variable->id)->whereIn('nombre', $legacyNames)->get() as $legacy) {
            foreach (DetalleCatalogoItem::query()->where('variable_detalle_id', $legacy->id)->get() as $bridge) {
                $exists = DetalleCatalogoItem::withTrashed()
                    ->where('variable_detalle_id', $target->id)
                    ->where('catalogo_tipo', $bridge->catalogo_tipo)
                    ->where('catalogo_item_id', $bridge->catalogo_item_id)
                    ->first();
                if ($exists) {
                    if ($exists->trashed()) {
                        $exists->restore();
                    }
                    $bridge->delete();
                } else {
                    $bridge->forceFill(['variable_detalle_id' => $target->id])->save();
                }
            }
        }
    }

    private function migrateLegacyCirugias(): void
    {
        $variable = Variable::query()->where('codigo', '5')->first();
        $target = VariableDetalle::query()->where('variable_id', $variable?->id)->where('nombre', 'CIRUGIAS')->first();
        if (! $variable || ! $target) {
            return;
        }
        // Los tipos viejos tenían filas URGENCIAS/PROGRAMADA; la planilla SP7 usa solo el tipo como fila.
        // No movemos esas métricas; las filas nuevas ya están en CIRUGIAS vía remember().
    }

    private function migrateLegacyDialisis(): void
    {
        // Ítems nuevos DIALISIS PERITONEAL / HEMODIALISIS ya creados por remember().
    }

    private function migrateLegacyNeoadyuvante(): void
    {
        $variable = Variable::query()->where('codigo', '9')->first();
        $target = VariableDetalle::query()->where('variable_id', $variable?->id)->where('nombre', 'TRATAMIENTO NEOADYUVANTE')->first();
        if (! $variable || ! $target) {
            return;
        }
        foreach (['QUIMIOTERAPIA', 'RADIOTERAPIA'] as $name) {
            $legacy = VariableDetalle::query()->where('variable_id', $variable->id)->where('nombre', $name)->first();
            if (! $legacy) {
                continue;
            }
            // Crear/asegurar prestación con el nombre del tipo y puente al consolidado.
            $this->dictionary->remember('9', 'TRATAMINETO NEOADYUVANTE', 'TRATAMIENTO NEOADYUVANTE', $name, 0);
        }
    }

    private function pruneToCatalog(): int
    {
        $removed = 0;
        foreach ($this->catalog() as $meta) {
            $variable = Variable::query()->where('codigo', $meta['codigo'])->first();
            $detalle = VariableDetalle::query()
                ->where('variable_id', $variable?->id)
                ->where('nombre', $meta['tipo'])
                ->first();
            if (! $detalle) {
                continue;
            }
            $wanted = collect($meta['items'])
                ->map(fn ($n) => Str::upper(Str::slug(Str::ascii($n), '_')))
                ->all();

            foreach (DetalleCatalogoItem::query()->where('variable_detalle_id', $detalle->id)->get() as $bridge) {
                $nombre = null;
                if ($bridge->catalogo_tipo === 'procedimiento') {
                    $nombre = Procedimiento::query()->where('id', $bridge->catalogo_item_id)->value('nombre');
                } else {
                    $nombre = Prestacion::query()->where('id', $bridge->catalogo_item_id)->value('nombre');
                }
                if (! $nombre) {
                    $bridge->delete();
                    $removed++;
                    continue;
                }
                $key = Str::upper(Str::slug(Str::ascii((string) $nombre), '_'));
                if (! in_array($key, $wanted, true)) {
                    $bridge->delete();
                    $removed++;
                }
            }
        }

        return $removed;
    }
}
