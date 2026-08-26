<?php

namespace App\Models\Estructura;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAjusteEstructuraItem extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_ajuste_estructura_items';

    protected $fillable = [
        'solicitud_id',
        'tipo_reorganizacion',
        'denominacion_actual',
        'denominacion_propuesta',
        'objetivo_dependencia_propuesta',
        'descripcion_motivos',
        'observaciones',
        'orden',
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudAjusteEstructura::class, 'solicitud_id');
    }

    public function getTipoLabelAttribute(): string
    {
        return SolicitudAjusteEstructura::TIPOS_REORGANIZACION[$this->tipo_reorganizacion] ?? $this->tipo_reorganizacion;
    }
}
