<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\EspecialidadMedica;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Consolida especialidades_medicas a 1 fila por código (o por nombre si no hay código).
 */
class EspecialidadesCatalogNormalizer
{
    /**
     * @return array{
     *     keepers: int,
     *     merged: int,
     *     deleted: int,
     *     bridges_repointed: int,
     *     bridges_removed_dup: int,
     *     details: array<int, string>
     * }
     */
    public function normalize(): array
    {
        $summary = [
            'keepers' => 0,
            'merged' => 0,
            'deleted' => 0,
            'bridges_repointed' => 0,
            'bridges_removed_dup' => 0,
            'details' => [],
        ];

        return DB::connection('pgsql')->transaction(function () use (&$summary) {
            $all = EspecialidadMedica::withTrashed()->orderBy('id')->get();

            // Grupos: por codigo si existe; si no, por nombre_normalizado.
            $groups = $all->groupBy(function (EspecialidadMedica $e) {
                if ($e->codigo !== null && $e->codigo !== '') {
                    return 'c:'.(string) $e->codigo;
                }

                return 'n:'.$this->normNombre((string) ($e->nombre_normalizado ?: $e->nombre));
            });

            foreach ($groups as $key => $group) {
                /** @var Collection<int, EspecialidadMedica> $group */
                $keeper = $this->pickKeeper($group);
                $summary['keepers']++;

                foreach ($group as $dup) {
                    if ($dup->id === $keeper->id) {
                        continue;
                    }
                    $this->mergeInto($dup, $keeper, $summary);
                    $summary['details'][] = "#{$dup->id} → #{$keeper->id} ({$keeper->codigo}|{$keeper->nombre})";
                }

                // Normalizar campos del keeper
                if ($keeper->trashed()) {
                    $keeper->restore();
                }
                $keeper->nombre_normalizado = $this->normNombre((string) $keeper->nombre);
                if ($keeper->especialidad_base === null || $keeper->especialidad_base === '') {
                    $keeper->especialidad_base = $keeper->nombre;
                }
                $keeper->activo = true;
                $keeper->save();
            }

            // Segunda pasada: 1 fila por nombre_normalizado (acentos / códigos distintos residuales)
            $this->consolidateByNormalizedName($summary);

            return $summary;
        });
    }

    /**
     * @param  array{keepers: int, merged: int, deleted: int, bridges_repointed: int, bridges_removed_dup: int, details: array<int, string>}  $summary
     */
    private function consolidateByNormalizedName(array &$summary): void
    {
        $groups = EspecialidadMedica::query()
            ->orderBy('id')
            ->get()
            ->groupBy(fn (EspecialidadMedica $e) => $this->normNombre((string) ($e->nombre_normalizado ?: $e->nombre)));

        foreach ($groups as $group) {
            if ($group->count() < 2) {
                continue;
            }
            $keeper = $this->pickKeeper($group);
            foreach ($group as $dup) {
                if ($dup->id === $keeper->id) {
                    continue;
                }
                $this->mergeInto($dup, $keeper, $summary);
                $summary['details'][] = "nombre #{$dup->id} → #{$keeper->id} ({$keeper->nombre})";
            }
        }
    }

    /**
     * @param  Collection<int, EspecialidadMedica>  $group
     */
    private function pickKeeper(Collection $group): EspecialidadMedica
    {
        // Preferir no eliminados
        $active = $group->filter(fn (EspecialidadMedica $e) => ! $e->trashed());
        $pool = $active->isNotEmpty() ? $active : $group;

        // Preferir contexto ambulatorio si aún existe la columna
        if ($this->hasContextoColumn()) {
            $amb = $pool->first(fn (EspecialidadMedica $e) => ($e->getAttribute('contexto') ?? null) === 'ambulatorio');
            if ($amb) {
                return $amb;
            }
        }

        // Preferir el de menor id
        return $pool->sortBy('id')->first();
    }

    private function hasContextoColumn(): bool
    {
        static $has = null;
        if ($has === null) {
            $has = \Illuminate\Support\Facades\Schema::connection('pgsql')
                ->hasColumn('bioestadistica.especialidades_medicas', 'contexto');
        }

        return $has;
    }

    /**
     * @param  array{keepers: int, merged: int, deleted: int, bridges_repointed: int, bridges_removed_dup: int, details: array<int, string>}  $summary
     */
    private function mergeInto(EspecialidadMedica $from, EspecialidadMedica $into, array &$summary): void
    {
        $type = CatalogType::EspecialidadMedica->value;

        $bridges = DetalleCatalogoItem::withTrashed()
            ->where('catalogo_tipo', $type)
            ->where('catalogo_item_id', $from->id)
            ->get();

        foreach ($bridges as $bridge) {
            $exists = DetalleCatalogoItem::withTrashed()
                ->where('variable_detalle_id', $bridge->variable_detalle_id)
                ->where('catalogo_tipo', $type)
                ->where('catalogo_item_id', $into->id)
                ->first();

            if ($exists) {
                if ($exists->trashed()) {
                    $exists->restore();
                }
                $bridge->forceDelete();
                $summary['bridges_removed_dup']++;
            } else {
                $bridge->catalogo_item_id = $into->id;
                if ($bridge->trashed()) {
                    $bridge->restore();
                }
                $bridge->activo = true;
                $bridge->save();
                $summary['bridges_repointed']++;
            }
        }

        EspecialidadMedica::withTrashed()
            ->where('especialidad_base_id', $from->id)
            ->update(['especialidad_base_id' => $into->id]);

        $from->forceDelete();
        $summary['merged']++;
        $summary['deleted']++;
    }

    private function normNombre(string $value): string
    {
        return Str::lower(Str::ascii(trim($value)));
    }
}
