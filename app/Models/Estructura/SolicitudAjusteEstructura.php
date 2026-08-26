<?php

namespace App\Models\Estructura;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;

class SolicitudAjusteEstructura extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solicitudes_ajuste_estructura';

    protected $fillable = [
        'codigo',
        'fecha_solicitud',
        'pei_profile_id',
        'dependencia_solicitante_id',
        'dependencia_solicitante_texto',
        'solicitante_nombre',
        'solicitante_cargo',
        'solicitante_email',
        'solicitante_telefono',
        'fundamentacion_general',
        'documento_respaldo_path',
        'organigrama_adjunto_path',
        'estado',
        'dictamen_tecnico',
        'analista_id',
        'fecha_dictamen',
        'token_qr',
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_dictamen'  => 'datetime',
    ];

    public const ESTADOS = [
        'solicitud'   => 'Solicitud Recibida',
        'en_analisis' => 'En Análisis Técnico',
        'observado'   => 'Con Observaciones',
        'aprobado'    => 'Aprobado',
        'rechazado'   => 'No Aprobado / Rechazado',
    ];

    public const TIPOS_REORGANIZACION = [
        'CREACION'               => 'Creación de Dependencia',
        'MODIFICACION_FUSION'    => 'Modificación / Fusión',
        'SUPRESION_ELIMINACION'  => 'Supresión / Eliminación',
        'CAMBIO_DENOMINACION'    => 'Cambio de Denominación',
        'REUBICACION_JERARQUICA' => 'Reubicación en el Organigrama',
        'OTRO'                   => 'Otro Ajuste',
    ];

    public function items()
    {
        return $this->hasMany(SolicitudAjusteEstructuraItem::class, 'solicitud_id')->orderBy('orden')->orderBy('id');
    }

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function dependenciaSolicitante()
    {
        return $this->belongsTo(Organigrama::class, 'dependencia_solicitante_id');
    }

    public function analista()
    {
        return $this->belongsTo(User::class, 'analista_id');
    }

    public static function generarCodigo(): string
    {
        $year = date('Y');
        $ultimo = static::whereYear('created_at', $year)
            ->withTrashed()
            ->orderByDesc('id')
            ->first();

        $numero = $ultimo ? ((int) substr($ultimo->codigo, -4)) + 1 : 1;
        return sprintf('SAE-%s-%04d', $year, $numero);
    }

    public static function generarToken(): string
    {
        do {
            $token = Str::random(32);
        } while (static::where('token_qr', $token)->exists());

        return $token;
    }

    public function getUrlQrAttribute(): string
    {
        return route('solicitud-estructura.consulta', $this->token_qr);
    }

    public static function estadoBadge(string $estado): string
    {
        return match ($estado) {
            'solicitud'   => 'badge-primary',
            'en_analisis' => 'badge-info',
            'observado'   => 'badge-warning text-dark',
            'aprobado'    => 'badge-success',
            'rechazado'   => 'badge-danger',
            default       => 'badge-secondary',
        };
    }

    public static function estadoLabel(string $estado): string
    {
        return self::ESTADOS[$estado] ?? ucfirst($estado);
    }
}
