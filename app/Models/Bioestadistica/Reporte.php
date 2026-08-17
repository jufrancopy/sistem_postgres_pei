<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends BioestadisticaModel
{
    protected $table = 'bioestadistica.reportes';

    protected $casts = [
        'definicion' => 'array',
        'publico' => 'boolean',
    ];

    public function formulario(): BelongsTo
    {
        return $this->belongsTo(Formulario::class);
    }
}
