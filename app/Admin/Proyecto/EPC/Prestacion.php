<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Prestacion extends Model
{
    protected $table = 'proyecto.e_p_c_prestaciones';

    protected $fillable = [
        'item',
        'type',
        'detail',
        'cost'
    ];
}
