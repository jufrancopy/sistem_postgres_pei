<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;

class RelevamientoProceso extends Model
{
    use HasUuids;

    protected $table = 'planificacion.relevamiento_procesos';

    protected $fillable = [
        'nombre',
        'contexto_motivo',
        'pei_profile_id',
        'organigrama_id',
        'tipo_relevamiento',
        'estado',
        'fecha_relevamiento',
        'objetivo',
        'analisis_ia',
        'participantes_externos',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_relevamiento' => 'date',
        'participantes_externos' => 'array',
    ];

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function organigrama()
    {
        return $this->belongsTo(Organigrama::class, 'organigrama_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responsables()
    {
        return $this->belongsToMany(User::class, 'planificacion.relevamiento_proceso_responsables', 'relevamiento_proceso_id', 'user_id')
                    ->withPivot('rol_visita', 'firma_digital', 'firmado_at', 'observaciones_firma');
    }

    public function pasos()
    {
        return $this->hasMany(RelevamientoPaso::class, 'relevamiento_proceso_id')->orderBy('orden', 'asc');
    }

    public function getTiempoAtencionTotalAttribute()
    {
        return $this->pasos->sum('tiempo_atencion_min');
    }

    public function getTiempoEsperaTotalAttribute()
    {
        return $this->pasos->sum('tiempo_espera_min');
    }

    public function getTiempoTrasladoTotalAttribute()
    {
        return $this->pasos->sum('tiempo_traslado_min');
    }

    public function getLeadTimeTotalAttribute()
    {
        return $this->tiempo_atencion_total + $this->tiempo_espera_total + $this->tiempo_traslado_total;
    }

    public function getEficienciaAttribute()
    {
        $total = $this->lead_time_total;
        if ($total == 0) return 100;
        return round(($this->tiempo_atencion_total / $total) * 100, 1);
    }

    public function getConteoCuellosBotellaAttribute()
    {
        return $this->pasos->where('es_cuello_botella', true)->count();
    }
}
