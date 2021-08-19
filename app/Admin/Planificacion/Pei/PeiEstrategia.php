<?php

namespace App\Admin\Planificacion\Pei;

use Illuminate\Database\Eloquent\Model;

class PeiEstrategia extends Model
{
    protected $table = 'planificacion.pei_estrategias';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['estrategia', 'user_id', 'objetivo_id'];

     public function user(){
        return $this->belongsTo('App\User');
    }

    public function dependencies(){
        return $this->belongsToMany('App\Admin\Globales\Organigrama', 'planificacion.pei-estrategia_dependencia', 'estrategia_id','dependency_id')
                    ->withTimestamps();
    }


    public function scopeEstrategia($query, $estrategia)
    {
        if (trim($estrategia) !="")
        {

    $query->where(\DB::raw("CONCAT(estrategia, ' ', dependency_id)"), 'LIKE', "%$estrategia%");    
        }
        
    }
}
