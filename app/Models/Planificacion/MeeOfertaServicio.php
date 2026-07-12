<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Planificacion\Pei\PeiProfile;

class MeeOfertaServicio extends Model
{
    protected $table = 'planificacion.mee_oferta_servicios';

    protected $fillable = [
        'pei_profile_id', 'accion', 'descripcion', 'beneficiarios', 'orden',
    ];

    public function perfil()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }
}
