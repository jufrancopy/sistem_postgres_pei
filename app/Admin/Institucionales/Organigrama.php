<?php

namespace App\Admin\Institucionales;

use Illuminate\Database\Eloquent\Model;

class Organigrama extends Model
{
    
    protected $fillable = ['dependency', 'dependency_id', 'user_id', 'email', 'responsable', 'telefono'];
    
    public function dependencies()
    {
        return $this->hasMany('App\Admin\Institucionales\Organigrama', 'dependency_id');
    }

    public function childrenDependencies()
    {
        return $this->hasMany('App\Admin\Institucionales\Organigrama', 'dependency_id', 'id')->with('dependencies');
    }

    public function parent()
    {
        return $this->hasOne('App\Admin\Institucionales\Organigrama', 'id', 'dependency_id');
    }
    

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(\DB::raw("CONCAT(dependency, ' ', dependency_id)"), 'LIKE', "%$nombre%");    
        }
        
    }


}


