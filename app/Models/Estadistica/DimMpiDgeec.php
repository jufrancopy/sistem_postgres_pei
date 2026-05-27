<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * Índice de Pobreza Multidimensional 2024 (DGEEC).
 *
 * El MPI ya tiene los indicadores pre-calculados por hogar.
 * La variable clave para el IPS es hh_d_no_afil (sin afiliación a seguro).
 */
class DimMpiDgeec extends Model
{
    protected $table = 'estadistica.dim_mpi_dgeec';

    protected $fillable = [
        'anio', 'departamento_codigo', 'area',
        'incidencia_h', 'intensidad_a', 'mpi_m0',
        'd_ni_noasis', 'd_esc_retardada', 'd_logro_min',
        'd_sin_salud', 'd_no_afil', 'd_jubi_pens',
        'd_destotalmax', 'd_subocup_max', 'd_10a17_ocup',
        'd_materialidad', 'd_hacinamiento', 'd_sin_basur',
        'd_agua_mejor', 'd_san_mejor', 'd_combus',
        'hogares_mpi_pobres', 'hogares_total',
        'fuente', 'periodo_referencia', 'notas', 'cargado_por',
    ];

    protected $casts = [
        'incidencia_h' => 'decimal:4',
        'intensidad_a' => 'decimal:4',
        'mpi_m0'       => 'decimal:4',
        'd_no_afil'    => 'decimal:4',
        'd_sin_salud'  => 'decimal:4',
        'd_jubi_pens'  => 'decimal:4',
        'd_combus'     => 'decimal:4',
        'd_agua_mejor' => 'decimal:4',
        'd_hacinamiento' => 'decimal:4',
    ];

    // Nombres de departamentos
    const DEPARTAMENTOS = [
        0 => 'Total País', 1 => 'Concepción', 2 => 'San Pedro',
        3 => 'Cordillera', 4 => 'Guairá', 5 => 'Caaguazú',
        6 => 'Caazapa', 7 => 'Itapúa', 8 => 'Misiones',
        9 => 'Paraguarí', 10 => 'Alto Paraná', 11 => 'Central',
        12 => 'Ñeembucú', 13 => 'Amambay', 14 => 'Canindeyú',
        15 => 'Presidente Hayes', 16 => 'Boquerón', 17 => 'Alto Paraguay',
    ];

