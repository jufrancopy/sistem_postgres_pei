<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanDiagnostico extends Model
{
    protected $table = 'plan_diagnosticos';
    protected $fillable = ['plan_id', 'titulo', 'contenido', 'orden'];

    public function tags()
    {
        return $this->hasMany(PlanDiagnosticoTag::class, 'diagnostico_id');
    }

    public function plan()
    {
        return $this->belongsTo(PlanMaestro::class, 'plan_id');
    }
}
