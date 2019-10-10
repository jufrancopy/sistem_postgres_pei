<?php

namespace App\Admin\Foda;

use Illuminate\Database\Eloquent\Model;

class FodaCategoria extends Model
{
    protected $table = "foda_categorias";

    protected $fillable = ['user_id', 'nombre', 'ambiente', 'modelo_id'];

    public function perfil()
    {
        return $this->belongsTo('App\Admin\Foda\FodaPerfil');
    }

    public function aspectos()
    {
        return $this->belongsToMany('App\Admin\Foda\FodaAspecto');
    }
    
    public function modelo()
    {
        return $this->belongsTo('App\Admin\Foda\FodaModelo');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) != "") {

            $query->where(\DB::raw("CONCAT(nombre, ' ', ambiente)"), 'LIKE', "%$nombre%");
        }
    }
}
