<?php

namespace App\Admin\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaAnalisis extends Model
{
    protected $dateFormat = 'Y-m-d H:i:sO';
    
    protected $table = "foda_analisis";
    
    protected $fillable = ['user_id','perfil_id', 'aspecto_id','tipo', 'ocurrencia','impacto'];

    public function categoria(){
        return $this->belongsTo('App\Admin\Foda\FodaCategoria');
    }

    public function aspecto(){
        return $this->belongsTo('App\Admin\Foda\FodaAspecto');
    }

    
    public function perfil(){
        return $this->belongsTo('App\Admin\FodaPerfil');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', categoria_id)"), 'LIKE', "%$nombre%");    
        }
        
    }

    
}

