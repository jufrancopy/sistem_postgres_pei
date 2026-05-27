<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanDiagnosticoTag extends Model
{
    public $timestamps = false;
    protected $table = 'plan_diagnostico_tags';
    protected $fillable = ['diagnostico_id', 'tag'];
}
