<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariableDetalle extends BioestadisticaModel
{
    protected $table = 'bioestadistica.variable_detalles';

    protected $casts = [
        'activo' => 'boolean',
        'meta' => 'array',
    ];

    public function variable(): BelongsTo
    {
        return $this->belongsTo(Variable::class);
    }

    public function catalogoItems(): HasMany
    {
        return $this->hasMany(DetalleCatalogoItem::class, 'variable_detalle_id')->orderBy('orden');
    }
}
