<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AopTrabajador extends Model
{
    protected $table = 'estadistica.aop_trabajadores';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'cedula', 'edad', 'sexo', 'salario',
        'tipo_empleado', 'tipo_seguro_codigo', 'tipo_seguro_descripcion',
        'es_excombatiente', 'regimen_codigo', 'regimen_descripcion',
        'departamento_codigo', 'departamento_nombre', 'zona',
        'empleador_ruc', 'empleador_nro_patronal', 'empleador_descripcion', 'empleador_actividad',
        'aporte_empleado', 'aporte_patronal', 'complemento_salud',
    ];

    protected $casts = ['es_excombatiente' => 'boolean'];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    // ── Agregados para reportes ───────────────────────────────────────────────

    public static function resumenPorPeriodo(int $periodoId): array
    {
        return [
            'total'    => self::where('periodo_id', $periodoId)->count(),
            'publicos' => self::where('periodo_id', $periodoId)->where('tipo_empleado', 'publico')->count(),
            'privados' => self::where('periodo_id', $periodoId)->where('tipo_empleado', 'privado')->count(),
            'mujeres'  => self::where('periodo_id', $periodoId)->where('sexo', 'F')->count(),
            'hombres'  => self::where('periodo_id', $periodoId)->where('sexo', 'M')->count(),
            'salario_promedio' => self::where('periodo_id', $periodoId)->avg('salario'),
            'aporte_total'     => self::where('periodo_id', $periodoId)
                ->selectRaw('SUM(aporte_empleado + aporte_patronal) as total')
                ->value('total'),
        ];
    }

    public static function porDepartamento(int $periodoId): \Illuminate\Support\Collection
    {
        return self::where('periodo_id', $periodoId)
            ->selectRaw('departamento_nombre, COUNT(*) as total, AVG(salario) as salario_promedio')
            ->groupBy('departamento_nombre')
            ->orderByDesc('total')
            ->get();
    }
}
