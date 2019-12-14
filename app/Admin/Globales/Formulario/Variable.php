<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $table = 'globales.formulario_variables';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['variable', 'user_id'];

    public function item(){
        return $this->hasMany('App\Admin\Globales\Formulario\Item');
    }

    public function scopeVariable($query, $variable)
    {
        if (trim($variable) !="")
        {

    $query->where(\DB::raw("CONCAT(variable, ' ', id)"), 'LIKE', "%$variable%");    
        }
        
    }
}