    // Dimensiones del MPI con sus pesos
    const DIMENSIONES = [
        // Educación
        'd_ni_noasis'     => ['label' => 'Niños sin escuela',        'dimension' => 'educacion', 'peso' => 1/15],
        'd_esc_retardada' => ['label' => 'Escolaridad retrasada',    'dimension' => 'educacion', 'peso' => 1/15],
        'd_logro_min'     => ['label' => 'Sin logro educativo',      'dimension' => 'educacion', 'peso' => 1/15],
        // Salud
        'd_sin_salud'     => ['label' => 'Sin acceso a salud',       'dimension' => 'salud',     'peso' => 1/15],
        'd_no_afil'       => ['label' => 'Sin afiliación a seguro',  'dimension' => 'salud',     'peso' => 1/15],
        'd_jubi_pens'     => ['label' => 'Sin jubilación/pensión',   'dimension' => 'salud',     'peso' => 1/15],
        // Trabajo
        'd_destotalmax'   => ['label' => 'Desempleo en el hogar',    'dimension' => 'trabajo',   'peso' => 1/15],
        'd_subocup_max'   => ['label' => 'Subocupación',             'dimension' => 'trabajo',   'peso' => 1/15],
        'd_10a17_ocup'    => ['label' => 'Trabajo infantil',         'dimension' => 'trabajo',   'peso' => 1/15],
        // Vivienda
        'd_materialidad'  => ['label' => 'Materialidad deficiente',  'dimension' => 'vivienda',  'peso' => 1/15],
        'd_hacinamiento'  => ['label' => 'Hacinamiento',             'dimension' => 'vivienda',  'peso' => 1/15],
        'd_sin_basur'     => ['label' => 'Sin recolección basura',   'dimension' => 'vivienda',  'peso' => 1/15],
        'd_agua_mejor'    => ['label' => 'Sin agua mejorada',        'dimension' => 'vivienda',  'peso' => 1/15],
        'd_san_mejor'     => ['label' => 'Sin saneamiento',          'dimension' => 'vivienda',  'peso' => 1/15],
        'd_combus'        => ['label' => 'Combustible inadecuado',   'dimension' => 'vivienda',  'peso' => 1/15],
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────
    public function cargadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cargado_por');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────
    public function getDepartamentoNombreAttribute(): string
    {
        return self::DEPARTAMENTOS[$this->departamento_codigo] ?? 'Desconocido';
    }

    public function getNivelRiesgoAttribute(): string
    {
        $mpi = (float) $this->mpi_m0;
        if ($mpi >= 0.15) return 'alto';
        if ($mpi >= 0.08) return 'medio';
        return 'bajo';
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeAnio($query, int $anio)       { return $query->where('anio', $anio); }
    public function scopeTotalPais($query)              { return $query->where('departamento_codigo', 0); }
    public function scopePorDepartamento($query)        { return $query->where('departamento_codigo', '>', 0)->where('area', 'total'); }

    // ── Métodos estáticos ─────────────────────────────────────────────────────

    /**
     * Procesa el CSV del MPI en streaming (fila por fila, sin cargar en RAM).
     */
    public static function procesarStream(string $ruta, string $extension, int $anio, ?int $cargadoPor = null): int
    {
        if (!file_exists($ruta)) return 0;

        $agregados = [];
        $handle    = fopen($ruta, 'r');
        if (!$handle) return 0;

        $encabezados = [];

        if ($extension === 'csv') {
            $primeraLinea = fgets($handle);
            $primeraLinea = ltrim($primeraLinea, "\xEF\xBB\xBF");
            $separador    = substr_count($primeraLinea, ';') >= substr_count($primeraLinea, ',') ? ';' : ',';
            $encabezados  = array_map('trim', str_getcsv($primeraLinea, $separador));

            while (($linea = fgets($handle)) !== false) {
                $linea = trim($linea);
                if (empty($linea)) continue;

                $valores = str_getcsv($linea, $separador);
                $fila    = [];
                foreach ($encabezados as $i => $col) {
                    $val = $valores[$i] ?? null;
                    $fila[$col] = is_numeric($val) ? (strpos($val, '.') !== false ? (float)$val : (int)$val) : $val;
                }

                self::acumularFila($fila, $agregados, $anio);
            }
        }

        fclose($handle);
        return self::guardarAgregados($agregados, $anio, $cargadoPor);
    }

    /**
     * Procesa el CSV del MPI y calcula agregados por departamento/área.
     * El MPI ya tiene indicadores pre-calculados — solo agrupamos con SUM(FEX).
     */
    public static function procesarCsv(array $filas, int $anio, ?int $cargadoPor = null): int
    {
        $agregados = [];
        foreach ($filas as $fila) {
            self::acumularFila((array)$fila, $agregados, $anio);
        }
        return self::guardarAgregados($agregados, $anio, $cargadoPor);
    }

    private static function acumularFila(array $fila, array &$agregados, int $anio): void
    {
        $get = fn($k) => $fila[$k] ?? $fila[strtolower($k)] ?? $fila[strtoupper($k)] ?? null;

        $dpto       = (int)   ($get('dpto')          ?? 0);
        $area       = (int)   ($get('area')           ?? 0);
        $fex        = (float) ($get('fex_2022')       ?? $get('facpob') ?? $get('FEX') ?? 1);
        $esMpiPobre = (int)   ($get('multid_poor_26') ?? 0);

        $areaStr = match($area) { 1 => 'urbana', 6 => 'rural', default => 'total' };

        foreach (["{$anio}-0-total", "{$anio}-{$dpto}-total", "{$anio}-{$dpto}-{$areaStr}"] as $key) {
            if (!isset($agregados[$key])) {
                [$a, $d, $ar] = explode('-', $key, 3);
                $agregados[$key] = [
                    'anio' => $anio, 'departamento_codigo' => (int)$d, 'area' => $ar,
                    'hogares_total' => 0, 'hogares_mpi_pobres' => 0,
                    '_sum_h' => 0, '_sum_a' => 0, '_sum_m0' => 0, '_n' => 0,
                ];
                foreach (array_keys(self::DIMENSIONES) as $dim) {
                    $agregados[$key]["_sum_{$dim}"] = 0;
                }
            }

            $agregados[$key]['hogares_total']    += $fex;
            $agregados[$key]['_n']               += $fex;
            if ($esMpiPobre) $agregados[$key]['hogares_mpi_pobres'] += $fex;

            $agregados[$key]['_sum_h']  += (float)($fila['H_26']  ?? $fila['h_26']  ?? 0) * $fex;
            $agregados[$key]['_sum_a']  += (float)($fila['A_26']  ?? $fila['a_26']  ?? 0) * $fex;
            $agregados[$key]['_sum_m0'] += (float)($fila['M0_26'] ?? $fila['m0_26'] ?? 0) * $fex;

            foreach (array_keys(self::DIMENSIONES) as $dim) {
                $colCsv = str_replace('d_', 'hh_d_', $dim);
                $val = $fila[$colCsv] ?? $fila[strtoupper($colCsv)] ?? 0;
                $agregados[$key]["_sum_{$dim}"] += (float)$val * $fex;
            }
        }
    }

    private static function guardarAgregados(array $agregados, int $anio, ?int $cargadoPor): int
    {
        $guardados = 0;
        foreach ($agregados as $registro) {
            $n = $registro['_n'] ?: 1;

            $data = [
                'anio'                => $registro['anio'],
                'departamento_codigo' => $registro['departamento_codigo'],
                'area'                => $registro['area'],
                'hogares_total'       => $registro['hogares_total'],
                'hogares_mpi_pobres'  => $registro['hogares_mpi_pobres'],
                'incidencia_h'        => round($registro['_sum_h']  / $n, 6),
                'intensidad_a'        => round($registro['_sum_a']  / $n, 6),
                'mpi_m0'              => round($registro['_sum_m0'] / $n, 6),
                'fuente'              => 'MPI-DGEEC 2024',
                'periodo_referencia'  => (string) $anio,
                'cargado_por'         => $cargadoPor,
            ];

            foreach (array_keys(self::DIMENSIONES) as $dim) {
                $data[$dim] = round($registro["_sum_{$dim}"] / $n, 6);
            }

            self::updateOrCreate(
                ['anio' => $data['anio'], 'departamento_codigo' => $data['departamento_codigo'], 'area' => $data['area']],
                $data
            );
            $guardados++;
        }
        return $guardados;
    }
}
