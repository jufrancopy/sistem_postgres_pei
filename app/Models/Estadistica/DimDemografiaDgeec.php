<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * Modelo para datos demográficos procesados de la DGEEC (INE Paraguay).
 * 
 * Esta tabla almacena cálculos agregados de la EPHC, NO los registros crudos.
 * 
 * Variables clave del diccionario EPHC:
 * - Geografía: DPTOREP (0-15), AREA (Urbana/Rural)
 * - PEA: A02-A05 → tablas PP5-PP8
 * - Informalidad: B10, B11 → tabla AP15
 * - Categoría ocupacional: A15 → cruce TR2
 * - Ingresos: B16G, B16T → tabla TR4
 * 
 * @see database/migrations/2026_05_06_000001_create_dim_demografia_dgeec_table.php
 */
class DimDemografiaDgeec extends Model
{
    protected $table = 'estadistica.dim_demografia_dgeec';

    protected $fillable = [
        'anio',
        'departamento_codigo',
        'area',
        // PEA
        'poblacion_total',
        'pea_total',
        'pea_ocupada',
        'pea_desocupada',
        'pei',
        // Informalidad
        'aportantes_ips',
        'aportantes_otras_cajas',
        'no_aportantes',
        'informalidad_total',
        // Categoría ocupacional
        'ocup_empleado_publico',
        'ocup_empleado_privado',
        'ocup_cuenta_propia',
        'ocup_empleador',
        'ocup_trabajador_familiar',
        'ocup_empleado_domestico',
        // Ingresos
        'ingreso_promedio_publico',
        'ingreso_promedio_privado',
        'ingreso_promedio_cuenta_propia',
        'ingreso_mediana_nacional',
        // Pobreza
        'poblacion_pobre',
        'poblacion_pobre_extremo',
        // Pre-jubilación
        'jubilados_encuesta',
        // Metadatos
        'fuente',
        'periodo_referencia',
        'notas_metodologicas',
        'cargado_por',
    ];

    protected $casts = [
        'poblacion_total' => 'decimal:2',
        'pea_total' => 'decimal:2',
        'pea_ocupada' => 'decimal:2',
        'pea_desocupada' => 'decimal:2',
        'pei' => 'decimal:2',
        'aportantes_ips' => 'decimal:2',
        'aportantes_otras_cajas' => 'decimal:2',
        'no_aportantes' => 'decimal:2',
        'informalidad_total' => 'decimal:2',
        'ocup_empleado_publico' => 'decimal:2',
        'ocup_empleado_privado' => 'decimal:2',
        'ocup_cuenta_propia' => 'decimal:2',
        'ocup_empleador' => 'decimal:2',
        'ocup_trabajador_familiar' => 'decimal:2',
        'ocup_empleado_domestico' => 'decimal:2',
        'ingreso_promedio_publico' => 'decimal:2',
        'ingreso_promedio_privado' => 'decimal:2',
        'ingreso_promedio_cuenta_propia' => 'decimal:2',
        'ingreso_mediana_nacional' => 'decimal:2',
        'poblacion_pobre' => 'decimal:2',
        'poblacion_pobre_extremo' => 'decimal:2',
        'jubilados_encuesta' => 'decimal:2',
    ];

    // ─────────────────────────────────────────────────────────────────────
    // Constantes para códigos de departamento (DPTOREP)
    // ─────────────────────────────────────────────────────────────────────
    const DEPARTAMENTOS = [
        0 => 'Total País',
        1 => 'Concepción',
        2 => 'San Pedro',
        3 => 'Cordillera',
        4 => 'Guairá',
        5 => 'Caaguazú',
        6 => 'Caazapa',
        7 => 'Itapúa',
        8 => 'Misiones',
        9 => 'Paraguarí',
        10 => 'Alto Paraná',
        11 => 'Central',
        12 => 'Ñeembucú',
        13 => 'Amambay',
        14 => 'Canindeyú',
        15 => 'Presidente Hayes',
        16 => 'Boquerón',
        17 => 'Alto Paraguay',
        // Nota: 16 y 17 se agrupan como "Chaco" en algunos análisis
    ];

    // ─────────────────────────────────────────────────────────────────────
    // Constantes para códigos de variable EPHC
    // ─────────────────────────────────────────────────────────────────────
    const CODIGOS_A15 = [
        1 => 'empleado_publico',
        2 => 'empleado_privado',
        3 => 'cuenta_propia',
        4 => 'empleador',
        5 => 'trabajador_familiar',
        6 => 'empleado_domestico',
        7 => 'trabajador_exterior_país', // Excluir de análisis IPS
        8 => 'trabajador_exterior_sin_país', // Excluir de análisis IPS
    ];

