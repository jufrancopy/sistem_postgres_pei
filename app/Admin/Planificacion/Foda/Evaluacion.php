<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
	protected  $table= 'planificacion.evaluaciones';

	protected $dateFormat = 'Y-m-d H:i:sO';
	
	protected $fillable =[
   	'item','tipo_evaluacion_id','	beneficiado', 'condicion', 'total', 'actividades', 'anho_id', 	'subgerencia_id'
   	];

	public function tipos_evaluaciones(){
   		return $this->belongsTo(TipoEvaluacion::class, 'tipo_evaluacion_id');
   	}

   	public function subgerencias(){
   		return $this->belongsTo(Subgerencia::class, 'subgerencia_id');
   	}

      public function anhos(){
         return $this->belongsTo(Anho::class, 'anho_id');
      }
}
