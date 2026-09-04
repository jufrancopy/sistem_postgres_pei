<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Collection;

class CatalogItemResolver
{
    /**
     * @return Collection<int, object{id: int, label: string, activo: bool, catalogo_tipo: string}>
     */
    public function rowsForDetalle(?VariableDetalle $detalle): Collection
    {
        if (! $detalle) {
            return collect();
        }

        $bridges = DetalleCatalogoItem::query()
            ->where('variable_detalle_id', $detalle->id)
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        if ($bridges->isEmpty()) {
            return collect();
        }

        return $bridges->map(function (DetalleCatalogoItem $bridge) {
            $item = $bridge->resolveCatalogItem();

            return (object) [
                'id' => (int) $bridge->catalogo_item_id,
                'label' => $item?->nombre ?? ('Ítem #'.$bridge->catalogo_item_id),
                'activo' => (bool) ($item?->activo ?? true),
                'catalogo_tipo' => $bridge->catalogo_tipo,
            ];
        })->filter(fn ($row) => $row->activo)->values();
    }
}
