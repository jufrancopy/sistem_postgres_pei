<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * Determinantes Ambientales de Salud — EPHC REG01 (Vivienda).
 *
 * Variables clave:
 * - V08: Agua para beber (riesgo = 7,9,13)
 * - V14B: Combustible cocina (1=Leña → predictor EPOC)
 * - V13: Desagüe (3,4=riesgo sanitario)
 * - V02B: Dormitorios (hacinamiento = TOTAL/V02B > 3)
 * - V24XX: Bienes duraderos (índice NSE)
 */
class DimIndicadoresViviendaDgeec extends Model
{
    protected $table = 'estadistica.dim_indicadores_vivienda_dgeec';

    protected $fillable = [
        'anio', 'departamento_codigo', 'area',
        'hogares_total',
        'hogares_sin_agua_potable', 'pct_sin_agua_potable',
        'hogares_cocina_lena', 'pct_cocina_lena',
        'hogares_sin_desague', 'pct_sin_desague',
        'hogares_sin_electricidad', 'pct_sin_electricidad',
        'hogares_hacinados', 'pct_hacinados',
        'hogares_bajo_nse', 'pct_bajo_nse',
        'hogares_alto_nse', 'pct_alto_nse',
        'hogares_pobre_extremo', 'hogares_pobre', 'pct_pobreza',
        'fuente', 'periodo_referencia', 'notas', 'cargado_por',
    ];

    const DEPARTAMENTOS = DimMpiDgeec::DEPARTAMENTOS;

