<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;

class Organigrama extends Model
{
    protected $table = 'organigramas';
    
    protected $fillable = ['dependency', 'dependency_id', 'user_id', 'email', 'responsable', 'telefono'];
    
    public function dependencies()
    {
        return $this->hasMany('App\Admin\Globales\Organigrama', 'dependency_id');
    }

    public function childrenDependencies()  
    {
        return $this->hasMany('App\Admin\Globales\Organigrama', 'dependency_id', 'id')->with('dependencies');
    }

    public function parent()
    {
        return $this->hasOne('App\Admin\Globales\Organigrama', 'id', 'dependency_id');
    }
    

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(dependency, ' ', dependency_id)"), 'LIKE', "%$nombre%");    
        }
        
    }


}


