<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\User;
use App\Admin\Planificacion\Task\Task;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Organigrama;


class Group extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $table = 'groups';

    protected $fillable = ['name', 'members'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'groups_has_members', 'group_id', 'user_id');
    }

    public function users()
    {
        return $this->members();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'group_id');
    }

    /**
     * Obtiene todos los usuarios del árbol completo del grupo.
     */
    public function usuariosDelArbol()
    {
        $ids = $this->descendantsAndSelf()->pluck('id')->toArray();

        return User::whereIn('group_id', $ids)->get();
    }

    /**
     * Obtener el PEI asociado a este grupo (ya sea como grupo raíz o grupo padre)
     */
    public function pei()
    {
        return $this->hasOne(PeiProfile::class, 'group_id')
            ->where('level', 'master')
            ->where('type', 'group');
    }

    /**
     * Obtener el organigrama asociado al PEI de este grupo
     */
    public function getOrganigramaAttribute()
    {
        $pei = $this->pei;
        if ($pei && $pei->dependency_id) {
            return Organigrama::find($pei->dependency_id);
        }
        return null;
    }
}
