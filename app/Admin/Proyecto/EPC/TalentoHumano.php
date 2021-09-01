<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class TalentoHumano extends Model
{
    protected $table = 'proyecto.e_p_c_talento_humanos';

    protected $fillable = [
        'item',
        // 'specialty_id',
        'hours',
        'type',
        'cost'
    ];

    public function especialidad()
    {
        return $this->belongsTo('App\Admin\Proyecto\EPC\Especialidad', 'specialty_id');
    }
}
