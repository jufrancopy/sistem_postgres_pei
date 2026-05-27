<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanEje extends Model
{
    protected $table = 'plan_ejes';

    protected $fillable = ['plan_id', 'codigo', 'nombre', 'color', 'icono', 'orden'];

    public function plan()
    {
        return $this->belongsTo(PlanMaestro::class, 'plan_id');
    }

    public function acciones()
    {
        return $this->hasMany(PlanAccion::class, 'eje_id')->orderBy('orden');
    }
}
