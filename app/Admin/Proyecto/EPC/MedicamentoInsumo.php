<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class MedicamentoInsumo extends Model
{
    protected $table = 'proyecto.e_p_c_equipamientos';
    
    protected $fillable = [
        'item', 
        'type',
        'cost'
        ];
}
