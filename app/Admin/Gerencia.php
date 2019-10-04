<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Gerencia extends Model
{
    protected  $table= 'gerencias';
    public function organigrama()
    {
    	
        return $this->belongsTo(Organigrama::class);
    }

    public function subgerencias()
    {
    	
        return $this->belongsTo(SubGerencia::class);
    }
}
