<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstablecimientoOrgano extends BioestadisticaModel
{
    protected $table = 'bioestadistica.establecimiento_organos';

    protected $casts = [
        'es_principal' => 'boolean',
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function organo(): BelongsTo
    {
        return $this->belongsTo(Organo::class);
    }
}
