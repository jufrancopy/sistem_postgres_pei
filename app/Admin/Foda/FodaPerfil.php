<?php

namespace App\Admin\Foda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FodaPerfil extends Model
{
    protected $table = "foda_perfiles";
    
    protected $fillable = ['nombre','contexto', 'user_id', 'modelo_id'];
    
    
    public function categorias(){
        
        return $this->belongsToMany('App\Admin\Foda\FodaCategoria', 'foda_categorias_has_perfil', 'perfil_id','categoria_id' );

    }

    public function aspectos(){
        
        return $this->belongsToMany('App\Admin\Foda\FodaAspecto');

    }

    public function modelo(){
        
        return $this->belongsTo('App\Admin\Foda\FodaModelo');

    }

   

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', contexto)"), 'LIKE', "%$nombre%");    
        }
        
    }
}