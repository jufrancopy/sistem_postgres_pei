<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\HasMasterCatalogFields;

class Vacuna extends BioestadisticaModel
{
    use HasMasterCatalogFields;

    protected $table = 'bioestadistica.vacunas';

    protected $casts = [
        'activo' => 'boolean',
        'requiere_lote' => 'boolean',
        'meta' => 'array',
    ];
}
