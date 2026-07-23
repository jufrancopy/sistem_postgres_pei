<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'foda_profile_id',
        'foda_analisis_id',
        'pei_profile_id',
        'show_foda',
        'show_pei',
        'show_riiss',
    ];

    protected $casts = [
        'show_foda' => 'boolean',
        'show_pei' => 'boolean',
        'show_riiss' => 'boolean',
    ];

    // Relaciones
    public function fodaProfile()
    {
        return $this->belongsTo(\App\Admin\Planificacion\Foda\FodaPerfil::class, 'foda_profile_id');
    }

    public function fodaAnalisis()
    {
        return $this->belongsTo(\App\Admin\Planificacion\Foda\FodaAnalisis::class, 'foda_analisis_id');
    }

    public function peiProfile()
    {
        return $this->belongsTo(\App\Admin\Planificacion\Pei\PeiProfile::class, 'pei_profile_id');
    }
}
