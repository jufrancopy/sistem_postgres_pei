<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Admin\Planificacion\Foda\FodaModelo;


class FodaAnalisis extends Model
{
    protected $table = "planificacion.foda_analisis";

    // protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'user_id', 'perfil_id', 'aspecto_id', 'tipo', 'ocurrencia', 'impacto',
        'promedio_desempeno_6m', 'inversion_historica_6m', 'iea_valor', 'iea_clasificacion',
    ];

    public function calcularIEA(): void
    {
        if (!$this->promedio_desempeno_6m || !$this->inversion_historica_6m || $this->inversion_historica_6m == 0) {
            return;
        }

        $iea = $this->promedio_desempeno_6m / $this->inversion_historica_6m;
        $this->iea_valor = round($iea, 4);

        if ($iea < 0.4) {
            $this->iea_clasificacion = 'debilidad';
        } elseif ($iea > 0.8) {
            $this->iea_clasificacion = 'fortaleza';
        } else {
            $this->iea_clasificacion = 'neutro';
        }

        $this->save();
    }

    public function categoria()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaCategoria');
    }

    public function aspecto()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaModelo');
    }

    public function fodaCruceAmbientes()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaCruceAmbiente', 'planificacion.foda_cruce_ambientes_has_fortalezas', 'fortaleza_id', 'cruce_id');
    }

    public function perfil()
    {
        return $this->belongsTo('App\Admin\Planificacion\Foda\FodaPerfil');
    }

    public function model()
    {
        return $this->belongsTo(FodaModelo::class, 'aspecto_id');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) != "") {

            $query->where(DB::raw("CONCAT(nombre, ' ', categoria_id)"), 'LIKE', "%$nombre%");
        }
    }
}
