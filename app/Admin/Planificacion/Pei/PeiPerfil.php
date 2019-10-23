<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;

class PeiPerfil extends Model
{
    protected $table = 'planificacion.pei_perfiles';

    protected $dateFormat = 'Y-m-d H:i:sO';

    protected $fillable = ['nombre', 'responsable', 'vigencia_desde', 'vigecia_hasta', 'user_id'];

     public function user(){
        return $this->belongsTo('App\User');
    }
}
