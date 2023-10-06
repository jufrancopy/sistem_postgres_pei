<?php

namespace App\Admin\Planificacion\Foda;


use App\Admin\Planificacion\Foda\FodaAnalisis;
use Illuminate\Database\Eloquent\Model;

class FodaCruceAmbiente extends Model
{
    protected $table = 'planificacion.foda_cruce_ambientes';

    // protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['user_id', 'perfil_id', 'fortaleza_id', 'oportunidad_id', 'debilidad_id', 'amenaza_id', 'estrategia', 'tipo'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function perfil()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaPerfil');
    }

    public function analisis()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaAnalisis');
    }

    public function fortalezas()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAnalisis', 'planificacion.foda_cruce_ambientes_has_fortalezas', 'cruce_id', 'fortaleza_id');
    }

    public function oportunidades()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAnalisis', 'planificacion.foda_cruce_ambientes_has_oportunidades', 'cruce_id', 'oportunidad_id');
    }

    public function debilidades()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAnalisis', 'planificacion.foda_cruce_ambientes_has_debilidades', 'cruce_id', 'debilidad_id');
    }

    public function amenazas()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAnalisis', 'planificacion.foda_cruce_ambientes_has_amenazas', 'cruce_id', 'amenaza_id');
    }
}
