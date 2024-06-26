<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FodaAspecto extends Model
{
    protected $table = "planificacion.foda_aspectos";

    // protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $fillable = ['user_id','nombre', 'referencia','categoria_id'];

    public function categoria(){
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaCategoria');
    }
    
    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(DB::raw("CONCAT(nombre, ' ', categoria_id)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
