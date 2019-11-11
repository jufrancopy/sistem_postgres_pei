<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'globales.formulario_items';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['item','user_id', 'variable_id'];

    public function dependencia(){
        return $this->belongsTo('App\Admin\Globales\Organigrama');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function variable(){
        return $this->belongsTo('App\Admin\Globales\Formulario\Variable');
    }

    public function scopeItem($query, $item)
    {
        if (trim($item) !="")
        {

    $query->where(\DB::raw("CONCAT(item, ' ', id)"), 'LIKE', "%$item%");    
        }
        
    }
}
