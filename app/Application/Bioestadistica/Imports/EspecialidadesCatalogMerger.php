<?php

namespace App\Application\Bioestadistica\Imports;

use App\Application\Bioestadistica\Dictionary\CatalogType;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\EspecialidadMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Fusiones/alias acordadas entre nombres del diccionario y la planilla de códigos.
 * Tras normalizar el catálogo: 1 especialidad = 1 fila (sin contexto).
 */
class EspecialidadesCatalogMerger
{
    /**
     * @return array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}
     */
    public function applyAgreedAliases(): array
    {
        $summary = [
            'merged' => 0,
            'renamed' => 0,
            'codigo_only' => 0,
            'soft_deleted' => 0,
            'details' => [],
        ];

        return DB::transaction(function () use (&$summary) {
            $this->renameOrMerge('ANESTESIOLOIA', 'ANESTESIOLOGIA', '51', $summary);
            $this->psiquiatriaOnlyCodigo($summary);
            $this->renameOrMerge('CARDIOLOGÍA INFANTIL', 'CARDIOLOGIA PEDIATRICA', '5', $summary);
            $this->renameOrMerge('CARDIOLOGIA INFANTIL', 'CARDIOLOGIA PEDIATRICA', '5', $summary);
            $this->renameOrMerge('NEFROLOGIA INFANTIL', 'NEFROLOGIA PEDIATRICA', '6', $summary);
            $this->renameOrMerge('GASTROENTEROLOGÍA INFANTIL', 'GASTROENTEROLOGIA PEDIATRICA', '60', $summary);
            $this->renameOrMerge('GASTROENTEROLOGIA INFANTIL', 'GASTROENTEROLOGIA PEDIATRICA', '60', $summary);
            $this->renameOrMerge('INFECTOLOGÍA INFANTIL', 'INFECTOLOGIA PEDIATRICA', '139', $summary);
            $this->renameOrMerge('INFECTOLOGIA INFANTIL', 'INFECTOLOGIA PEDIATRICA', '139', $summary);
            $this->renameOrMerge('FISIATRIA', 'FISIATRIA ADULTO', '18', $summary);
            $this->renameOrMerge('TRAUMATOLOGÍA', 'TRAUMATOLOGIA GENERAL', '16', $summary);
            $this->renameOrMerge('TRAUMATOLOGIA', 'TRAUMATOLOGIA GENERAL', '16', $summary);
            $this->applyFollowUpAliases($summary);

            return $summary;
        });
    }

