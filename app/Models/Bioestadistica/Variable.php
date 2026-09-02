<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Variable extends BioestadisticaModel
{
    protected $table = 'bioestadistica.variables';

    protected $casts = ['activo' => 'boolean'];

    public function detalles(): HasMany
    {
        return $this->hasMany(VariableDetalle::class)->orderBy('orden')->orderBy('nombre');
    }

    public function etiqueta(): string
    {
        return "{$this->codigo} — {$this->nombre}";
    }
}
