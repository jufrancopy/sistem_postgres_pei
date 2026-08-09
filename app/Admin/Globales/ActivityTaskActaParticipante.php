<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityTaskActaParticipante extends Model
{
    protected $table = 'activity_task_acta_participantes';

    protected $fillable = [
        'acta_id',
        'nombre',
        'apellido',
        'correo',
        'dependencia',
        'cargo',
        'telefono',
        'asistio',
        'registrado_via_qr',
        'firma',
        'ip_address',
        'user_agent',
        'user_id',
    ];

    protected $casts = [
        'asistio'           => 'boolean',
        'registrado_via_qr' => 'boolean',
    ];

    public function acta()
    {
        return $this->belongsTo(ActivityTaskActa::class, 'acta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
