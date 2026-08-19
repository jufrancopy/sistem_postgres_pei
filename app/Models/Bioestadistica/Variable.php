<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Variable extends BioestadisticaModel
{
    protected $table = 'bioestadistica.variables';

    protected $casts = ['activo' => 'boolean'];

    public function detalles(): HasMany
    {
        return $this->hasMany(VariableDetalle::class)->orderBy('orden')->orderBy('nombre');
    }

    public function prestaciones(): HasManyThrough
    {
        return $this->hasManyThrough(Prestacion::class, VariableDetalle::class, 'variable_id', 'detalle_id');
    }

    public function etiqueta(): string
    {
        return "{$this->codigo} — {$this->nombre}";
    }
}
