<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class ApoyoAdministrativo extends Model
{
    protected $table = 'proyecto.e_p_c_apoyo_administrativos';
    
    protected $fillable = [
        'item', 
        'type',
        'cost'
        ];
}
