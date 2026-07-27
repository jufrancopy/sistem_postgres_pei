<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;

class GamificationPoint extends Model
{
    protected $table = 'gamification_points';

    protected $fillable = [
        'user_id',
        'pei_profile_id',
        'points',
        'action_type',
        'description',
        'reference_type',
        'reference_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function getActionTypeLabel()
    {
        $labels = [
            'task_created'       => 'Tarea creada',
            'task_completed'     => 'Tarea completada',
            'comment_created'    => 'Comentario creado',
            'foda_analisis'      => 'Análisis FODA',
            'foda_cruce'         => 'Cruce FODA',
            'riiss_asignacion'   => 'Asignación RIISS',
            'riiss_evaluacion'   => 'Evaluación RIISS',
            'riiss_cumplimiento' => 'Cumplimiento RIISS',
        ];
        return $labels[$this->action_type] ?? ucfirst(str_replace('_', ' ', $this->action_type));
    }
}
