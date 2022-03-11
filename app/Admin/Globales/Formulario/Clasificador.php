<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Clasificador extends Model
{
    protected $table = 'estadistica.formulario_clasificadores';
    
    protected $fillable = ['user_id', 'clasificador', 'clasificador_id'];
    
    protected $dateFormat = 'Y-m-d H:i:s';

    public function clasificadores()
    {
        return $this->hasMany('App\Admin\Globales\Formulario\Clasificador', 'clasificador_id');
    }

    public function childrenClasificadores()  
    {
        return $this->hasMany('App\Admin\Globales\Formulario\Clasificador', 'clasificador_id', 'id')->with('clasificadores');
    }

    public function parent()
    {
        return $this->hasOne('App\Admin\Globales\Formulario\Clasificador', 'id', 'clasificador_id');
    }

        public function firstChild()
    {
        return $this->hasMany('App\Admin\Globales\Formulario\Clasificador')->with('parent');
    }
}
