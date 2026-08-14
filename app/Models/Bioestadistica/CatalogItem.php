<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogItem extends BioestadisticaModel
{
    protected $table = 'bioestadistica.catalog_items';

    protected $casts = [
        'activo' => 'boolean',
        'meta' => 'array',
    ];

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class);
    }
}
