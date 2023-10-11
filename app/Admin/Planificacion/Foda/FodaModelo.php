<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;


class FodaModelo extends Model
{
    use NodeTrait;
    
    protected $table = 'planificacion.foda_models';

    // protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $fillable = ['name', 'owner', 'environment', 'description'];

    public function categorias(){
        $this->belongsToMany('App\Admin\Planificacion\Foda\FodaCategoria', 'planificacion.foda_categoria_has_modelo', 'modelo_id', 'categoria_id' );
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(DB::raw("CONCAT(nombre, ' ', autor)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
