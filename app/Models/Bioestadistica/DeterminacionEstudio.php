<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\HasMasterCatalogFields;

class DeterminacionEstudio extends BioestadisticaModel
{
    use HasMasterCatalogFields;

    protected $table = 'bioestadistica.determinaciones_estudios';

    protected $casts = [
        'activo' => 'boolean',
        'es_agregado' => 'boolean',
        'meta' => 'array',
    ];
}
