<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
// use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Organigrama extends Model
{
    use NodeTrait;
    // use HasRecursiveRelationships;

    protected $table = 'organigramas';
    
    protected $fillable = ['dependency', 'user_id', 'email', 'responsable', 'telefono'];
    
    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(DB::raw("CONCAT(dependency, ' ', dependency_id)"), 'LIKE', "%$nombre%");    
        }
        
    }


}


