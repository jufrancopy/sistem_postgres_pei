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

    public function equipamientos()
    {
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Equipamiento', 'proyecto.e_p_c_equipamientos_servicios', 'servicio_id', 'equipamiento_id')
            ->withPivot('cantidad');
    }

    public function tthhs()
    {
        return $this->belongsToMany('App\Admin\Proyecto\EPC\TalentoHumano', 'proyecto.e_p_c_tthhs_servicios', 'servicio_id', 'tthh_id')
            ->withPivot('cantidad');
    }

    public function infraestructuras()
    {
        return $this->belongsToMany('App\Admin\Proyecto\EPC\Infraestructura', 'proyecto.e_p_c_infraestructura_servicio', 'servicio_id', 'infraestructura_id')
            ->withPivot('cantidad');
    }

    public function apoyoAdministrativos()
    {
        return $this->belongsToMany('App\Admin\Proyecto\EPC\ApoyoAdministrativo', 'proyecto.e_p_c_apoyo_administrativo_servicio', 'servicio_id', 'apoyo_administrativo_id')
            ->withPivot('cantidad');
    }

    public function otroServicios()
    {
        return $this->belongsToMany('App\Admin\Proyecto\EPC\OtroServicio', 'proyecto.otroservicio_has_servicios', 'servicio_id', 'otro_servicio_id')
            ->withPivot('cantidad');
    }

    
}
