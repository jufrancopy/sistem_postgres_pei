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
        'site_name',
        'logo_url',
        'contact_email',
        'contact_phone',
        'opening_hours',
        'address',
        'footer_text',
        'system_parameters',
    ];

    protected $casts = [
        'show_foda'         => 'boolean',
        'show_pei'          => 'boolean',
        'show_riiss'        => 'boolean',
        'system_parameters' => 'array',
    ];

    /**
     * Obtener el valor de una variable global del sistema con fallback por defecto.
     */
    public static function getSetting($key, $default = null)
    {
        $config = static::first();
        if (!$config) {
            return $default;
        }

        if (isset($config->$key) && !is_null($config->$key) && $config->$key !== '') {
            return $config->$key;
        }

        if (is_array($config->system_parameters) && isset($config->system_parameters[$key])) {
            return $config->system_parameters[$key];
        }

        return $default;
    }

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
