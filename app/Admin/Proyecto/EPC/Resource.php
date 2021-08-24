<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'proyecto.e_p_c_resources';

    protected $fillable = [
        'item',
        'type',
        'measure',
        'detail',
        'cost'
    ];
}
