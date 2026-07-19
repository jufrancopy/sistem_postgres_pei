<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Globales\Organigrama;
use App\Models\User;
use App\Models\InstitucionParaguay;

class PeiActor extends Model
{
    protected $table = 'planificacion.pei_actores';

    protected $fillable = [
        'pei_profile_id', 'tipo',
        'organigrama_id', 'user_id',
        'institucion_id', 'dependencia_externa', 'persona_referente', 'email_externo',
        'aportes', 'orden',
    ];

    public function organigrama()
    {
        return $this->belongsTo(Organigrama::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institucion()
    {
        return $this->belongsTo(InstitucionParaguay::class, 'institucion_id');
    }

    public function getDependenciaLabelAttribute(): string
    {
        if ($this->tipo === 'interno') {
            return $this->organigrama?->dependency ?? '—';
        }
        return $this->institucion?->nombre ?? $this->dependencia_externa ?? '—';
    }

    public function getPersonaLabelAttribute(): string
    {
        return $this->tipo === 'interno'
            ? ($this->user?->name ?? '—')
            : ($this->persona_referente ?? '—');
    }
}
