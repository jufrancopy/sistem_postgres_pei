<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicador extends BioestadisticaModel
{
    protected $table = 'bioestadistica.indicadores';

    protected $casts = [
        'activo' => 'boolean',
        'decimales' => 'integer',
    ];

    public function formulas(): HasMany
    {
        return $this->hasMany(IndicadorFormula::class)->orderByDesc('vigente_desde')->orderByDesc('id');
    }

    public function cache(): HasMany
    {
        return $this->hasMany(IndicadorCache::class);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
