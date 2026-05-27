<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanCita extends Model
{
    protected $table = 'plan_citas';
    protected $fillable = ['plan_id', 'texto', 'autor', 'fecha', 'contexto', 'orden'];

    public function plan()
    {
        return $this->belongsTo(PlanMaestro::class, 'plan_id');
    }
}
