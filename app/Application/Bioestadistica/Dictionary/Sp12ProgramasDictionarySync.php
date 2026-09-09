<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Alinea dominio 17 con la hoja SP12 de Formularios SP
 * (VIH, transmisión vertical, tuberculosis, enfermedades no transmisibles).
 */
class Sp12ProgramasDictionarySync
{
    public const SNAPSHOT_PATH = 'app/bioestadistica/sp12_programas_snapshot.json';

    public const SP12_TIPOS = [
        'PROGRAMA DE VIH SIDA',
        'PREVENCION DE LA TRANSMISION VERTICAL',
        'PROGRAMA DE TUBERCULOSIS',
        'ENFERMEDADES NO TRANSMISIBLES',
    ];

    private const DEDUPE_OWNERS = [
        'ENFERMEDADES NO TRANSMISIBLES' => [
            'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE DIABETES MELLITUS TIPO II',
            'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE HIPERTENSIÓN ARTERIAL',
            'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE ENFERMEDAD ISQUÉMICA DEL CORAZÓN',
            'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE ASMA O ESTADOS ASMÁTICOS',
        ],
        'PROGRAMA DE TUBERCULOSIS' => [
            'NUMERO DE SINTOMATICOS RESPIRATORIOS CAPTADOS Y NOTIFICADOS PNCT',
            'NÚMERO DE SINTOMÁTICOS RESPIRATORIOS CAPTADOS Y NOTIFICADOS AL PNCT',
            'NÚMERO DE CASOS DE TB CAPTADOS Y NOTIFICADOS AL PNCT',
            'NÚMERO DE MUESTRAS EXAMINADAS',
            'NUMERO DE RRHH CAPACITADOS EN LA PREVENCION, DIAGNOSTICO Y TRATAMIENTO DE LA TB',
            'NÚMERO DE RECURSOS HUMANOS CAPACITADOS EN LA PREVENCIÓN, DIAGNÓSTICO Y TRATAMIENTO DE LA TB',
        ],
    ];

    public function __construct(private HealthVariableDictionary $dictionary = new HealthVariableDictionary)
    {
    }

    /**
     * @return array{snapshot: string, bridges: int, deduped: int}
     */
    public function apply(bool $forceSnapshot = false): array
    {
        $path = storage_path(self::SNAPSHOT_PATH);
        if ($forceSnapshot || ! File::exists($path)) {
            $this->writeSnapshot($path);
        }

        $bridges = 0;
        $orden = 2000;
        foreach ($this->catalog() as $tipo => $prestaciones) {
            foreach ($prestaciones as $nombre) {
                $this->dictionary->remember('17', 'PROGRAMAS DE SALUD', $tipo, $nombre, $orden++);
                $bridges++;
            }
        }

        $deduped = $this->dedupeBridges() + $this->pruneToCatalog();

        return [
            'snapshot' => $path,
            'bridges' => $bridges,
            'deduped' => $deduped,
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
            $variableId = (int) ($snapshot['variable_id'] ?? 0);
            if ($variableId <= 0) {
                return;
            }

            $keepDetalleIds = collect($snapshot['detalles'] ?? [])->pluck('id')->filter()->all();
            $keepBridgeIds = collect($snapshot['bridges'] ?? [])->pluck('id')->filter()->all();

            // Solo tocar tipos SP12 nuevos + puentes asociados; restaurar snapshot completo de dominio 17
            // sería destructivo respecto a SP13. Limitamos a tipos SP12 y bridges tocados.
            $sp12Names = self::SP12_TIPOS;
            $sp12Ids = VariableDetalle::withTrashed()
                ->where('variable_id', $variableId)
                ->whereIn('nombre', $sp12Names)
                ->pluck('id');

            DetalleCatalogoItem::withTrashed()
                ->whereIn('variable_detalle_id', $sp12Ids)
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
                ->where('variable_id', $variableId)
                ->whereIn('nombre', $sp12Names)
                ->whereNotIn('id', $keepDetalleIds)
                ->get()
                ->each->delete();

            foreach ($snapshot['detalles'] ?? [] as $row) {
                if (! in_array($row['nombre'], $sp12Names, true)
                    && ! in_array($row['nombre'], ['VIH', 'TUBERCULOSIS Y EPOC', 'EPIDEMIOLOGIA Y VIGILANCIA', 'DIABETES E HIPERTENSION'], true)) {
                    continue;
                }
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

            $this->restoreSp12Fields($snapshot['sp12_fields'] ?? []);
        });

        return true;
    }

    public function snapshotExists(): bool
    {
        return File::exists(storage_path(self::SNAPSHOT_PATH));
    }

