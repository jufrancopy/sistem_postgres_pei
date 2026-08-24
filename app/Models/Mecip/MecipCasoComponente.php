<?php

namespace App\Models\Mecip;

use Illuminate\Database\Eloquent\Model;

class MecipCasoComponente extends Model
{
    protected $table = 'mecip_caso_componentes';

    protected $fillable = [
        'mecip_caso_id',
        'tipo',
        'nombre',
        'entidad_origen_destino',
        'descripcion',
        'orden',
    ];

    public function caso()
    {
        return $this->belongsTo(MecipCaso::class, 'mecip_caso_id');
    }
}
