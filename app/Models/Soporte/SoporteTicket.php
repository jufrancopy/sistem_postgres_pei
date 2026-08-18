<?php

namespace App\Models\Soporte;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;

class SoporteTicket extends Model
{
    protected $table = 'soporte_tickets';

    protected $fillable = [
        'codigo',
        'user_id',
        'pei_profile_id',
        'titulo',
        'descripcion',
        'url_origen',
        'ruta_origen',
        'nodo_contexto',
        'prioridad',
        'estado',
        'respuesta_admin',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public static function generarCodigo(): string
    {
        $anio = date('Y');
        $ultimo = static::whereYear('created_at', $anio)->max('id') ?? 0;
        $num = $ultimo + 1;
        return 'TK-' . $anio . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
