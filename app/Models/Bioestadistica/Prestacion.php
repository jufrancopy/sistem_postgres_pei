<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\HasMasterCatalogFields;

class Prestacion extends BioestadisticaModel
{
    use HasMasterCatalogFields;

    protected $table = 'bioestadistica.prestaciones';

    protected $casts = [
        'activo' => 'boolean',
        'es_indicador' => 'boolean',
        'meta' => 'array',
    ];
}
