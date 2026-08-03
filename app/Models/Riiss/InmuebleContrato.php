<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InmuebleContrato extends Model
{
    use HasFactory;
    
    protected $table = 'inmueble_contratos';

    protected $fillable = [
        'id_establecimiento',
        'tipo_contrato',
        'nro_contrato',
        'descripcion',
        'costo_total',
        'porcentaje_avance',
        'archivo_url',
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento', 'id_establecimiento');
    }
}
