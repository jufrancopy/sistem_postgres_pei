<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaGestion extends BioestadisticaModel
{
    protected $table = 'bioestadistica.areas_gestion';

    protected $casts = ['activo' => 'boolean'];

    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class);
    }
}
