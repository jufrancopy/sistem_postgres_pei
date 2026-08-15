<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class GradoComplejidad extends BioestadisticaModel
{
    protected $table = 'bioestadistica.grados_complejidad';

    protected $casts = ['activo' => 'boolean'];

    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class);
    }
}
