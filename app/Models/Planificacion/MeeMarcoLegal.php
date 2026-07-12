<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Organigrama;

class MeeMarcoLegal extends Model
{
    protected $table = 'planificacion.mee_marco_legal';

    protected $fillable = [
        'pei_profile_id', 'marco_legal', 'competencias', 'orden',
    ];

    public function perfil()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function responsables()
    {
        return $this->belongsToMany(
            Organigrama::class,
            'planificacion.mee_marco_legal_responsables',
            'marco_legal_id',
            'organigrama_id'
        );
    }
}
