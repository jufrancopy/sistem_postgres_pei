<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class TalentoHumano extends Model
{
    protected $table = 'proyecto.e_p_c_talento_humanos';
    
    protected $fillable = [
        'item', 
        'specialty', 
        'hours',
        'type',
        'cost'
        ];
}
