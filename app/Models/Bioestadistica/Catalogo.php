<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalogo extends BioestadisticaModel
{
    protected $table = 'bioestadistica.catalogos';

    protected $casts = ['activo' => 'boolean'];

    public function items(): HasMany
    {
        return $this->hasMany(CatalogItem::class)->orderBy('orden');
    }
}
