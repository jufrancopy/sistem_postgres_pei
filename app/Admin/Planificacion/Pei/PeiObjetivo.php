<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;

class PeiObjetivo extends Model
{
    protected $table = 'planificacion.pei_objetivos';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['user_id', 'objetivo', 'perfil_id'];

     public function user(){
        return $this->belongsTo('App\User');
    }

    public function dependencies(){
        return $this->belongsToMany('App\Admin\Globales\Organigrama', 'planificacion.pei-objetivo_dependencia', 'objetivo_id','dependency_id')
                    ->withTimestamps();
        
    }
}
