<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Planificacion\Pei\PeiProfile;

class PeiAccionPgn extends Model
{
    protected $table = 'planificacion.pei_accion_pgn';

    protected $fillable = [
        'pei_profile_id', 'pgn_nodo_id',
        'resultado', 'monto_vinculado_gs', 'monto_ejecutado_gs',
    ];

    protected $casts = [
        'monto_vinculado_gs'  => 'decimal:2',
        'monto_ejecutado_gs'  => 'decimal:2',
    ];

    public function accion()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function nodo()
    {
        return $this->belongsTo(PgnNodo::class, 'pgn_nodo_id')->with('estructura');
    }

    // % de ejecución presupuestaria
    public function getPctEjecucionAttribute(): ?float
    {
        if (!$this->monto_vinculado_gs || $this->monto_vinculado_gs == 0) return null;
        return round(($this->monto_ejecutado_gs / $this->monto_vinculado_gs) * 100, 1);
    }

    // Semáforo: verde ≥85%, amarillo ≥50%, rojo <50%
    public function getSemaforoAttribute(): string
    {
        $pct = $this->pct_ejecucion;
        if ($pct === null) return 'sin-datos';
        if ($pct >= 85)   return 'verde';
        if ($pct >= 50)   return 'amarillo';
        return 'rojo';
    }
}
