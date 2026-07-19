<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Models\User;
use App\Admin\Planificacion\Task\Task;
use App\Admin\Globales\Group;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Models\Planificacion\MarcoReferencial;

class PeiProfile extends Model
{
    use HasUuids;
    use SoftDeletes;
    use NodeTrait;

    protected $table = 'planificacion.pei_profiles';

    protected $fillable = [
        'name',
        'year_start',
        'year_end',
        'type',
        'level',
        'mision',
        'vision',
        'values',
        'period',
        'numerator',
        'operator',
        'denominator',
        'goal',
        'progress',
        'group_id',
        'dependency_id',
        'action',
        'indicator',
        'baseline',
        'target',
        'user_id',
        'order_item',
        'report_type',
        'parameters',
        'number_target',
        'tipo_indicador',
        'semaforo',
        'presupuesto_asignado',
        'presupuesto_ejecutado',
        'nivel_label',
        'foda_perfil_id',
        'bsc_perspectiva',
        'indicador_id',
        'activity_id',
        'public_token',
        'public_tabs',
        'resultado_intermedio',
        'ri_presupuestario',
        'ri_programa',
        'ri_recursos_gs',
        'ri_metas',
        'public_token',
    ];

    protected $casts = [
        'ri_metas'    => 'array',
        'public_tabs' => 'array',
    ];

    const BSC_PERSPECTIVAS = [
        'financiera'  => 'Perspectiva Financiera',
        'clientes'    => 'Perspectiva de Clientes / Usuarios',
        'procesos'    => 'Perspectiva de Procesos Internos',
        'aprendizaje' => 'Perspectiva de Aprendizaje y Crecimiento',
    ];

    // Modelos de niveles predefinidos
    public static function modelosDeNiveles(): array
    {
        return [
            'A'     => ['master' => 'Plan',                   'axi' => 'Eje',                  'goal' => 'Objetivo',  'action' => 'Acción'],
            'B'     => ['master' => 'Plan',                   'axi' => 'Programa',             'goal' => 'Proyecto',  'action' => 'Actividad'],
            'C'     => ['master' => 'Estrategia',             'axi' => 'Eje',                  'goal' => 'Meta',      'action' => 'Tarea'],
            'D'     => ['master' => 'Objetivo Institucional', 'axi' => 'Estrategia',           'goal' => 'Plan',      'action' => 'Acción'],
            'IPS'   => ['master' => 'PEI',                    'axi' => 'Eje Estratégico',      'goal' => 'Objetivo',  'action' => 'Acción'],
            'MECIP' => ['master' => 'PEI',                    'axi' => 'Objetivo Estratégico', 'goal' => 'Meta',      'action' => 'Acción'],
        ];
    }

    /**
     * Retorna las etiquetas del modelo MECIP 2015 como array asociativo.
     */
    public static function etiquetasMecip(): array
    {
        return self::modelosDeNiveles()['MECIP'];
    }

    public function generatePublicToken(): string
    {
        $token = bin2hex(random_bytes(24));
        $this->update(['public_token' => $token]);
        return $token;
    }

    public function revokePublicToken(): void
    {
        $this->update(['public_token' => null]);
    }

    public function getLabelNivel(): string
    {
        if ($this->nivel_label) {
            $labels = json_decode($this->nivel_label, true);
            return $labels[$this->level] ?? ucfirst($this->level);
        }
        // Default modelo A
        return self::modelosDeNiveles()['A'][$this->level] ?? ucfirst($this->level);
    }

    public function alertaPresupuestaria(): ?string
    {
        if (!$this->presupuesto_asignado || !$this->target || $this->presupuesto_asignado == 0) {
            return null;
        }

        $pctMeta       = $this->target > 0 ? ($this->progress / $this->target) * 100 : 0;
        $pctPresupuesto = ($this->presupuesto_ejecutado / $this->presupuesto_asignado) * 100;

        if ($pctMeta < 20 && $pctPresupuesto > 80) {
            return 'subejecucion';
        }

        return null;
    }

    public function calcularSemaforo(): void
    {
        if (!$this->progress || !$this->target || $this->target == 0) {
            return;
        }

        $avance = ($this->progress / $this->target) * 100;

        if ($this->tipo_indicador === 'lead') {
            // Lead al 100% pero Lag estancado → cuello de botella (amarillo)
            // Se evalúa solo el esfuerzo
            if ($avance >= 100) {
                $this->semaforo = 'verde';
            } elseif ($avance >= 50) {
                $this->semaforo = 'amarillo';
            } else {
                $this->semaforo = 'rojo';
            }
        } else {
            // Lag: resultado histórico
            if ($avance >= 85) {
                $this->semaforo = 'verde';
            } elseif ($avance >= 50) {
                $this->semaforo = 'amarillo';
            } else {
                $this->semaforo = 'rojo';
            }
        }

        $this->save();
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function analysts()
    {
        return $this->belongsToMany(User::class, 'planificacion.peis_profiles_has_analysts', 'pei_profile_id', 'analyst_id');
    }

    public function tasks()
    {
        return $this->morphMany(Task::class, 'typetaskable', 'typetaskable_id');
    }

    public function strategies()
    {
        return $this->belongsToMany(FodaCruceAmbiente::class, 'planificacion.pei_profiles_has_strategies', 'profile_id', 'strategy_id');
    }

    public function responsibles()
    {
        return $this->belongsToMany(Organigrama::class, 'planificacion.peis_profiles_has_responsibles', 'profile_id', 'responsible_id')
            ->withPivot('rol')
            ->withTimestamps();
    }

    public function hasAccountable(): bool
    {
        return $this->responsibles()->wherePivot('rol', 'A')->exists();
    }

    public function accountableCount(): int
    {
        return $this->responsibles()->wherePivot('rol', 'A')->count();
    }

    public function dependency()
    {
        return $this->belongsTo(Organigrama::class);
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fodaPerfil()
    {
        return $this->belongsTo(FodaPerfil::class, 'foda_perfil_id');
    }

    public function actores()
    {
        return $this->hasMany(\App\Models\Planificacion\PeiActor::class, 'pei_profile_id');
    }

    public function indicador()
    {
        return $this->belongsTo(\App\Models\Planificacion\Indicador::class, 'indicador_id');
    }

    public function activity()
    {
        return $this->belongsTo(\App\Admin\Globales\Activity::class, 'activity_id');
    }

    public function activityTasks()
    {
        return $this->belongsToMany(
            \App\Admin\Globales\ActivityTask::class,
            'pei_profile_tasks',
            'pei_profile_id',
            'activity_task_id'
        );
    }

    public function marcos()
    {
        return $this->belongsToMany(
            MarcoReferencial::class,
            'planificacion.pei_profile_marcos',
            'pei_profile_id',
            'marco_id'
        );
    }
}
