<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Infraestructura extends Model
{
    protected $table = 'proyecto.e_p_c_infraestructuras';

    protected $fillable = [
        'item',
        'type',
        'measurement',
        'cost'
    ];
}
