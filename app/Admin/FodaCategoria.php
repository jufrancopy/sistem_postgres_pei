<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class FodaCategoria extends Model
{
    protected $table = "foda_categorias";

    protected $fillable = ['user_id', 'nombre', 'ambiente', 'modelo_id'];

    public function perfil()
    {
        return $this->belongsTo('App\Admin\FodaPerfil');
    }

    public function aspectos()
    {
        return $this->belongsToMany('App\Admin\FodaAspecto');
    }
    
    public function modelo()
    {
        return $this->belongsTo('App\Admin\FodaModelo');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) != "") {

            $query->where(\DB::raw("CONCAT(nombre, ' ', ambiente)"), 'LIKE', "%$nombre%");
        }
    }
}
