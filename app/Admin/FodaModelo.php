<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class FodaModelo extends Model
{
    protected $fillable = ['user_id','nombre', 'autor'];
    
    public function categorias(){
        $this->belongsToMany('App\Admin\FodaCategoria', 'foda_categoria_has_modelo', 'modelo_id', 'categoria_id' );
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', autor)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
