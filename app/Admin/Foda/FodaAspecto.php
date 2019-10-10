<?php

namespace App\Admin\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaAspecto extends Model
{
    protected $table = "foda_aspectos";
    
    protected $fillable = ['user_id','nombre', 'categoria_id'];

    public function categoria(){
        return $this->belongsTo('App\Admin\Foda\FodaCategoria');
    }
    
    
    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', categoria_id)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
