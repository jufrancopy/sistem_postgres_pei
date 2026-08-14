<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEstablecimiento extends BioestadisticaModel
{
    protected $table = 'bioestadistica.tipos_establecimiento';

    protected $casts = ['activo' => 'boolean'];

    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class);
    }
}
