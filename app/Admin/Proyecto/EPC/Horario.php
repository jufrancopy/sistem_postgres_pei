<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table ="proyecto.e_p_c_horarios";
    
    protected $fillable = ['item', 'start_time', 'end_time'];
}
