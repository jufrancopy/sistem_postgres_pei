<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class OtroServicio extends Model
{
    protected $table = 'proyecto.e_p_c_otro_servicios';
    
    protected $fillable = [
        'item', 
        'type',
        'cost'
        ];
}
