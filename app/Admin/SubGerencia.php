<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class SubGerencia extends Model
{
    protected  $table= 'sub_gerencias';  
    public function gerencias()
    {
    
        return $this->belongsTo(Gerencia::class);
    }

    public function organigramas()
    {
    	
        return $this->belongsTo(Organigrama::class);
    }
}
