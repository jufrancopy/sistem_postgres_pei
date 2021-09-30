<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Turno extends Model
{
    protected $table = 'proyecto.e_p_c_turnos';
    
    protected $fillable = ['item'];

    public function scopeNombre($query, $nombre){
        if (trim($nombre) !=""){
            $query->where(DB::raw("CONCAT(nombre, ' ', autor)"), 'LIKE', "%$nombre%");    
            }
        }
}
