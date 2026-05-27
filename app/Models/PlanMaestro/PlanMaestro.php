<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanMaestro extends Model
{
    protected $table = 'plan_maestros';

    protected $fillable = [
        'nombre', 'institucion', 'descripcion', 'responsable', 'periodo', 'user_id', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function ejes()
    {
        return $this->hasMany(PlanEje::class, 'plan_id')->orderBy('orden');
    }

    public function acciones()
    {
        return $this->hasMany(PlanAccion::class, 'plan_id')->orderBy('orden');
    }

    public function diagnosticos()
    {
        return $this->hasMany(PlanDiagnostico::class, 'plan_id')->orderBy('orden');
    }

    public function canillas()
    {
        return $this->hasMany(PlanCanilla::class, 'plan_id')->orderBy('orden');
    }

    public function citas()
    {
        return $this->hasMany(PlanCita::class, 'plan_id')->orderBy('orden');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
