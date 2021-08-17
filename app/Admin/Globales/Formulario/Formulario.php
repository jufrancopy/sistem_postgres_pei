<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $table = 'estadistica.formulario_formularios';

    protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $fillable = ['formulario', 'dependencia_emisor_id', 'dependencia_receptor_id','user_id'];

    
    
    public function variables(){
        
        return $this->belongsToMany('App\Admin\Globales\Formulario\Variable', 'globales.formulario_formulario_has_variables', 'formulario_id','variable_id' );

    }

    public function dependenciaEmisora(){
        return $this->belongsTo('App\Admin\Globales\Organigrama', 'dependencia_emisor_id');
    }

    public function dependenciaReceptora(){
        return $this->belongsTo('App\Admin\Globales\Organigrama', 'dependencia_receptor_id');
    }
}
