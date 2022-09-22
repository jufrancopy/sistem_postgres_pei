<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'type'];

    
    public function scopeName($query, $name)
    {
        if (trim($name) !="")
        {

    $query->where(DB::raw("CONCAT(name, ' ', id)"), 'LIKE', "%$name%");    
        }
        
    }
}
