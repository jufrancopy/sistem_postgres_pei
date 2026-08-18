<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Planificacion\Pei\PeiProfile;

class PeiAsesoria extends Model
{
    protected $table = 'planificacion.pei_asesorias';

    protected $fillable = [
        'pei_profile_id',
        'nombre',
        'email',
        'institucion',
        'codigo_acceso',
        'dictamen_general',
        'estado',
    ];

    public function profile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id', 'id');
    }

    public function comentarios()
    {
        return $this->hasMany(PeiAsesoriaComentario::class, 'pei_asesoria_id', 'id');
    }

    public static function generarCodigo(): string
    {
        do {
            $code = 'ASESOR-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::where('codigo_acceso', $code)->exists());

        return $code;
    }
}
