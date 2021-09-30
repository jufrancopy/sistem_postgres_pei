<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TalentoHumano extends Model
{
    protected $table = 'proyecto.e_p_c_talento_humanos';

    protected $fillable = [
        'item',
        'hours',
        'type',
        'cost'
    ];
    
    public function especialidad()
    {
        return $this->belongsTo('App\Admin\Proyecto\EPC\Especialidad', 'specialty_id');
    }

    public function scopeItem($query, $item){
        if (trim($item) !=""){
            $query->where(DB::raw("CONCAT(item, ' ', type)"), 'LIKE', "%$item%");    
            }
        }
}
