<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'proyecto.e_p_c_servicios';
    
    protected $fillable = [
        'item', 
        'type',
        'description',
        ];

    public function equipamientos(){
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Equipamiento', 'proyecto.e_p_c_recursos_servicios', 'servicio_id','detail_equipamiento_id');
    }

    public function resources(){
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Resource', 'proyecto.e_p_c_resources', 'servicio_id','resource_id');
    }
    
}
