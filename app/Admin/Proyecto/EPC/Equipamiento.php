<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Equipamiento extends Model
{
    protected $table = 'proyecto.e_p_c_equipamientos';
    
    protected $fillable = [
        'item', 
        'type',
        'cost'
        ];
        public function servicios()
        {
            return $this->belongsToMany('App\Admin\Proyecto\EPC\Servicio');
        }
}
