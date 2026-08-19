<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestacion extends BioestadisticaModel
{
    protected $table = 'bioestadistica.prestaciones';

    protected $casts = ['activo' => 'boolean'];

    public function detalle(): BelongsTo
    {
        return $this->belongsTo(VariableDetalle::class, 'detalle_id');
    }
}
