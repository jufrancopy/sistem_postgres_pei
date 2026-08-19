<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class EstructuraDepartamento extends BioestadisticaModel
{
    protected $table = 'bioestadistica.estructura_departamentos';

    protected $casts = ['activo' => 'boolean'];

    public function servicios(): HasMany
    {
        return $this->hasMany(EstructuraServicio::class, 'departamento_id')->orderBy('nombre');
    }
}
