<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ServicioEquipamientoCantidad extends Pivot

{
    protected $table = 'proyecto.e_p_c_equipamientos_servicios';

    public function servicio()
    {
        return $this->belongsTo('App\Admin\Proyecto\EPC\Servicio');
    }
    
    public function equipamiento()
    {
        return $this->belongsTo('App\Admin\Proyecto\EPC\Equipamiento');
    }    

}