    const CODIGOS_B11 = [
        1 => 'IPS',
        2 => 'Caja Fiscal',
        3 => 'MSPBS',
        4 => 'Otra caja de jubilación',
        5 => 'Military',
        6 => 'No aporta',
    ];

    // ─────────────────────────────────────────────────────────────────────
    // Relaciones
    // ─────────────────────────────────────────────────────────────────────
    public function cargadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cargado_por');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Accessors
    // ─────────────────────────────────────────────────────────────────────
    public function getDepartamentoNombreAttribute(): string
    {
        return self::DEPARTAMENTOS[$this->departamento_codigo] ?? 'Desconocido';
    }

    public function getTasaPenetracionAttribute(): float
    {
        if ($this->pea_ocupada <= 0) return 0;
        return round(($this->aportantes_ips / $this->pea_ocupada) * 100, 4);
    }

    public function getTasaInformalidadAttribute(): float
    {
        if ($this->pea_ocupada <= 0) return 0;
        return round(($this->informalidad_total / $this->pea_ocupada) * 100, 4);
    }

    public function getTasaOcupacionAttribute(): float
    {
        if ($this->pea_total <= 0) return 0;
        return round(($this->pea_ocupada / $this->pea_total) * 100, 4);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────────────────────────────
    public function scopeAnio($query, int $anio)
    {
        return $query->where('anio', $anio);
    }

    public function scopeDepartamento($query, int $codigo)
    {
        return $query->where('departamento_codigo', $codigo);
    }

    public function scopeArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    public function scopeTotalPais($query)
    {
        return $query->where('departamento_codigo', 0);
    }

    public function scopeUrbana($query)
    {
        return $query->where('area', 'urbana');
    }

    public function scopeRural($query)
    {
        return $query->where('area', 'rural');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Métodos estáticos
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Obtiene los KPIs principales para un año y departamento.
     */
    public static function obtenerKpis(int $anio, ?int $departamento = null): array
    {
        $query = self::where('anio', $anio)
            ->where('area', 'total');

        if ($departamento !== null) {
            $query->where('departamento_codigo', $departamento);
        }

        $dato = $query->first();

        if (!$dato) {
            return [
                'anio' => $anio,
                'encontrado' => false,
            ];
        }

        return [
            'anio' => $anio,
            'departamento' => $dato->departamento_nombre,
            'poblacion_total' => $dato->poblacion_total,
            'pea_total' => $dato->pea_total,
            'pea_ocupada' => $dato->pea_ocupada,
            'aportantes_ips' => $dato->aportantes_ips,
            'tasa_penetracion' => $dato->tasa_penetracion,
            'tasa_informalidad' => $dato->tasa_informalidad,
            'tasa_ocupacion' => $dato->tasa_ocupacion,
            'poblacion_pobre' => $dato->poblacion_pobre,
            'encontrado' => true,
        ];
    }

    /**
     * Compara datos del IPS vs datos EPHC para detectar brechas.
     * 
     * @param int $cotizantesIps Número de cotizantes según tabla interna TR2
     * @param int $anio Año de comparación
     * @param int|null $departamento Código de departamento (null = total país)
     * @return array Análisis de brecha
     */
    public static function analizarBrecha(int $cotizantesIps, int $anio, ?int $departamento = null): array
    {
        $dato = self::where('anio', $anio)
            ->where('area', 'total')
            ->when($departamento, fn($q) => $q->where('departamento_codigo', $departamento))
            ->first();

        if (!$dato) {
            return [
                'error' => 'No hay datos EPHC para el período solicitado',
            ];
        }

        $aportantesEphc = (int) $dato->aportantes_ips;
        $brecha = $cotizantesIps - $aportantesEphc;
        $pctBrecha = $aportantesEphc > 0 
            ? round(($brecha / $aportantesEphc) * 100, 2) 
            : 0;

        $diagnostico = 'Normal';
        if ($pctBrecha > 10) {
            $diagnostico = 'ALERTA: Posibles cotizantes fantasmas o doble empleo';
        } elseif ($pctBrecha < -10) {
            $diagnostico = 'ALERTA: Posible evasión detectada';
        }

        return [
            'anio' => $anio,
            'departamento' => $dato->departamento_nombre,
            'cotizantes_ips_interno' => $cotizantesIps,
            'aportantes_ephc' => $aportantesEphc,
            'brecha' => $brecha,
            'pct_brecha' => $pctBrecha,
            'diagnostico' => $diagnostico,
            'tasa_penetracion_ephc' => $dato->tasa_penetracion,
        ];
    }
}
