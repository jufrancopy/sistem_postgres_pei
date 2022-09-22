<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;

class Variable extends Model
{
    use NodeTrait;
    
    protected $table = 'estadistica.formulario_variables';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['name', 'type','user_id'];

    public function item(){
        return $this->hasMany('App\Admin\Globales\Formulario\Item');
    }

    public function scopeVariable($query, $variable)
    {
        if (trim($variable) !="")
        {

    $query->where(DB::raw("CONCAT(variable, ' ', id)"), 'LIKE', "%$variable%");    
        }
        
    }
}
