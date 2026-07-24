<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;

class GamificationBadge extends Model
{
    protected $table = 'gamification_user_badges';

    protected $fillable = [
        'user_id',
        'pei_profile_id',
        'badge_key',
        'unlocked_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    /**
     * Definición del catálogo global de insignias
     */
    public static function getCatalog(): array
    {
        return [
            // BRONCE
            'primer_paso' => [
                'name'        => 'Primer Paso',
                'category'    => 'Comentarios',
                'tier'        => 'bronce',
                'icon'        => 'fa-comment-alt',
                'color'       => '#cd7f32',
                'description' => 'Realizaste tu primer comentario en una actividad.',
            ],
            'diagnostico_inicial' => [
                'name'        => 'Diagnóstico Inicial',
                'category'    => 'FODA',
                'tier'        => 'bronce',
                'icon'        => 'fa-search',
                'color'       => '#cd7f32',
                'description' => 'Registraste tu primer análisis de aspecto en el módulo FODA.',
            ],
            'evaluador_novato' => [
                'name'        => 'Evaluador Novato',
                'category'    => 'RIISS',
                'tier'        => 'bronce',
                'icon'        => 'fa-hospital',
                'color'       => '#cd7f32',
                'description' => 'Completaste tu primera evaluación de un establecimiento de salud.',
            ],
            'ingreso_diario' => [
                'name'        => 'Puntual',
                'category'    => 'Sistema',
                'tier'        => 'bronce',
                'icon'        => 'fa-key',
                'color'       => '#cd7f32',
                'description' => 'Ingresaste al sistema activamente.',
            ],

            // PLATA
            'tactico_eficiente' => [
                'name'        => 'Táctico Eficiente',
                'category'    => 'Tareas',
                'tier'        => 'plata',
                'icon'        => 'fa-check-circle',
                'color'       => '#a0a0a0',
                'description' => 'Completaste 10 tareas a tiempo dentro de las actividades.',
            ],
            'analista_foda' => [
                'name'        => 'Analista FODA',
                'category'    => 'FODA',
                'tier'        => 'plata',
                'icon'        => 'fa-th-large',
                'color'       => '#a0a0a0',
                'description' => 'Registraste 15 aspectos estratégicos en el FODA.',
            ],
            'formulador_estrategico' => [
                'name'        => 'Formulador Estratégico',
                'category'    => 'FODA',
                'tier'        => 'plata',
                'icon'        => 'fa-chess',
                'color'       => '#a0a0a0',
                'description' => 'Formulaste 5 estrategias de cruce de ambientes (FO/DO/FA/DA).',
            ],
            'evaluador_experto' => [
                'name'        => 'Evaluador Experto',
                'category'    => 'RIISS',
                'tier'        => 'plata',
                'icon'        => 'fa-clipboard-check',
                'color'       => '#a0a0a0',
                'description' => 'Completaste 10 evaluaciones de establecimientos de la RIISS.',
            ],
            'comunicador' => [
                'name'        => 'Comunicador Activo',
                'category'    => 'Comentarios',
                'tier'        => 'plata',
                'icon'        => 'fa-comments',
                'color'       => '#a0a0a0',
                'description' => 'Realizaste 20 comentarios constructivos en tareas.',
            ],

            // ORO
            'arquitecto_estrategico' => [
                'name'        => 'Arquitecto Estratégico',
                'category'    => 'FODA',
                'tier'        => 'oro',
                'icon'        => 'fa-crown',
                'color'       => '#ffd700',
                'description' => 'Formulaste 15 estrategias de cruce y 30 análisis FODA.',
            ],
            'guardian_ejecucion' => [
                'name'        => 'Guardián de Ejecución',
                'category'    => 'Tareas',
                'tier'        => 'oro',
                'icon'        => 'fa-rocket',
                'color'       => '#ffd700',
                'description' => 'Completaste 50 tareas dentro de la planificación.',
            ],
            'inspector_salud' => [
                'name'        => 'Inspector de Salud',
                'category'    => 'RIISS',
                'tier'        => 'oro',
                'icon'        => 'fa-user-md',
                'color'       => '#ffd700',
                'description' => 'Completaste 25 evaluaciones de la Red de Salud.',
            ],
            'leyenda_estrategica' => [
                'name'        => 'Leyenda Estratégica',
                'category'    => 'General',
                'tier'        => 'oro',
                'icon'        => 'fa-trophy',
                'color'       => '#ffd700',
                'description' => 'Alcanzaste 1,000+ puntos de reputación en un Plan PEI.',
            ],
        ];
    }
}
