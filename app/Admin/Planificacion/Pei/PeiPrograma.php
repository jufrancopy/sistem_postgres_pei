<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;

class PeiPrograma extends Model
{
    protected $table = 'planificacion.pei_programas';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['user_id', 'programa', 'estrategia_id'];

     public function user(){
        return $this->belongsTo('App\User');
    }

    public function dependencies(){
        return $this->belongsToMany('App\Admin\Globales\Organigrama', 'planificacion.pei-programa_dependencia', 'programa_id','dependency_id')
                    ->withTimestamps();
    }
}
