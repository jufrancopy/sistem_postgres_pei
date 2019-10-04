<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Organigrama extends Model
{
    protected  $table= 'organigramas';

    public function gerencias()
    {
    	
        return $this->belongsTo(Gerencia::class);
    }

    public function subgerencias()
    {
    	
        return $this->belongsTo(SubGerencia::class);
    }
}
