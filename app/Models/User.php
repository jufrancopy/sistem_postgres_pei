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

    /**
     * Devuelve el PEI activo del usuario según el árbol del grupo al que pertenece.
     */
    public function peiActual()
    {
        $group = $this->group;
        if (!$group) {
            return null;
        }

        $root = $group->ancestors()->withDepth()->orderByDesc('depth')->first() ?? $group;

        if ($root) {
            return $root->pei()->first() ?? $group->pei()->first();
        }

        return null;
    }

    /**
     * Devuelve el grupo raíz del árbol funcional asociado al PEI.
     */
    public function getGrupoPadreAttribute()
    {
        $group = $this->group;
        if (!$group) {
            return null;
        }

        $root = $group->ancestors()->withDepth()->orderByDesc('depth')->first() ?? $group;

        if ($root && $root->pei()->exists()) {
            return $root;
        }

        return $group;
    }

    /**
     * Verifica si el usuario pertenece al mismo árbol jerárquico del grupo raíz dado.
     */
    public function perteneceAlArbol($grupoRoot): bool
    {
        if (!$grupoRoot || !$this->group) {
            return false;
        }

        $grupoActual = $this->group;

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
     * Solo para roles Coordinador/Analista de Planificación
     */
    public function getOrganigramaDelPeiAttribute()
    {
        if ($this->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación'])) {
            $pei = $this->peiActual();
            if ($pei && $pei->dependency_id) {
                return Organigrama::find($pei->dependency_id);
            }
        }
        return null;
    }
}
