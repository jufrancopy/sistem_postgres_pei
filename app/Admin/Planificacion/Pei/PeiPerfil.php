<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;

class PeiPerfil extends Model
{
    protected $table = 'planificacion.pei_perfiles';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['nombre', 'responsable', 'vigencia_desde', 'vigencia_hasta', 'user_id'];

     public function user(){
        return $this->belongsTo('App\User');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(nombre, ' ', responsable)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
