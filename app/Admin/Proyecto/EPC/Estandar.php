<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Estandar extends Model
{
    protected $table = 'proyecto.e_p_c_estandars';

    protected $fillable = [
        'item',
        'type',
        'detail'
    ];

    public function servicios()
    {
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Servicio', 'proyecto.e_p_c_estandars_servicios', 'estandar_id', 'servicio_id')
            ->withPivot('cantidad');
    }
}
