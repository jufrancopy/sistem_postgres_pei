<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;

class EstructuraControl extends Model
{
    protected $table = 'globales.estructuras';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $fillable = ['dependencia', 'user_id'];

    public function dependencias(){
        return $this->belongsToMany('App\Admin\Globales\Organigrama', 'globales.dependencias_has_estructuras','estructura_id','dependency_id' );
    }

    public function subDependencia(){
        return $this->belongsTo('App\Admin\Globales\Organigrama', 'dependencia');
    }
}
