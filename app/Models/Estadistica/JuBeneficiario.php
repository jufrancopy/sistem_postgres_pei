<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Estadistica\SiessTerritorialTrait;

class JuBeneficiario extends Model
{
    use SiessTerritorialTrait;

    protected $table = 'estadistica.ju_beneficiarios';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'organigrama_id', 'locality_id',
        'cedula', 'sexo', 'edad',
        'barrio', 'ciudad', 'departamento_codigo', 'departamento_nombre',
        'monto_bruto', 'concepto', 'fecha_concesion',
    ];

    protected $casts = ['fecha_concesion' => 'date'];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    public static function resumenPorPeriodo(int $periodoId): array
    {
        return [
            'total'          => self::where('periodo_id', $periodoId)->count(),
            'mujeres'        => self::where('periodo_id', $periodoId)->where('sexo', 'F')->count(),
            'hombres'        => self::where('periodo_id', $periodoId)->where('sexo', 'M')->count(),
            'monto_total'    => self::where('periodo_id', $periodoId)->sum('monto_bruto'),
            'monto_promedio' => self::where('periodo_id', $periodoId)->avg('monto_bruto'),
            'edad_promedio'  => self::where('periodo_id', $periodoId)->avg('edad'),
        ];
    }

    public static function porConcepto(int $periodoId): \Illuminate\Support\Collection
    {
        return self::where('periodo_id', $periodoId)
            ->selectRaw('concepto, COUNT(*) as total, SUM(monto_bruto) as monto_total')
            ->groupBy('concepto')
            ->orderByDesc('total')
            ->get();
    }

    public static function porDepartamento(int $periodoId): \Illuminate\Support\Collection
    {
        return self::where('periodo_id', $periodoId)
            ->selectRaw('departamento_nombre, COUNT(*) as total, SUM(monto_bruto) as monto_total')
            ->groupBy('departamento_nombre')
            ->orderByDesc('total')
            ->get();
    }
}
