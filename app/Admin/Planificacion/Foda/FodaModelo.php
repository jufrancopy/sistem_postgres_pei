<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaModelo extends Model
{
    protected $table = 'planificacion.foda_modelos';

    protected $dateFormat = 'Y-m-d H:i:sO';
    
    protected $fillable = ['user_id','nombre', 'autor'];

    public function categorias(){
        $this->belongsToMany('App\Admin\Planificacion\Foda\FodaCategoria', 'planificacion.foda_categoria_has_modelo', 'modelo_id', 'categoria_id' );
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', autor)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
