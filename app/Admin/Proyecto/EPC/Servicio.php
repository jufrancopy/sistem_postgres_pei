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
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Equipamiento', 'proyecto.e_p_c_equipamientos_servicios', 'servicio_id','detail_equipamiento_id');
    }

    public function tthh(){
        return $this->belongsToMany('App\Admin\Proyecto\EPC\TalentoHumano', 'proyecto.e_p_c_tthh_servicios', 'servicio_id','detail_tthh_id');
    }

    public function medicamentoInsumos(){
        return $this->belongsToMany('App\Admin\Proyecto\EPC\MedicamentoInsumo', 'proyecto.medicamento-insumos_servicios', 'servicio_id','detail_medicamento-insumo_id');
    }

    public function resources(){
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Resource', 'proyecto.e_p_c_resources', 'servicio_id','resource_id');
    }
    
}
