<?php

namespace App\Models\Mecip;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class MecipCaso extends Model
{
    use SoftDeletes;

    protected $table = 'mecip_casos';

    protected $fillable = [
        'numero_caso',
        'codigo_subproceso',
        'macroproceso',
        'proceso',
        'subproceso',
        'version',
        'fecha_elaboracion',
        'responsable_analisis',
        'lider_mecip_id',
        'created_by',
        'estado_flujo',
        'dictamen_final',
        'automatico_flag',
    ];

    protected $casts = [
        'fecha_elaboracion' => 'date',
        'automatico_flag'   => 'boolean',
    ];

    public function liderMecip()
    {
        return $this->belongsTo(User::class, 'lider_mecip_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function componentes()
    {
        return $this->hasMany(MecipCasoComponente::class, 'mecip_caso_id')->orderBy('orden');
    }

    public function insumos()
    {
        return $this->hasMany(MecipCasoComponente::class, 'mecip_caso_id')->where('tipo', 'insumo')->orderBy('orden');
    }

    public function productos()
    {
        return $this->hasMany(MecipCasoComponente::class, 'mecip_caso_id')->where('tipo', 'producto')->orderBy('orden');
    }

    public function actividades()
    {
        return $this->hasMany(MecipCasoActividad::class, 'mecip_caso_id')->orderBy('orden');
    }

    public function comentarios()
    {
        return $this->hasMany(MecipCasoComentario::class, 'mecip_caso_id')->latest();
    }

    public function cambios()
    {
        return $this->hasMany(MecipCasoCambio::class, 'mecip_caso_id')->latest();
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado_flujo) {
            'borrador'       => 'Borrador / Carga Inicial',
            'remitido_lider' => 'Remitido a Líder MECIP',
            'resuelto_lider' => 'Resuelto con Justificación',
            'cerrado_admin'  => 'Aprobado y Cerrado',
            default          => ucfirst($this->estado_flujo),
        };
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado_flujo) {
            'borrador'       => 'badge-secondary',
            'remitido_lider' => 'badge-warning text-dark',
            'resuelto_lider' => 'badge-info',
            'cerrado_admin'  => 'badge-success',
            default          => 'badge-primary',
        };
    }
}
