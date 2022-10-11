<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $table = 'estadistica.formulario_formularios';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['formulario', 'dependencia_emisor_id', 'dependencia_receptor_id', 'user_id', 'status'];

    public function variables()
    {
        return $this
            ->belongsToMany('App\Admin\Globales\Formulario\Variable', 'estadistica.formulario_formulario_has_variables', 'formulario_id', 'variable_id')
            ->withPivot('selected')
            ->withPivot('selected_variable_id')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function dependenciaEmisora()
    {
        return $this->belongsTo('App\Admin\Globales\Organigrama', 'dependencia_emisor_id');
    }

    public function dependenciaReceptora()
    {
        return $this->belongsTo('App\Admin\Globales\Organigrama', 'dependencia_receptor_id');
    }
}
