<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organo extends BioestadisticaModel
{
    protected $table = 'bioestadistica.organos';

    protected $casts = [
        'activo' => 'boolean',
        'es_jerarquico' => 'boolean',
        'orden' => 'integer',
        'fuente_pagina' => 'integer',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(OrganoTipo::class, 'tipo_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('orden')->orderBy('nombre');
    }

    public function establecimientoLinks(): HasMany
    {
        return $this->hasMany(EstablecimientoOrgano::class, 'organo_id');
    }

    public function scopeRaices(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
