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
            'daily_login'        => 'Acceso diario',
            'login_streak'       => 'Racha de accesos',
            'manual_admin'       => '⭐ Asignación manual',
            'donacion_enviada'   => 'Donación enviada',
            'donacion_recibida'  => 'Donación recibida',
            'chat_message'       => 'Aporte en el chat',
            'chat_context_query' => 'Consulta vinculada en chat',
            'pei_edit'           => 'Edición de elemento PEI',
            'git_commit'         => '🚀 Desarrollo & Aporte de Código',
            'reporte_falla'      => '🛠️ Reporte de Falla / Mejora Técnica',
        ];
        return $labels[$this->action_type] ?? ucfirst(str_replace('_', ' ', $this->action_type));
    }

    public function isReferenceValid(): bool
    {
        if (!$this->reference_type || !$this->reference_id) {
            return true;
        }

        if ($this->action_type === 'git_commit' || !class_exists($this->reference_type)) {
            return true;
        }

        try {
            $model = app($this->reference_type);
            $keyName = $model->getKeyName();
            $keyType = $model->getKeyType();

            // Si la clave primaria del modelo es entera/numérica pero el reference_id no lo es, evitar error de sintaxis en PostgreSQL
            if (in_array($keyType, ['int', 'integer']) && !is_numeric($this->reference_id)) {
                return true;
            }

            return $model->newQuery()
                ->where($keyName, $this->reference_id)
                ->exists();
        } catch (\Throwable $e) {
            return true;
        }
    }
}
