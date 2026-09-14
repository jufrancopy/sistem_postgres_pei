<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganoTipo extends BioestadisticaModel
{
    protected $table = 'bioestadistica.organo_tipos';

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function organos(): HasMany
    {
        return $this->hasMany(Organo::class, 'tipo_id')->orderBy('orden')->orderBy('nombre');
    }
}
