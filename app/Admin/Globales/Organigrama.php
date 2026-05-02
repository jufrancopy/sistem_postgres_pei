<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;

class Organigrama extends Model
{
    use NodeTrait;
    
    protected $table = 'organigramas';
    
    protected $fillable = ['dependency', 'user_id', 'email', 'manager', 'phone'];

    public function parent()
    {
        return $this->belongsTo(Organigrama::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    
    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(DB::raw("CONCAT(dependency, ' ', dependency_id)"), 'LIKE', "%$nombre%");    
        }
        
    }


}


