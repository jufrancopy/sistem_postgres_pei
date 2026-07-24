<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use App\Admin\Globales\Group;


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
}
