<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Microred extends BioestadisticaModel
{
    protected $table = 'bioestadistica.microredes';

    protected $casts = ['activo' => 'boolean'];

    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class);
    }
}
