<?php

namespace App\Application\Bioestadistica\Organigrama;

use App\Models\Bioestadistica\Organo;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * Cortes de captura: dependencias bajo el ancla del establecimiento.
 */
class OrganoCorteService
{
    /** @var list<string> */
    private const TIPOS_TOPE_RUTA = ['direccion', 'direccion_regional', 'gerencia'];

    /**
     * @return Collection<int, Organo>
     */
    public function anclas(int $establecimientoId): Collection
    {
        return Organo::query()
            ->whereHas('establecimientoLinks', fn ($q) => $q->where('establecimiento_id', $establecimientoId))
            ->with('tipo')
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * @return Collection<int, Organo>
     */
    public function candidatos(int $establecimientoId): Collection
    {
        $anclas = $this->anclas($establecimientoId);
        if ($anclas->isEmpty()) {
            return collect();
        }

        $subtreeIds = $this->collectSubtreeIds($anclas->pluck('id')->all());

        return Organo::query()
            ->with([
                'tipo',
                'parent.tipo',
                'parent.parent.tipo',
                'parent.parent.parent.tipo',
                'parent.parent.parent.parent.tipo',
            ])
            ->whereIn('id', $subtreeIds)
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Opciones del desplegable: todo el subárbol con etiqueta legible.
     *
     * @return Collection<int, array{id: int, label: string, tipo: string, tipo_codigo: string}>
     */
    public function opcionesParaEstablecimiento(int $establecimientoId): Collection
    {
        $anclaIds = $this->anclas($establecimientoId)->pluck('id')->map(fn ($id) => (int) $id)->all();

        return $this->candidatos($establecimientoId)
            ->map(fn (Organo $organo) => [
                'id' => (int) $organo->id,
                'label' => $this->etiqueta($organo, $anclaIds),
                'tipo' => (string) ($organo->tipo?->nombre ?? ''),
                'tipo_codigo' => (string) ($organo->tipo?->codigo ?? ''),
            ])
            ->sortBy(fn (array $item) => mb_strtolower($item['label']), SORT_NATURAL)
            ->values();
    }

    /**
     * Primer nivel (pendientes / resumen).
     *
     * @return Collection<int, array{id: int, label: string, tipo: string, tipo_codigo: string}>
     */
    public function opcionesRaizParaEstablecimiento(int $establecimientoId): Collection
    {
        $anclaIds = $this->anclas($establecimientoId)->pluck('id')->map(fn ($id) => (int) $id)->all();

        return $this->hijosRaiz($establecimientoId)
            ->map(fn (Organo $organo) => [
                'id' => (int) $organo->id,
                'label' => $this->etiqueta($organo, $anclaIds),
                'tipo' => (string) ($organo->tipo?->nombre ?? ''),
                'tipo_codigo' => (string) ($organo->tipo?->codigo ?? ''),
            ])
            ->sortBy(fn (array $item) => mb_strtolower($item['label']), SORT_NATURAL)
            ->values();
    }

    public function assertSeleccion(int $establecimientoId, mixed $organoId): ?Organo
    {
        $candidatos = $this->candidatos($establecimientoId);
        if ($candidatos->isEmpty()) {
            return null;
        }

        $id = (int) ($organoId ?? 0);
        if ($id < 1) {
            throw ValidationException::withMessages([
                'organo_id' => 'Seleccione una dependencia del organigrama vinculado al establecimiento.',
            ]);
        }

        $match = $candidatos->firstWhere('id', $id);
        if (! $match) {
            throw ValidationException::withMessages([
                'organo_id' => 'La dependencia no pertenece al organigrama del establecimiento.',
            ]);
        }

        return $match;
    }

    /**
     * @param  list<int>|null  $anclaIds
     */
    public function etiqueta(Organo $organo, ?array $anclaIds = null): string
    {
        $chain = [];
        $node = $organo;
        $guard = 0;
        while ($node && $guard < 8) {
            $codigo = (string) ($node->tipo?->codigo ?? '');
            array_unshift($chain, [
                'nombre' => (string) $node->nombre,
                'codigo' => $codigo,
                'id' => (int) $node->id,
            ]);
            if ($anclaIds !== null && in_array((int) $node->id, $anclaIds, true)) {
                break;
            }
            if (in_array($codigo, self::TIPOS_TOPE_RUTA, true)) {
                break;
            }
            if (! $node->relationLoaded('parent')) {
                $node->load('parent.tipo');
            }
            $node = $node->parent;
            $guard++;
        }

        while (count($chain) > 1 && in_array($chain[0]['codigo'], self::TIPOS_TOPE_RUTA, true)) {
            array_shift($chain);
        }

        return collect($chain)->pluck('nombre')->implode(' › ');
    }

    /**
     * @return Collection<int, Organo>
     */
    private function hijosRaiz(int $establecimientoId): Collection
    {
        $anclas = $this->anclas($establecimientoId);
        if ($anclas->isEmpty()) {
            return collect();
        }

        if ($anclas->count() === 1) {
            return $this->hijosDe((int) $anclas->first()->id);
        }

        return $anclas;
    }

    /**
     * @return Collection<int, Organo>
     */
    private function hijosDe(int $parentId): Collection
    {
        return Organo::query()
            ->with([
                'tipo',
                'parent.tipo',
                'parent.parent.tipo',
                'parent.parent.parent.tipo',
            ])
            ->where('parent_id', $parentId)
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * @param  list<int>  $rootIds
     * @return list<int>
     */
    private function collectSubtreeIds(array $rootIds): array
    {
        $seen = [];
        $queue = $rootIds;
        while ($queue !== []) {
            $id = (int) array_shift($queue);
            if (isset($seen[$id])) {
                continue;
            }
            $seen[$id] = true;
            $children = Organo::query()
                ->where('parent_id', $id)
                ->where('activo', true)
                ->pluck('id')
                ->all();
            foreach ($children as $childId) {
                $queue[] = (int) $childId;
            }
        }

        return array_map('intval', array_keys($seen));
    }
}
