<?php

namespace App\Admin\Proyecto\EPC;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipamiento extends Model
{
    protected $table = 'proyecto.e_p_c_equipamientos';

    protected $fillable = [
        'item',
        'type',
        'cost'
    ];

    public function scopeItem($query, $item){
        if (trim($item) !=""){
            $query->where(DB::raw("CONCAT(item, ' ', type)"), 'LIKE', "%$item%");    
            }
        }
    
}
