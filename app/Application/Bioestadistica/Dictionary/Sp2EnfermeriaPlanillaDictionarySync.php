<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Agrega al dominio 13 los 4 ítems de la planilla SP2/SP3-enfermería que faltaban.
 * OTROS queda fuera a propósito.
 */
class Sp2EnfermeriaPlanillaDictionarySync
{
    public const SNAPSHOT_PATH = 'app/bioestadistica/sp2_enfermeria_planilla_snapshot.json';

    public const DOMINIO_CODIGO = '13';

    public const DOMINIO_NOMBRE = 'SERVICIOS DE ENFERMERIA';

    /**
     * Ítems planilla a incorporar (sin OTROS).
     * Incluye los de la planilla interior «9- ENFERMERIA» que faltaban en dominio 13.
     *
     * @var list<array{tipo: string, prestacion: string}>
     */
    public const ADDITIONS = [
        ['tipo' => 'CURACIONES', 'prestacion' => 'LAVADO DE OIDO'],
        ['tipo' => 'OBSTETRICIA', 'prestacion' => 'OBSTETRICIA'],
        ['tipo' => 'CUIDADOS A PACIENTES QUIRÚRGICOS', 'prestacion' => 'SUTURA'],
        ['tipo' => 'CONTROL PACIENTES', 'prestacion' => 'TEST DEL PIECITO'],
        // PLA. CARGA INTERIOR — hoja 9- ENFERMERIA
        ['tipo' => 'CONTROL PACIENTES', 'prestacion' => 'CANTIDAD DE PACIENTES EN OBSERVACION'],
        ['tipo' => 'TRASLADO DEL PACIENTE', 'prestacion' => 'CANTIDAD DE PACIENTES EVACUADOS'],
        ['tipo' => 'SIGNOS VITALES', 'prestacion' => 'CONTROL SIGNOS VITALES'],
        ['tipo' => 'CONTROL PACIENTES', 'prestacion' => 'H.G.T. (Prueba de Diabetes)'],
        ['tipo' => 'ADMINISTRACIÓN DE MEDICAMENTOS', 'prestacion' => 'INYECCIONES'],
        ['tipo' => 'CURACIONES', 'prestacion' => 'CURACIONES'],
        ['tipo' => 'TERAPIA DE INFUSION', 'prestacion' => 'HIDRATACION'],
    ];

    public function __construct(private HealthVariableDictionary $dictionary = new HealthVariableDictionary)
    {
    }

    /**
     * @return array{snapshot: string, bridges: int, created_bridges: int, created_detalles: int}
     */
    public function apply(bool $forceSnapshot = false): array
    {
        $path = storage_path(self::SNAPSHOT_PATH);
        if ($forceSnapshot || ! File::exists($path)) {
            $this->writeSnapshot($path);
        }

        $bridges = 0;
        $createdBridges = 0;
        $createdDetalles = 0;
        $orden = 5000;

        foreach (self::ADDITIONS as $row) {
            $result = $this->dictionary->remember(
                self::DOMINIO_CODIGO,
                self::DOMINIO_NOMBRE,
                $row['tipo'],
                $row['prestacion'],
                $orden++
            );
            $bridges++;
            if ($result['created']['bridge'] ?? false) {
                $createdBridges++;
            }
            if ($result['created']['detalle'] ?? false) {
                $createdDetalles++;
            }
        }

        return [
            'snapshot' => $path,
            'bridges' => $bridges,
            'created_bridges' => $createdBridges,
            'created_detalles' => $createdDetalles,
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

            $this->restoreSp2Fields($snapshot['sp2_fields'] ?? []);
        });

        return true;
    }

    public function snapshotExists(): bool
    {
        return File::exists(storage_path(self::SNAPSHOT_PATH));
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     */
    private function restoreSp2Fields(array $fields): void
    {
        if ($fields === []) {
            return;
        }

        $sp2 = Formulario::where('codigo', 'SP2')->first();
        if (! $sp2) {
            return;
        }

        $keepIds = collect($fields)->pluck('id')->filter()->all();
        Field::withTrashed()
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $sp2->id))
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
                'required' => (bool) ($row['required'] ?? false),
            ])->save();
        }
    }

    private function writeSnapshot(string $path): void
    {
        File::ensureDirectoryExists(dirname($path));

        $variable = Variable::query()->where('codigo', self::DOMINIO_CODIGO)->first();
        $detalles = VariableDetalle::withTrashed()
            ->where('variable_id', $variable?->id)
            ->orderBy('id')
            ->get();

        $bridges = DetalleCatalogoItem::withTrashed()
            ->whereIn('variable_detalle_id', $detalles->pluck('id'))
            ->orderBy('id')
            ->get();

        $sp2 = Formulario::where('codigo', 'SP2')->with('secciones.fields')->first();
        $fields = [];
        foreach ($sp2?->secciones ?? [] as $section) {
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
            'additions' => self::ADDITIONS,
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
            'sp2_fields' => $fields,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
