<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariableDetalle extends BioestadisticaModel
{
    protected $table = 'bioestadistica.variable_detalles';

    protected $casts = ['activo' => 'boolean'];

    public function variable(): BelongsTo
    {
        return $this->belongsTo(Variable::class);
    }

    public function prestaciones(): HasMany
    {
        return $this->hasMany(Prestacion::class, 'detalle_id')->orderBy('orden')->orderBy('nombre');
    }
}