    /**
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    public function applyFollowUpAliases(?array &$summary = null): array
    {
        $summary ??= [
            'merged' => 0,
            'renamed' => 0,
            'codigo_only' => 0,
            'soft_deleted' => 0,
            'details' => [],
        ];

        return DB::transaction(function () use (&$summary) {
            $this->renameOrMerge('PSICOLOGIA PEDIATRICA', 'PSICOLOGIA NIÑOS', '38', $summary);
            $this->ensureCodigoExactNombre(['PSICOLOGÍA', 'PSICOLOGIA'], '70', $summary);
            $this->ensureEspecialidad('HEMATOLOGIA', '2', $summary);
            $this->ensureEspecialidad('HEMATOLOGIA PEDIATRICA', '109', $summary);
            $this->softDeleteExactNombre('OTRAS ESPECIALIDADES', $summary);

            return $summary;
        });
    }

    /**
     * @param  array<int, string>  $nombres
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    private function ensureCodigoExactNombre(array $nombres, string $codigo, array &$summary): void
    {
        $needles = array_map(fn ($n) => $this->normalize($n), $nombres);
        $rows = EspecialidadMedica::query()->get()
            ->filter(fn (EspecialidadMedica $e) => in_array($this->normalize($e->nombre), $needles, true));

        foreach ($rows as $row) {
            if ((string) $row->codigo === $codigo) {
                $summary['details'][] = "#{$row->id}: «{$row->nombre}» ya tiene codigo {$codigo}";
                continue;
            }
            $row->codigo = $codigo;
            $row->orden = is_numeric($codigo) ? (int) $codigo : $row->orden;
            $row->activo = true;
            $row->save();
            $summary['codigo_only']++;
            $summary['details'][] = "#{$row->id}: «{$row->nombre}» ← codigo {$codigo}";
        }
    }

    /**
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    private function ensureEspecialidad(string $nombre, string $codigo, array &$summary): void
    {
        $existing = EspecialidadMedica::query()->get()
            ->first(fn (EspecialidadMedica $e) => $this->normalize($e->nombre) === $this->normalize($nombre));

        if ($existing) {
            if ((string) $existing->codigo !== $codigo) {
                $existing->codigo = $codigo;
                $existing->orden = is_numeric($codigo) ? (int) $codigo : $existing->orden;
                $existing->activo = true;
                $existing->save();
                $summary['codigo_only']++;
                $summary['details'][] = "#{$existing->id}: «{$existing->nombre}» ← codigo {$codigo}";
            } else {
                $summary['details'][] = "#{$existing->id}: «{$nombre}» ya existe (codigo {$codigo})";
            }

            return;
        }

        $created = EspecialidadMedica::query()->create([
            'codigo' => $codigo,
            'nombre' => $nombre,
            'nombre_normalizado' => $this->normalize($nombre),
            'especialidad_base' => $nombre,
            'orden' => is_numeric($codigo) ? (int) $codigo : 0,
            'activo' => true,
        ]);
        $summary['renamed']++;
        $summary['details'][] = "#{$created->id}: creada «{$nombre}» (codigo {$codigo})";
    }

    /**
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    private function softDeleteExactNombre(string $nombre, array &$summary): void
    {
        $rows = EspecialidadMedica::query()->get()
            ->filter(fn (EspecialidadMedica $e) => $this->normalize($e->nombre) === $this->normalize($nombre));

        foreach ($rows as $row) {
            DetalleCatalogoItem::query()
                ->where('catalogo_tipo', CatalogType::EspecialidadMedica->value)
                ->where('catalogo_item_id', $row->id)
                ->delete();
            $row->delete();
            $summary['soft_deleted']++;
            $summary['details'][] = "#{$row->id}: eliminada «{$nombre}»";
        }
    }

    /**
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    private function psiquiatriaOnlyCodigo(array &$summary): void
    {
        $psiquiatria = $this->findByNombreAny('PSIQUIATRÍA')
            ->filter(fn (EspecialidadMedica $e) => $this->normalize($e->nombre) === 'psiquiatria' && ! $e->trashed())
            ->values();

        foreach ($psiquiatria as $row) {
            $row->codigo = '7';
            $row->orden = 7;
            $row->activo = true;
            $row->save();
            $summary['codigo_only']++;
            $summary['details'][] = "#{$row->id}: PSIQUIATRÍA ← codigo 7 (nombre conservado)";
        }

        foreach ($this->findByNombreAny('SIQUIATRIA') as $dup) {
            if ($dup->trashed() || $this->normalize($dup->nombre) !== 'siquiatria') {
                continue;
            }
            $keep = $psiquiatria->first();
            if ($keep) {
                $this->mergeInto($dup, $keep, $summary);
                $summary['details'][] = "#{$dup->id} SIQUIATRIA → #{$keep->id} PSIQUIATRÍA (codigo 7)";
            } else {
                $dup->nombre = 'PSIQUIATRÍA';
                $dup->nombre_normalizado = 'psiquiatria';
                $dup->especialidad_base = 'PSIQUIATRÍA';
                $dup->codigo = '7';
                $dup->orden = 7;
                $dup->save();
                $summary['renamed']++;
                $summary['details'][] = "#{$dup->id}: SIQUIATRIA → PSIQUIATRÍA (codigo 7)";
            }
        }
    }

    /**
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    private function renameOrMerge(string $fromNombre, string $toNombre, string $codigo, array &$summary): void
    {
        foreach ($this->findByNombreAny($fromNombre) as $from) {
            if ($from->trashed() || $this->normalize($from->nombre) !== $this->normalize($fromNombre)) {
                continue;
            }

            $target = EspecialidadMedica::query()->get()
                ->first(fn (EspecialidadMedica $e) => $this->normalize($e->nombre) === $this->normalize($toNombre));

            if ($target && $target->id !== $from->id) {
                $target->codigo = $codigo;
                $target->orden = is_numeric($codigo) ? (int) $codigo : $target->orden;
                $target->nombre_normalizado = $this->normalize($toNombre);
                $target->activo = true;
                $target->save();
                $this->mergeInto($from, $target, $summary);
                $summary['details'][] = "#{$from->id} «{$fromNombre}» → #{$target->id} «{$toNombre}» (codigo {$codigo})";
                continue;
            }

            $from->nombre = $toNombre;
            $from->nombre_normalizado = $this->normalize($toNombre);
            $from->especialidad_base = $toNombre;
            $from->codigo = $codigo;
            $from->orden = is_numeric($codigo) ? (int) $codigo : $from->orden;
            $from->activo = true;
            $from->save();
            $summary['renamed']++;
            $summary['details'][] = "#{$from->id}: «{$fromNombre}» → «{$toNombre}» (codigo {$codigo})";
        }
    }

    /**
     * @param  array{merged: int, renamed: int, codigo_only: int, soft_deleted: int, details: array<int, string>}  $summary
     */
    private function mergeInto(EspecialidadMedica $from, EspecialidadMedica $into, array &$summary): void
    {
        if ($from->id === $into->id) {
            return;
        }

        $type = CatalogType::EspecialidadMedica->value;
        foreach (DetalleCatalogoItem::withTrashed()->where('catalogo_tipo', $type)->where('catalogo_item_id', $from->id)->get() as $bridge) {
            $exists = DetalleCatalogoItem::withTrashed()
                ->where('variable_detalle_id', $bridge->variable_detalle_id)
                ->where('catalogo_tipo', $type)
                ->where('catalogo_item_id', $into->id)
                ->first();
            if ($exists) {
                $bridge->forceDelete();
            } else {
                $bridge->catalogo_item_id = $into->id;
                if ($bridge->trashed()) {
                    $bridge->restore();
                }
                $bridge->save();
            }
        }

        EspecialidadMedica::withTrashed()
            ->where('especialidad_base_id', $from->id)
            ->update(['especialidad_base_id' => $into->id]);

        $from->forceDelete();
        $summary['soft_deleted']++;
        $summary['merged']++;
    }

    /** @return \Illuminate\Support\Collection<int, EspecialidadMedica> */
    private function findByNombreAny(string $nombre)
    {
        $needle = $this->normalize($nombre);

        return EspecialidadMedica::withTrashed()
            ->get()
            ->filter(fn (EspecialidadMedica $e) => $this->normalize($e->nombre) === $needle
                || $this->normalize((string) $e->nombre_normalizado) === $needle)
            ->values();
    }

    private function normalize(string $value): string
    {
        return Str::lower(Str::ascii(trim($value)));
    }
}
