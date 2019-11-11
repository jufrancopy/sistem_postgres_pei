<?php

namespace App\Admin\Planificacion\Monitoreo;

use Illuminate\Database\Eloquent\Model;

class TipoEvaluacion extends Model
{
    protected $table = 'planificacion.tipos_evaluaciones';

    protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $fillable = ['nombre'];
    

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', id)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
