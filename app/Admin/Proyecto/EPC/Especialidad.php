<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'proyecto.e_c_p_especialidads';
    
    protected $fillable = [
        'item', 
        'type',
        'detail',
        'cost'
        ];
}
