<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

use App\Models\User;
use App\Admin\Planificacion\Task\Task;
use App\Admin\Globales\Group;

class PeiProfile extends Model
{
    use SoftDeletes;
    use NodeTrait;

    protected $table = 'planificacion.pei_profiles';

    protected $fillable = [
        'name',
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
}
