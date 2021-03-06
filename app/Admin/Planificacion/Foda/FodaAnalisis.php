<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaAnalisis extends Model
{
    protected $table = "planificacion.foda_analisis";
    
    protected $dateFormat = 'Y-m-d H:i:sO';
    
    protected $fillable = ['user_id','perfil_id', 'aspecto_id','tipo', 'ocurrencia','impacto'];

    public function categoria(){
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaCategoria');
    }

    public function aspecto(){
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaAspecto');
    }

    
    public function perfil(){
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaPerfil');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', categoria_id)"), 'LIKE', "%$nombre%");    
        }
        
    }

    
}

