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
    ];

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
}
