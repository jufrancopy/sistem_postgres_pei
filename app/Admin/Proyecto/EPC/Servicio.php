<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'proyecto.e_p_c_servicios';
    
    /*
    protected $fillable = [
        'item', 
        'type',
        'description',
        'detail_tthh_id',
        'detail_equipamiento_id',
        'detail_infraestructura_id',
        'detail_medicamento-insumo_id',
        'detail_apoyo-administrativo_id',
        ];
    */
    protected $fillable = [
        'item', 
        'type',
        'description',
        'detail_equipamiento_id',
     
        ];

    public function detailEquipamientos(){
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Servicio', 'proyecto.e_p_c_equipamientos', 'servicio_id','detail_equipamiento_id');
    }
}