    /**
     * @return array<string, list<string>>
     */
    public function catalog(): array
    {
        return [
            'PROGRAMA DE VIH SIDA' => [
                'NÚMERO DE PACIENTES INTERNADOS EN LA POBLACIÓN GENERAL (PG) CON CONSEJERÍAS PRE-TEST REALIZADAS EN EL MES',
                'NÚMERO DE PACIENTES TESTADOS EN EL MES EN POBLACIÓN ABIERTA',
            ],
            'PREVENCION DE LA TRANSMISION VERTICAL' => [
                'NÚMERO DE PACIENTES TESTADOS EN PERINATOLOGÍA Y/O SALA DE PARTOS',
                'NÚMERO DE CONSEJERÍAS POST-TEST EN PACIENTES POSITIVOS REALIZADOS EN EL MES',
            ],
            'PROGRAMA DE TUBERCULOSIS' => [
                'NÚMERO DE SINTOMÁTICOS RESPIRATORIOS CAPTADOS Y NOTIFICADOS AL PNCT',
                'NÚMERO DE CASOS DE TB CAPTADOS Y NOTIFICADOS AL PNCT',
                'NÚMERO DE MUESTRAS EXAMINADAS',
                'NÚMERO DE RECURSOS HUMANOS CAPACITADOS EN LA PREVENCIÓN, DIAGNÓSTICO Y TRATAMIENTO DE LA TB',
            ],
            'ENFERMEDADES NO TRANSMISIBLES' => [
                'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE DIABETES MELLITUS TIPO II',
                'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE HIPERTENSIÓN ARTERIAL',
                'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE ENFERMEDAD ISQUÉMICA DEL CORAZÓN',
                'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE ASMA O ESTADOS ASMÁTICOS',
            ],
        ];
    }

    private function writeSnapshot(string $path): void
    {
        File::ensureDirectoryExists(dirname($path));

        $variable = Variable::query()->where('codigo', '17')->first();
        $relevantNames = array_merge(self::SP12_TIPOS, [
            'VIH',
            'TUBERCULOSIS Y EPOC',
            'EPIDEMIOLOGIA Y VIGILANCIA',
            'DIABETES E HIPERTENSION',
        ]);

        $detalles = VariableDetalle::withTrashed()
            ->where('variable_id', $variable?->id)
            ->whereIn('nombre', $relevantNames)
            ->orderBy('id')
            ->get();

        $bridges = DetalleCatalogoItem::withTrashed()
            ->whereIn('variable_detalle_id', $detalles->pluck('id'))
            ->orderBy('id')
            ->get();

        $sp12 = Formulario::where('codigo', 'SP12')->with('secciones.fields')->first();
        $fields = [];
        foreach ($sp12?->secciones ?? [] as $section) {
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
            'variable_id' => $variable?->id,
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
            'sp12_fields' => $fields,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     */
    private function restoreSp12Fields(array $fields): void
    {
        if ($fields === []) {
            return;
        }

        $sp12 = Formulario::where('codigo', 'SP12')->first();
        if (! $sp12) {
            return;
        }

        $keepIds = collect($fields)->pluck('id')->filter()->all();
        Field::withTrashed()
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $sp12->id))
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

    private function dedupeBridges(): int
    {
        $variable = Variable::query()->where('codigo', '17')->first();
        if (! $variable) {
            return 0;
        }

        $removed = 0;
        foreach (self::DEDUPE_OWNERS as $ownerName => $prestacionNames) {
            $owner = VariableDetalle::query()
                ->where('variable_id', $variable->id)
                ->where('nombre', $ownerName)
                ->first();
            if (! $owner) {
                continue;
            }

            $normalizedWanted = collect($prestacionNames)
                ->map(fn ($n) => Str::upper(Str::slug(Str::ascii($n), '_')))
                ->all();
            $itemIds = Prestacion::query()
                ->where('activo', true)
                ->get(['id', 'nombre'])
                ->filter(function ($p) use ($normalizedWanted) {
                    $key = Str::upper(Str::slug(Str::ascii((string) $p->nombre), '_'));

                    return in_array($key, $normalizedWanted, true);
                })
                ->pluck('id');

            if ($itemIds->isEmpty()) {
                continue;
            }

            $removed += DetalleCatalogoItem::query()
                ->whereIn('catalogo_item_id', $itemIds)
                ->where('catalogo_tipo', 'prestacion')
                ->where('variable_detalle_id', '<>', $owner->id)
                ->whereHas('detalle', fn ($q) => $q->where('variable_id', $variable->id))
                ->get()
                ->each->delete()
                ->count();
        }

        return $removed;
    }

    private function pruneToCatalog(): int
    {
        $variable = Variable::query()->where('codigo', '17')->first();
        if (! $variable) {
            return 0;
        }

        $removed = 0;
        foreach ($this->catalog() as $tipo => $prestaciones) {
            $detalle = VariableDetalle::query()
                ->where('variable_id', $variable->id)
                ->where('nombre', $tipo)
                ->first();
            if (! $detalle) {
                continue;
            }

            $wanted = collect($prestaciones)
                ->map(fn ($n) => Str::upper(Str::slug(Str::ascii($n), '_')))
                ->all();

            foreach (DetalleCatalogoItem::query()->where('variable_detalle_id', $detalle->id)->where('catalogo_tipo', 'prestacion')->get() as $bridge) {
                $prestacion = Prestacion::query()->find($bridge->catalogo_item_id);
                if (! $prestacion) {
                    $bridge->delete();
                    $removed++;
                    continue;
                }
                $key = Str::upper(Str::slug(Str::ascii((string) $prestacion->nombre), '_'));
                if (! in_array($key, $wanted, true)) {
                    $bridge->delete();
                    $removed++;
                }
            }
        }

        return $removed;
    }
}
