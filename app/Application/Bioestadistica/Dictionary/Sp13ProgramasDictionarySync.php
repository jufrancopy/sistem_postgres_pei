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
 * Alinea dominio 17 (Programas de salud) con la hoja SP13 de Formularios SP.
 * Diabetes/Nutrición/VIH/TB/Epidemiología permanecen en el diccionario pero no en SP13.
 */
class Sp13ProgramasDictionarySync
{
    public const SNAPSHOT_PATH = 'app/bioestadistica/sp13_programas_snapshot.json';

    public const SP13_TIPOS = [
        'PROGRAMA SALUD SEXUAL Y REPRODUCTIVA',
        'PREVENCION DEL CANCER CERVICO UTERINO Y MAMAS',
        'VIOLENCIA',
        'ADULTOS MAYORES VIDA PLENA',
        'SALUD DEL ADOLESCENTE',
        'SALUD DEL NINO',
        'SALUD BUCODENTAL',
    ];

    /** Prestaciones que no deben quedar puenteadas fuera de su tipo SP13 canónico. */
    private const DEDUPE_OWNERS = [
        'SALUD DEL NINO' => [
            'NÚMERO DE NIÑOS/AS < A 6 MESES QUE RECIBEN LACTANCIA MATERNA EXCLUSIVA',
            'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIARREA',
            'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE DIARREA CON DESHIDRATACION GRAVE',
            'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE DIARREA CON DESHIDRATACIÓN GRAVE',
        ],
        'SALUD DEL ADOLESCENTE' => [
            'NÚMERO DE ADOLESCENTES CON SIGNOS DE VIOLENCIA DETECTADOS QUE CONSULTAN EN LOS SERVICIOS DE SALUD',
        ],
        'PREVENCION DEL CANCER CERVICO UTERINO Y MAMAS' => [
            'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE CÁNCER DE CUELLO UTERINO',
            'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE CÁNCER DE MAMA',
        ],
    ];

    public function __construct(private HealthVariableDictionary $dictionary = new HealthVariableDictionary)
    {
    }

    /**
     * @return array{snapshot: string, created_detalles: int, bridges: int, deduped: int}
     */
    public function apply(bool $forceSnapshot = false): array
    {
        $path = storage_path(self::SNAPSHOT_PATH);
        if ($forceSnapshot || ! File::exists($path)) {
            $this->writeSnapshot($path);
        }

        $bridges = 0;
        $orden = 1000;
        foreach ($this->catalog() as $tipo => $prestaciones) {
            foreach ($prestaciones as $nombre) {
                $this->dictionary->remember('17', 'PROGRAMAS DE SALUD', $tipo, $nombre, $orden++);
                $bridges++;
            }
        }

        $deduped = $this->dedupeBridges();
        $pruned = $this->pruneToCatalog();

        return [
            'snapshot' => $path,
            'created_detalles' => VariableDetalle::query()
                ->whereHas('variable', fn ($q) => $q->where('codigo', '17'))
                ->whereIn('nombre', self::SP13_TIPOS)
                ->count(),
            'bridges' => $bridges,
            'deduped' => $deduped + $pruned,
        ];
    }

    /**
     * Restaura el snapshot previo (si existe) y deja el diccionario como antes del sync.
     */
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

            DetalleCatalogoItem::withTrashed()
                ->whereHas('detalle', fn ($q) => $q->where('variable_id', $variableId))
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

