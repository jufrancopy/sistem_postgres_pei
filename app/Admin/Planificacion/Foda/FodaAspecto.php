<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaAspecto extends Model
{
    protected $table = "planificacion.foda_aspectos";

    protected $dateFormat = 'Y-m-d H:i:sO';
    
    protected $fillable = ['user_id','nombre', 'categoria_id'];

    public function categoria(){
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaCategoria');
    }
    
    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', categoria_id)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
