<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeiPerfil extends Model
{
    protected $table = 'planificacion.pei_perfiles';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['nombre', 'mision', 'vision','valores','responsable', 'vigencia','user_id'];

     public function user(){
        return $this->belongsTo('App\User');
    }

    public function objetivo(){
        return $this->belongsTo('App\Admin\Planificacion\Pei\PeiObjetivo', 'perfil_id');
    }

    public function objetivos(){
        return $this->belongsToMany('App\Admin\Planificacion\Pei\PeiObjetivo', 'planificacion.pei_perfil_objetivos', 'perfil_id','objetivo_id')->withTimestamps();
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {
            $query->where(\DB::raw("CONCAT(nombre, ' ', responsable)"), 'LIKE', "%$nombre%");    
        }
        
    }
}