            $this->restoreSp13Fields($snapshot['sp13_fields'] ?? []);
        });

        return true;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     */
    private function restoreSp13Fields(array $fields): void
    {
        if ($fields === []) {
            return;
        }

        $sp13 = Formulario::where('codigo', 'SP13')->first();
        if (! $sp13) {
            return;
        }

        $keepIds = collect($fields)->pluck('id')->filter()->all();
        Field::withTrashed()
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $sp13->id))
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
            'PROGRAMA SALUD SEXUAL Y REPRODUCTIVA' => [
                'NÚMERO DE EMBARAZADAS QUE SE REALIZAN TEST RÁPIDOS PARA VIH Y SÍFILIS',
                'NÚMERO USUARIOS ASEGURADOS QUE RETIRAN ANTICONCEPTIVOS ORALES',
                'NÚMERO DE USUARIOS QUE RETIRAN DISPOSITIVOS DE BARRERAS',
                'NÚMERO DE TEST RÁPIDO PARA VIH POSITIVO',
                'NÚMERO DE TEST RÁPIDO PARA SÍFILIS POSITIVO',
                'NÚMERO DE TEST RÁPIDO PARA VIH Y SÍFILIS',
                'NÚMERO DE RECIÉN NACIDOS CON VDRL POSITIVO',
                'NÚMERO DE NACIDOS VIVOS EN EL SERVICIO DE SALUD',
                'NÚMERO DE CHARLAS SOBRE SALUD SEXUAL Y REPRODUCTIVA REALIZADAS EN LA SALA DE ESPERA',
                'NÚMERO DE EMBARAZADAS CON CONTROL PRENATAL EN EL PRIMER TRIMESTRE DE EMBARAZO',
                'NÚMERO DE EMBARAZADAS CON CONTROL PRENATAL',
                'NÚMERO DE EMBARAZADAS CON CONTROL PRENATAL NULO',
                'NÚMERO DE EMBARAZADAS < DE 18 AÑOS CON ATENCIÓN PRENATAL',
                'NÚMERO DE CESÁREAS REALIZADAS EN EL SERVICIO',
                'NÚMERO DE PARTOS REALIZADOS',
                'NÚMERO DE RECIÉN NACIDOS CON PESO < A 2500 GR',
                'NÚMERO DE NACIMIENTOS EN EL SERVICIO DE SALUD',
                'NÚMERO DE EMBARAZADAS CON CONTROL ODONTOLÓGICO',
                'NÚMERO DE EMERGENCIAS OBSTÉTRICAS ATENDIDAS EN EL SERVICIO DE SALUD',
                'NÚMERO DE EMERGENCIAS OBSTÉTRICAS RESUELTAS CON ÉXITO EN EL SERVICIO DE SALUD',
                'NÚMERO DE MUERTES MATERNAS EN EL SERVICIO',
                'NÚMERO DE EMBARAZADAS CON HEMORRAGIA',
                'NÚMERO DE EMBARAZADAS CON INFECCIONES',
                'NÚMERO DE ABORTO',
                'NÚMERO DE RECIÉN NACIDOS FALLECIDOS < A 28 DÍAS DE VIDA',
                'NÚMERO DE RECIÉN NACIDOS CON LACTANCIA MATERNA EXCLUSIVA AL ALTA',
                'NÚMERO DE RECIÉN NACIDOS DADOS DE ALTA',
                'CUMPLE EL SERVICIO DE SALUD CON LOS 10 PASOS DE LA LACTANCIA MATERNA',
                'NÚMERO DE CHARLAS REALIZADAS EN EL SERVICIO DE SALUD SOBRE BENEFICIOS DE LA LACTANCIA MATERNA',
            ],
            'PREVENCION DEL CANCER CERVICO UTERINO Y MAMAS' => [
                'NÚMERO DE PRUEBAS DE PAP CON RESULTADO POSITIVO',
                'NÚMERO DE PRUEBAS DE PAP REALIZADOS',
                'NÚMERO DE ESTUDIOS DE COLPOSCOPIA REALIZADOS',
                'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE CÁNCER DE CUELLO UTERINO',
                'NÚMERO DE MUJERES QUE CONSULTAN EN EL SERVICIO DE SALUD',
                'NÚMERO DE PACIENTES CON DIAGNÓSTICO DE CÁNCER DE MAMA',
                'NÚMERO TOTAL DE CONSULTAS (VARONES + MUJERES) EN EL SERVICIO DE SALUD',
                'NÚMERO DE CHARLAS REALIZADAS SOBRE PREVENCIÓN DEL CÁNCER CÉRVICO-UTERINO',
            ],
            'VIOLENCIA' => [
                'NÚMERO DE MUJERES QUE CONSULTAN EN EL SERVICIO DE SALUD VÍCTIMAS DE VIOLENCIA',
                'NÚMERO DE NIÑOS Y NIÑAS VÍCTIMAS DE VIOLENCIA Y ABUSO SEXUAL',
                'NÚMERO DE NIÑOS VÍCTIMAS DE VIOLENCIA Y ABUSO SEXUAL',
                'NÚMERO DE NIÑAS VÍCTIMAS DE VIOLENCIA Y ABUSO SEXUAL',
                'NÚMERO DE CHARLAS REALIZADAS SOBRE PREVENCIÓN DE VIOLENCIA INTRAFAMILIAR',
            ],
            'ADULTOS MAYORES VIDA PLENA' => [
                'NÚMERO DE ADULTOS MAYORES QUE REALIZAN ACTIVIDADES SOCIO-SANITARIAS',
                'NÚMERO DE CHARLAS SOBRE ESTILOS DE VIDA SALUDABLES Y FACTORES DE RIESGO',
                'NÚMERO DE ADULTOS MAYORES QUE PARTICIPAN EN CLUB DE HIPERTENSIÓN ARTERIAL',
                'NÚMERO DE ADULTOS MAYORES QUE PARTICIPAN EN CLUB DE DIABETES',
                'NÚMERO DE CHARLAS REALIZADAS SOBRE FACTORES DE RIESGO',
            ],
            'SALUD DEL ADOLESCENTE' => [
                'NÚMERO DE ADOLESCENTES CON APLICACIÓN DE TEST PSICO-EMOCIONAL',
                'NÚMERO DE CHARLAS SOBRE SALUD SEXUAL Y REPRODUCTIVA Y HÁBITOS DE HIGIENE',
                'NÚMERO DE CHARLAS SOBRE PREVENCIÓN DE ADICCIONES Y VIOLENCIA',
                'NÚMERO DE ADOLESCENTES CON SIGNOS DE VIOLENCIA DETECTADOS QUE CONSULTAN EN LOS SERVICIOS DE SALUD',
                'NÚMERO DE ADOLESCENTES CON INSPECCIÓN BUCO-DENTAL',
                'NÚMERO DE ADOLESCENTES CON DESNUTRICIÓN',
                'NÚMERO DE ADOLESCENTES CON SOBREPESO',
                'NÚMERO DE ADOLESCENTES CON OBESIDAD',
                'NÚMERO DE ADOLESCENTES PARTICIPANDO EN CLUB DE ADOLESCENTES EN SU TIEMPO LIBRE',
            ],
            'SALUD DEL NINO' => [
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS QUE CONSULTAN EN CRECIMIENTO Y DESARROLLO',
                'NÚMERO DE NIÑOS/AS MENORES A 5 AÑOS CON PESO NORMAL',
                'NÚMERO DE NIÑOS/AS MENORES A 5 AÑOS CON SOBREPESO',
                'NÚMERO DE NIÑOS/AS MENORES A 5 AÑOS CON OBESIDAD',
                'NÚMERO DE NIÑOS/AS MENORES A 5 AÑOS DESNUTRIDOS',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON TRASTORNOS DE DESARROLLO',
                'NÚMERO DE CHARLAS EN SALA DE ESPERA DE PEDIATRÍA',
                'NÚMERO DE R.N. (< A 28 DÍAS) QUE CONSULTAN EN PEDIATRÍA',
                'NÚMERO DE NIÑOS/AS < A 6 MESES QUE CONSULTAN EN PEDIATRÍA',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE NO NEUMONÍA',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE GRIPE',
                'NÚMERO DE NIÑOS/AS < A 6 MESES QUE RECIBEN LACTANCIA MATERNA EXCLUSIVA',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON ANEMIA',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON PARASITOSIS',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS INMUNIZADOS',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE NEUMONÍA',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE NEUMONÍA GRAVE',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIARREA',
                'NÚMERO DE NIÑOS/AS < A 5 AÑOS CON DIAGNÓSTICO DE DIARREA CON DESHIDRATACIÓN GRAVE',
            ],
            'SALUD BUCODENTAL' => [
                'NÚMERO DE CHARLAS EDUCATIVAS EN SALUD BUCO-DENTAL',
                'NÚMERO TOTAL DE INSPECCIÓN BUCODENTAL',
                'NÚMERO DE INSPECCIÓN BUCODENTAL EN ADULTO MAYOR',
                'NÚMERO DE INSPECCIÓN BUCO-DENTAL EN ADOLESCENTES',
                'NÚMERO DE INSPECCIÓN BUCO-DENTAL EN NIÑOS',
            ],
        ];
    }

    private function writeSnapshot(string $path): void
    {
        File::ensureDirectoryExists(dirname($path));

        $variable = Variable::query()->where('codigo', '17')->first();
        $detalles = VariableDetalle::withTrashed()
            ->where('variable_id', $variable?->id)
            ->orderBy('id')
            ->get();

        $bridges = DetalleCatalogoItem::withTrashed()
            ->whereIn('variable_detalle_id', $detalles->pluck('id'))
            ->orderBy('id')
            ->get();

        $sp13 = Formulario::where('codigo', 'SP13')->with('secciones.fields')->first();
        $fields = [];
        foreach ($sp13?->secciones ?? [] as $section) {
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
            'sp13_fields' => $fields,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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

            $itemIds = Prestacion::query()
                ->whereIn('nombre', $prestacionNames)
                ->pluck('id');

            // También matchear por normalización aproximada de tildes/espacios.
            $normalizedWanted = collect($prestacionNames)
                ->map(fn ($n) => Str::upper(Str::slug(Str::ascii($n), '_')))
                ->all();
            $extraIds = Prestacion::query()
                ->where('activo', true)
                ->get(['id', 'nombre'])
                ->filter(function ($p) use ($normalizedWanted) {
                    $key = Str::upper(Str::slug(Str::ascii((string) $p->nombre), '_'));

                    return in_array($key, $normalizedWanted, true);
                })
                ->pluck('id');
            $itemIds = $itemIds->merge($extraIds)->unique()->values();

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

        // Cáncer: quitar de SSR si ya está en el tipo cáncer.
        $cancer = VariableDetalle::query()
            ->where('variable_id', $variable->id)
            ->where('nombre', 'PREVENCION DEL CANCER CERVICO UTERINO Y MAMAS')
            ->first();
        $ssr = VariableDetalle::query()
            ->where('variable_id', $variable->id)
            ->where('nombre', 'PROGRAMA SALUD SEXUAL Y REPRODUCTIVA')
            ->first();
        if ($cancer && $ssr) {
            $cancerItemIds = DetalleCatalogoItem::query()
                ->where('variable_detalle_id', $cancer->id)
                ->pluck('catalogo_item_id');
            $removed += DetalleCatalogoItem::query()
                ->where('variable_detalle_id', $ssr->id)
                ->whereIn('catalogo_item_id', $cancerItemIds)
                ->get()
                ->each->delete()
                ->count();
        }

        return $removed;
    }

    /**
     * Deja en cada tipo SP13 solo prestaciones del catálogo planilla (por nombre normalizado).
     */
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

            $bridges = DetalleCatalogoItem::query()
                ->where('variable_detalle_id', $detalle->id)
                ->where('catalogo_tipo', 'prestacion')
                ->with([])
                ->get();

            foreach ($bridges as $bridge) {
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
