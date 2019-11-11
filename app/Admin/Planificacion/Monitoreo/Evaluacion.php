<?php

namespace App\Admin\Planificacion\Monitoreo;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'planificacion.evaluaciones';

    protected $dateFormat = 'Y-m-d H:i:sO';
    
    protected $fillable = ['nombre'];

    public function tipo_evaluacion(){
        return $this->belongsTo('App\Admin\Planificacion\Monitoreo\TipoEvaluacion');
    }

    public function subGerencia(){
        return $this->belongsTo('App\Admin\SubGerencia');
    }
}

