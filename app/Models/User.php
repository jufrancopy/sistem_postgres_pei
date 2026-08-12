<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use App\Admin\Globales\Group;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;


class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * Comprueba si el usuario está actualmente en línea (activo en los últimos 5 min).
     */
    public function isOnline(): bool
    {
        return \Illuminate\Support\Facades\Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Relación con las actividades del funcionario (Telemetría)
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'user_id');
    }

    /**
     * Puntos totales acumulados en Gamificación
     */
    public function getGamificationPointsAttribute(): int
    {
        return (int) \App\Models\Gamification\GamificationPoint::where('user_id', $this->id)->sum('points');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'group_id', 'avatar'
    ];

    /**
     * Retorna la URL completa del avatar del usuario o un avatar por defecto
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            return asset('storage/' . ltrim($this->avatar, '/'));
        }
        $name = urlencode($this->name ?? 'Usuario');
        return "https://ui-avatars.com/api/?name={$name}&background=0f172a&color=ffffff&bold=true";
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function allUsers()
    {
        return $this->all();
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_has_members', 'user_id', 'group_id');
    }

    /**
     * Devuelve el PEI activo del usuario según el árbol del grupo o dependencias a las que pertenece.
     */
    public function peiActual()
    {
        $group = $this->group;
        if (!$group && method_exists($this, 'groups')) {
            $group = $this->groups()->first();
        }

        if ($group) {
            $root = $group->isRoot()
                ? $group
                : ($group->ancestors()->whereNull('parent_id')->first() ?? $group->ancestors()->first() ?? $group);

            if ($root) {
                $pei = PeiProfile::where('level', 'master')->where('group_id', $root->id)->first();
                if ($pei) {
                    return $pei;
                }

                $descendantIds = $root->descendants()->pluck('id')->toArray();
                if (!empty($descendantIds)) {
                    $peiDesc = PeiProfile::where('level', 'master')->whereIn('group_id', $descendantIds)->first();
                    if ($peiDesc) {
                        return $peiDesc;
                    }
                }
            }
        }

        // Si no se encuentra por grupo, buscar por asignación directa de analista o responsable
        $peiAnalyst = PeiProfile::where('level', 'master')
            ->whereHas('analysts', function($q) {
                $q->where('users.id', $this->id);
            })->first();
        if ($peiAnalyst) {
            return $peiAnalyst;
        }

        $peiResp = PeiProfile::where('level', 'master')
            ->whereHas('responsibles', function($q) {
                $q->where('users.id', $this->id);
            })->first();
        if ($peiResp) {
            return $peiResp;
        }

        return null;
    }

    /**
     * Devuelve el grupo raíz del árbol funcional asociado al usuario o PEI.
     */
    public function getGrupoPadreAttribute()
    {
        $group = $this->group;
        if (!$group && method_exists($this, 'groups')) {
            $group = $this->groups()->first();
        }

        if (!$group) {
            return null;
        }

        if ($group->isRoot()) {
            return $group;
        }

        $root = $group->ancestors()->whereNull('parent_id')->first() ?? $group->ancestors()->first() ?? $group;
        return $root;
    }

    /**
     * Verifica si el usuario pertenece al mismo árbol jerárquico del grupo raíz dado.
     */
    public function perteneceAlArbol($grupoRoot): bool
    {
        if (!$grupoRoot) {
            return false;
        }

        $grupoActual = $this->group;
        if (!$grupoActual && method_exists($this, 'groups')) {
            $grupoActual = $this->groups()->first();
        }

        if (!$grupoActual) {
            return false;
        }

        if ($grupoActual->id === $grupoRoot->id) {
            return true;
        }

        $ancestors = $grupoActual->ancestors()->pluck('id')->toArray();
        if (in_array($grupoRoot->id, $ancestors, true)) {
            return true;
        }

        $descendants = $grupoRoot->descendants()->pluck('id')->toArray();
        return in_array($grupoActual->id, $descendants, true);
    }

    /**
     * Obtener el organigrama asociado al PEI del grupo del usuario
     * Solo para roles Coordinador/Analista de Planificación o Administrador
     */
    public function getOrganigramaDelPeiAttribute()
    {
        $pei = $this->peiActual();
        if ($pei && $pei->dependency_id) {
            return Organigrama::find($pei->dependency_id);
        }

        // Buscar si el usuario es manager en algún organigrama
        $org = Organigrama::where('user_id', $this->id)->first();
        if ($org) {
            return $org;
        }

        return null;
    }
}
