<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;

class Pei extends Model
{
    protected $table = 'planificacion.peis';

    protected $fillable = [
                'nivel', 
                'tipo', 
                'nivel_id',
                'user_id', 
                'mision', 
                'vision', 
                'periodo', 
                'numerador', 
                'operador', 
                'denominador', 
                'meta',
                'valores', 
                'avance'];
    
    public function niveles()
    {
        return $this->hasMany('App\Admin\Planificacion\Pei\Pei', 'nivel_id');
    }

    public function childrenNiveles()  
    {
        return $this->hasMany('App\Admin\Planificacion\Pei\Pei', 'nivel_id', 'id')->with('niveles');
    }

    public function parent()
    {
        return $this->hasOne('App\Admin\Planificacion\Pei\Pei', 'id', 'nivel_id');
    }

    public function dependencies(){
        return $this->belongsToMany('App\Admin\Globales\Organigrama', 'planificacion.pei_dependencias', 'pei_id','dependency_id')
                    ->withTimestamps();
    }
}
