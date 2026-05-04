<?php

namespace App\Models\Estadistica;

use App\Admin\Globales\Organigrama;

/**
 * Trait reutilizable para todos los modelos SIESS que tienen
 * organigrama_id y locality_id (territorialización).
 */
trait SiessTerritorialTrait
{
    public function organigrama()
    {
        return $this->belongsTo(Organigrama::class, 'organigrama_id');
    }

    public function locality()
    {
        return $this->belongsTo(\App\Models\Backend\Locality::class, 'locality_id');
    }

    /**
     * Scope: filtrar por establecimiento específico
     */
    public function scopeDelEstablecimiento($query, int $organigramaId)
    {
        return $query->where('organigrama_id', $organigramaId);
    }

    /**
     * Scope: filtrar solo establecimientos con AOP
     */
    public function scopeConAop($query)
    {
        return $query->whereHas('organigrama', fn($q) => $q->where('tiene_aop', true));
    }

    /**
     * Scope: filtrar por tipo de tenencia (PROPIO, CONVENIO, TERCERIZADO)
     */
    public function scopePorTenencia($query, string $tenencia)
    {
        return $query->whereHas('organigrama', fn($q) => $q->where('tenencia', $tenencia));
    }

    /**
     * Scope: filtrar por región
     */
    public function scopePorRegion($query, string $region)
    {
        return $query->whereHas('organigrama', fn($q) => $q->where('region', $region));
    }

    /**
     * Scope: filtrar por nivel de complejidad del establecimiento
     */
    public function scopePorNivel($query, string $nivel)
    {
        return $query->whereHas('organigrama', fn($q) => $q->where('nivel_complejidad', $nivel));
    }
}
