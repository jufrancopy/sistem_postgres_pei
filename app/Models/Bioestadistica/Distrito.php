<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distrito extends BioestadisticaModel
{
    protected $table = 'bioestadistica.distritos';

    protected $casts = ['activo' => 'boolean'];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class);
    }
}
