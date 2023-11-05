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
        'order_item'
    ];

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
        return $this->belongsToMany(Organigrama::class, 'planificacion.peis_profiles_has_responsibles', 'profile_id', 'responsible_id');
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