    // Indicadores con sus descripciones para el UI
    const INDICADORES = [
        'pct_sin_agua_potable' => [
            'label'     => 'Sin agua potable',
            'variable'  => 'V08 ∈ {7,9,13}',
            'riesgo'    => 'EDAs, parasitosis',
            'color'     => 'danger',
        ],
        'pct_cocina_lena' => [
            'label'     => 'Cocina con leña',
            'variable'  => 'V14B = 1',
            'riesgo'    => 'EPOC, asma, enf. respiratorias',
            'color'     => 'warning',
        ],
        'pct_sin_desague' => [
            'label'     => 'Sin desagüe adecuado',
            'variable'  => 'V13 ∈ {3,4}',
            'riesgo'    => 'Enf. gastrointestinales',
            'color'     => 'danger',
        ],
        'pct_hacinados' => [
            'label'     => 'Hacinamiento',
            'variable'  => 'TOTAL/V02B > 3',
            'riesgo'    => 'Enf. infecciosas, TBC',
            'color'     => 'warning',
        ],
        'pct_sin_electricidad' => [
            'label'     => 'Sin electricidad',
            'variable'  => 'V10 = 6',
            'riesgo'    => 'Accidentes, cadena de frío',
            'color'     => 'secondary',
        ],
        'pct_pobreza' => [
            'label'     => 'Pobreza',
            'variable'  => 'POBREZAI ∈ {1,2}',
            'riesgo'    => 'Demanda potencial IPS',
            'color'     => 'info',
        ],
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

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeAnio($query, int $anio)  { return $query->where('anio', $anio); }
    public function scopeTotalPais($query)         { return $query->where('departamento_codigo', 0); }
    public function scopePorDepartamento($query)   { return $query->where('departamento_codigo', '>', 0)->where('area', 'total'); }

    // ── Procesamiento ─────────────────────────────────────────────────────────

    /**
     * Procesa en streaming desde archivo físico (sin cargar en RAM).
     */
    public static function procesarStream(string $ruta, string $extension, int $anio, ?int $cargadoPor = null): int
    {
        if (!file_exists($ruta)) return 0;

        $handle = fopen($ruta, 'r');
        if (!$handle) return 0;

        $agregados   = [];
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
     * Procesa filas del CSV de Vivienda EPHC aplicando FEX.
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

        $dpto    = (int)   ($get('DPTOREP') ?? 0);
        $area    = (int)   ($get('AREA')    ?? 0);
        $fex     = (float) ($get('FEX')     ?? 1);
        $total   = (int)   ($get('TOTAL')   ?? 0);
        $v02b    = max(1, (int) ($get('V02B') ?? 1));
        $v08     = (int)   ($get('V08')     ?? 0);
        $v10     = (int)   ($get('V10')     ?? 0);
        $v13     = (int)   ($get('V13')     ?? 0);
        $v14b    = (int)   ($get('V14B')    ?? 0);
        $v2403   = (int)   ($get('V2403')   ?? 0);
        $v2405   = (int)   ($get('V2405')   ?? 0);
        $v2408   = (int)   ($get('V2408')   ?? 0);
        $v2413   = (int)   ($get('V2413')   ?? 0);
        $v23b    = (int)   ($get('V23B')    ?? 0);
        $pobreza = (int)   ($get('POBREZAI') ?? 0);

        $areaStr = match($area) { 1 => 'urbana', 6 => 'rural', default => 'total' };

        foreach (["{$anio}-0-total", "{$anio}-{$dpto}-total", "{$anio}-{$dpto}-{$areaStr}"] as $key) {
            if (!isset($agregados[$key])) {
                [$a, $d, $ar] = explode('-', $key, 3);
                $agregados[$key] = array_fill_keys([
                    'anio', 'departamento_codigo', 'area',
                    'hogares_total', 'hogares_sin_agua_potable', 'hogares_cocina_lena',
                    'hogares_sin_desague', 'hogares_sin_electricidad', 'hogares_hacinados',
                    'hogares_bajo_nse', 'hogares_alto_nse', 'hogares_pobre_extremo', 'hogares_pobre',
                ], 0);
                $agregados[$key]['anio'] = $anio;
                $agregados[$key]['departamento_codigo'] = (int)$d;
                $agregados[$key]['area'] = $ar;
            }

            $agregados[$key]['hogares_total'] += $fex;
            if (in_array($v08, [7, 9, 13]))    $agregados[$key]['hogares_sin_agua_potable'] += $fex;
            if ($v14b === 1)                    $agregados[$key]['hogares_cocina_lena']       += $fex;
            if (in_array($v13, [3, 4]))         $agregados[$key]['hogares_sin_desague']       += $fex;
            if ($v10 === 6)                     $agregados[$key]['hogares_sin_electricidad']  += $fex;
            if ($total / $v02b > 3)             $agregados[$key]['hogares_hacinados']         += $fex;
            if ($v2403 === 6 && $v2405 === 6 && $v14b === 1) $agregados[$key]['hogares_bajo_nse'] += $fex;
            if ($v2408 === 1 && $v2413 === 1 && $v23b === 1) $agregados[$key]['hogares_alto_nse'] += $fex;
            if ($pobreza === 1) { $agregados[$key]['hogares_pobre_extremo'] += $fex; $agregados[$key]['hogares_pobre'] += $fex; }
            elseif ($pobreza === 2) { $agregados[$key]['hogares_pobre'] += $fex; }
        }
    }

    private static function guardarAgregados(array $agregados, int $anio, ?int $cargadoPor): int
    {
        $guardados = 0;
        foreach ($agregados as $reg) {
            $total = $reg['hogares_total'] ?: 1;
            $pct   = fn($v) => round($v / $total * 100, 4);

            $data = array_merge($reg, [
                'pct_sin_agua_potable'  => $pct($reg['hogares_sin_agua_potable']),
                'pct_cocina_lena'       => $pct($reg['hogares_cocina_lena']),
                'pct_sin_desague'       => $pct($reg['hogares_sin_desague']),
                'pct_sin_electricidad'  => $pct($reg['hogares_sin_electricidad']),
                'pct_hacinados'         => $pct($reg['hogares_hacinados']),
                'pct_bajo_nse'          => $pct($reg['hogares_bajo_nse']),
                'pct_alto_nse'          => $pct($reg['hogares_alto_nse']),
                'pct_pobreza'           => $pct($reg['hogares_pobre']),
                'fuente'                => 'EPHC-DGEEC REG01',
                'periodo_referencia'    => (string) $reg['anio'],
                'cargado_por'           => $cargadoPor,
            ]);

            self::updateOrCreate(
                ['anio' => $data['anio'], 'departamento_codigo' => $data['departamento_codigo'], 'area' => $data['area']],
                $data
            );
            $guardados++;
        }
        return $guardados;
    }
}
