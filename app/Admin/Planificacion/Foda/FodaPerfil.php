<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FodaPerfil extends Model
{
    protected $table = "planificacion.foda_perfiles";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:sO';
    
    protected $fillable = ['nombre','contexto', 'user_id', 'modelo_id'];
    
    
    public function categorias(){
        
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaCategoria', 'planificacion.foda_categorias_has_perfil', 'perfil_id','categoria_id' );

    }

    public function aspectos(){
        
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAspecto');

    }

    public function modelo(){
        
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaModelo');

    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', contexto)"), 'LIKE', "%$nombre%");    
        }
        
    }
}