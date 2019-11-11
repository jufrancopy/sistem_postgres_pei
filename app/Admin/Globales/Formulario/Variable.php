<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $table = 'globales.formulario_variables';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['variable', 'user_id'];

    public function items(){

        return $this->belongsToMany('App\Admin\Globales\Formulario\Item', 'globales.formulario_items_has_variable', 'variable_id', 'item_id');
    }

    public function scopeVariable($query, $variable)
    {
        if (trim($variable) !="")
        {

    $query->where(\DB::raw("CONCAT(variable, ' ', id)"), 'LIKE', "%$variable%");    
        }
        
    }
}
