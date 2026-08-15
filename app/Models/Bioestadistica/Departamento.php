<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends BioestadisticaModel
{
    protected $table = 'bioestadistica.departamentos';

    protected $casts = ['activo' => 'boolean'];

    public function distritos(): HasMany
    {
        return $this->hasMany(Distrito::class);
    }
}
