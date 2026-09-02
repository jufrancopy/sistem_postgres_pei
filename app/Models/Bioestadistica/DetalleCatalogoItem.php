<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCatalogoItem extends BioestadisticaModel
{
    protected $table = 'bioestadistica.detalle_catalogo_items';

    public $timestamps = true;

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function detalle(): BelongsTo
    {
        return $this->belongsTo(VariableDetalle::class, 'variable_detalle_id');
    }

    public function resolveCatalogItem(): ?BioestadisticaModel
    {
        $class = \App\Application\Bioestadistica\Dictionary\CatalogType::tryFrom($this->catalogo_tipo)?->modelClass();

        return $class ? $class::find($this->catalogo_item_id) : null;
    }
}
