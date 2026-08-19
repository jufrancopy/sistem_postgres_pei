<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstructuraServicio extends BioestadisticaModel
{
    protected $table = 'bioestadistica.estructura_servicios';

    protected $casts = ['activo' => 'boolean'];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(EstructuraDepartamento::class, 'departamento_id');
    }
}
