<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaCategoria extends Model
{
    protected $table = "planificacion.foda_categorias";

    protected $dateFormat = 'Y-m-d H:i:sO';

    protected $fillable = ['user_id', 'nombre', 'ambiente', 'modelo_id'];

    public function perfil()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaPerfil');
    }

    public function aspectos()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAspecto');
    }
    
    public function modelo()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaModelo');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) != "") {

            $query->where(\DB::raw("CONCAT(nombre, ' ', ambiente)"), 'LIKE', "%$nombre%");
        }
    }
}
