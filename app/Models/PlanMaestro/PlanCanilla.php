<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanCanilla extends Model
{
    protected $table = 'plan_canillas';
    protected $fillable = ['plan_id', 'tipo', 'ejemplos', 'estrategia', 'monto', 'orden'];

    public function plan()
    {
        return $this->belongsTo(PlanMaestro::class, 'plan_id');
    }
}
