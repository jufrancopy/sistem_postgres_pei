<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\User;

class JuntaIntervencion extends Model
{
    use HasUuids;

    protected $table = 'planificacion.junta_intervenciones';

    protected $fillable = [
        'codigo_expediente',
        'junta_id',
        'pei_profile_id',
        'reporte_avance_id',
        'solicitante_user_id',
        'diagnostico',
        'recomendaciones_mitigacion',
        'estado',
        'prioridad',
        'firma_estampada_at',
    ];

    protected $casts = [
        'recomendaciones_mitigacion' => 'array',
        'firma_estampada_at' => 'datetime',
    ];

    public function junta()
    {
        return $this->belongsTo(Junta::class, 'junta_id');
    }

    public function accion()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function reporte()
    {
        return $this->belongsTo(PeiAccionReporte::class, 'reporte_avance_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_user_id');
    }

    public static function generarCodigoExpediente(Junta $junta): string
    {
        $year = date('Y');
        $count = self::where('junta_id', $junta->id)->whereYear('created_at', $year)->count() + 1;
        $progCode = strtoupper(substr($junta->programa ?? 'GEN', 0, 3));
        return 'INT-' . $progCode . '-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
