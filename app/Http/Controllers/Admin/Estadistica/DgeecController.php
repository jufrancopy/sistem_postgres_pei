<?php

namespace App\Http\Controllers\Admin\Estadistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Estadistica\EphDataset;
use App\Models\Estadistica\DimDemografiaDgeec;

/**
 * Controlador para procesar datos EPHC del INE y cruzarlos con datos IPS.
 * 
 * Diccionario EPHC - Variables clave:
 * - DPTOREP: Departamento (0=Total país, 1-17=Departamentos)
 * - AREA: 1=Urbana, 6=Rural
 * - FEX: Factor de Expansión (CRÍTICO: usar SUM(FEX), no COUNT)
 * - A02-A05: Población Económicamente Activa (PEA)
 * - B10, B11: Informalidad laboral (B11=1 = Aporta al IPS)
 * - A15: Categoría ocupacional (1=Público, 2=Privado, etc.)
 * - B16G, B16T: Ingresos laborales
 * - POBREZAI: Pobreza (1=Pobre, 2=Extremo)
 */
class DgeecController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ════════════════════════════════════════════════════════════════════════
    // DASHBOARD DE CRUCE EPHC-IPS
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Dashboard principal de KPIs calculados.
     */
    public function index(Request $request)
    {
        $anio = $request->anio ?? now()->year;
        $departamento = $request->departamento ?? 0;

        // KPIs del período seleccionado
        $datosEphc = DimDemografiaDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->when($departamento !== 'all', fn($q) => $q->where('departamento_codigo', $departamento))
            ->orderBy('departamento_codigo')
            ->get();

        // Años disponibles
        $anios = DimDemografiaDgeec::selectRaw('DISTINCT anio')
            ->orderByDesc('anio')
            ->pluck('anio');

        // Datos para gráficos
        $chartData = $datosEphc->map(fn($d) => [
            'departamento' => $d->departamento_nombre,
            'pea_ocupada' => (int) $d->pea_ocupada,
            'aportantes_ips' => (int) $d->aportantes_ips,
            'tasa_penetracion' => $d->tasa_penetracion,
            'tasa_informalidad' => $d->tasa_informalidad,
        ]);

        return view('admin.estadisticas.dgeec.index', compact(
            'datosEphc', 'anios', 'anio', 'departamento', 'chartData'
        ));
    }

    // ════════════════════════════════════════════════════════════════════════
    // MAPEO DE COLUMNAS EPHC
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Formulario para mapear columnas del dataset con el diccionario EPHC.
     */
    public function mapear(Request $request, $datasetId)
    {
        $dataset = EphDataset::withDatos()
            ->select(['id', 'titulo', 'anio', 'total_filas', 'columnas', 'datos'])
            ->findOrFail($datasetId);

        $columnasDetectadas = $dataset->columnas ?? [];
        $datosMuestra = array_slice($dataset->datos ?? [], 0, 5);

        // Variables del diccionario EPHC esperadas
        $variablesEphc = $this->getVariablesDiccionario();

        // Intentar detección automática
        $mapeoSugerido = $this->detectarColumnasAutomatico($columnasDetectadas);

        return view('admin.estadisticas.dgeec.mapear', compact(
            'dataset', 'columnasDetectadas', 'datosMuestra', 'variablesEphc', 'mapeoSugerido'
        ));
    }

    /**
     * Procesa el dataset aplicando el mapeo de columnas.
     */
    public function procesar(Request $request, $datasetId)
    {
        $request->validate([
            'anio'               => 'required|integer|min:2000|max:' . (now()->year + 1),
            'periodo_referencia' => 'nullable|string|max:20',
            'fex_column'         => 'nullable|string',
        ]);

        $dataset = EphDataset::select(['id', 'titulo', 'anio', 'total_filas', 'columnas',
                                       'archivo_path', 'archivo_extension'])
            ->findOrFail($datasetId);

        // Detectar columna FEX: primero del form, luego auto-detección
        $fexColumn = $request->fex_column;
        if (empty($fexColumn)) {
            foreach ($dataset->columnas ?? [] as $col) {
                if (in_array(strtoupper($col), ['FEX', 'FEX_P', 'FEX_2022', 'FACPOB', 'FACTOR_EXPANSION'])) {
                    $fexColumn = $col;
                    break;
                }
            }
        }

        if (empty($fexColumn)) {
            return back()
                ->withErrors(['fex_column' => 'No se detectó la columna FEX. Seleccionala manualmente.'])
                ->withInput();
        }

        // Mapeo de columnas
        $mapeo = [
            'fex'     => $fexColumn,
            'dptorep' => $request->dptorep_column ?: $this->autoDetectar($dataset->columnas, ['DPTOREP', 'DPTO', 'DEPARTAMENTO']),
            'area'    => $request->area_column    ?: $this->autoDetectar($dataset->columnas, ['AREA']),
            'a02'     => $request->a02_column,
            'a03'     => $request->a03_column,
            'a04'     => $request->a04_column,
            'a05'     => $request->a05_column,
            'a15'     => $request->a15_column,
            'b10'     => $request->b10_column,
            'b11'     => $request->b11_column,
            'b16t'    => $request->b16t_column,
            'pobrezai'=> $request->pobrezai_column ?: $this->autoDetectar($dataset->columnas, ['POBREZAI', 'pobrezai']),
            'a18'     => $request->a18_column,
        ];

        // Procesar — desde archivo en streaming si existe, sino desde BD
        $resultados = [];
        if ($dataset->archivo_path) {
            $ruta = storage_path('app/' . $dataset->archivo_path);
            $resultados = $this->calcularAgregadosStream($ruta, $dataset->archivo_extension, $mapeo, $request->anio);
        } else {
            $datasetConDatos = EphDataset::withDatos()->find($datasetId);
            $resultados = $this->calcularAgregados($datasetConDatos->datos ?? [], $mapeo, $request->anio);
        }

        // Guardar en dim_demografia_dgeec
        $guardados = 0;
        foreach ($resultados as $registro) {
            $registro['fuente']              = 'EPHC-DGEEC';
            $registro['periodo_referencia']  = $request->periodo_referencia ?? "{$request->anio}";
            $registro['cargado_por']         = Auth::id();

            DimDemografiaDgeec::updateOrCreate(
                [
                    'anio'                => $registro['anio'],
                    'departamento_codigo' => $registro['departamento_codigo'],
                    'area'                => $registro['area'],
                ],
                $registro
            );
            $guardados++;
        }

        return redirect()->route('siess.dgeec.index')
            ->with('success', "Dataset procesado: {$guardados} registros agregados calculados.");
    }

    /**
     * Auto-detecta una columna buscando candidatos en la lista de columnas del dataset.
     */
    private function autoDetectar(?array $columnas, array $candidatos): ?string
    {
        foreach ($candidatos as $c) {
            foreach ($columnas ?? [] as $col) {
                if (strtoupper($col) === strtoupper($c)) return $col;
            }
        }
        return null;
    }

    /**
     * Calcula agregados en streaming desde archivo (sin cargar en RAM).
     */
    private function calcularAgregadosStream(string $ruta, string $extension, array $mapeo, int $anio): array
    {
        if (!file_exists($ruta)) return [];

        $handle = fopen($ruta, 'r');
        if (!$handle) return [];

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

                $this->acumularFilaDemografia($fila, $mapeo, $agregados, $anio);
            }
        }

        fclose($handle);
        return $agregados;
    }

    /**
     * Acumula una fila en los agregados de demografía.
     */
    private function acumularFilaDemografia(array $fila, array $mapeo, array &$agregados, int $anio): void
    {
        $get = function($key) use ($fila, $mapeo) {
            if (!isset($mapeo[$key])) return null;
            $col = $mapeo[$key];
            return $fila[$col] ?? $fila[strtoupper($col)] ?? $fila[strtolower($col)] ?? null;
        };

        $dpto    = (int)   ($get('dptorep') ?? 0);
        $areaCod = (int)   ($get('area')    ?? 0);
        $fex     = (float) ($get('fex')     ?? 1);

        $areaStr = match($areaCod) { 1 => 'urbana', 6 => 'rural', default => 'total' };

        $keys = ["{$anio}-0-total", "{$anio}-{$dpto}-total", "{$anio}-{$dpto}-{$areaStr}"];

        foreach ($keys as $key) {
            if (!isset($agregados[$key])) {
                [$a, $d, $ar] = explode('-', $key, 3);
                $agregados[$key] = $this->inicializarRegistro($anio, (int)$d, $ar);
            }

            $agregados[$key]['poblacion_total'] += $fex;

            if ((int)($get('a02') ?? 0) === 1) $agregados[$key]['pea_total']      += $fex;
            if ((int)($get('a03') ?? 0) === 1) $agregados[$key]['pea_ocupada']    += $fex;
            if ((int)($get('a04') ?? 0) === 1) $agregados[$key]['pea_desocupada'] += $fex;
            if ((int)($get('a05') ?? 0) === 1) $agregados[$key]['pei']            += $fex;

            $b11 = (int)($get('b11') ?? 0);
            if ($b11 === 1)                    $agregados[$key]['aportantes_ips']         += $fex;
            if (in_array($b11, [2,3,4,5]))     $agregados[$key]['aportantes_otras_cajas'] += $fex;

            $b10 = (int)($get('b10') ?? 0);
            if ($b10 === 6)                    $agregados[$key]['no_aportantes'] += $fex;

            if ((int)($get('a03') ?? 0) === 1 && $b11 !== 1) {
                $agregados[$key]['informalidad_total'] += $fex;
            }

            $a15 = (int)($get('a15') ?? 0);
            if ($a15 >= 1 && $a15 <= 6 && (int)($get('a03') ?? 0) === 1) {
                $campo = match($a15) {
                    1 => 'ocup_empleado_publico', 2 => 'ocup_empleado_privado',
                    3 => 'ocup_cuenta_propia',    4 => 'ocup_empleador',
                    5 => 'ocup_trabajador_familiar', 6 => 'ocup_empleado_domestico',
                    default => null,
                };
                if ($campo) $agregados[$key][$campo] += $fex;
            }

            $pobreza = (int)($get('pobrezai') ?? 0);
            if ($pobreza === 1)      $agregados[$key]['poblacion_pobre_extremo'] += $fex;
            if (in_array($pobreza, [1,2])) $agregados[$key]['poblacion_pobre']  += $fex;

            if ((int)($get('a18') ?? 0) === 8) $agregados[$key]['jubilados_encuesta'] += $fex;
        }
    }

    // ════════════════════════════════════════════════════════════════════════
    // ANÁLISIS DE BRECHAS
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Compara datos IPS vs datos EPHC para detectar evasión/cotizantes fantasmas.
     */
    public function brecha(Request $request)
    {
        $anio = $request->anio ?? now()->year;

        // Datos EPHC
        $datosEphc = DimDemografiaDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->orderBy('departamento_codigo')
            ->get();

        // TODO: Conectar con tabla interna AOP (cotizantes reales IPS)
        // Por ahora usamos datos de ejemplo
        $cotizantesIps = [
            0 => 1200000,    // Total país
            11 => 500000,    // Central
            10 => 180000,    // Alto Paraná
            7 => 120000,     // Itapúa
            // ... otros departamentos
        ];

        $analisis = $datosEphc->map(function($dato) use ($cotizantesIps) {
            $cotizantes = $cotizantesIps[$dato->departamento_codigo] ?? 0;
            $aportantesEphc = (int) $dato->aportantes_ips;
            
            $brecha = $cotizantes - $aportantesEphc;
            $pctBrecha = $aportantesEphc > 0 
                ? round(($brecha / $aportantesEphc) * 100, 2) 
                : 0;

            $diagnostico = 'Normal';
            if ($pctBrecha > 10) {
                $diagnostico = '⚠️ Cotizantes fantasmas posible';
            } elseif ($pctBrecha < -10) {
                $diagnostico = '⚠️ Evasión probable';
            }

            return [
                'departamento' => $dato->departamento_nombre,
                'pea_ocupada' => $dato->pea_ocupada,
                'aportantes_ephc' => $aportantesEphc,
                'cotizantes_ips' => $cotizantes,
                'brecha' => $brecha,
                'pct_brecha' => $pctBrecha,
                'tasa_penetracion_ephc' => $dato->tasa_penetracion,
                'tasa_informalidad' => $dato->tasa_informalidad,
                'diagnostico' => $diagnostico,
            ];
        });

        $anios = DimDemografiaDgeec::selectRaw('DISTINCT anio')
            ->orderByDesc('anio')
            ->pluck('anio');

        return view('admin.estadisticas.dgeec.brecha', compact('analisis', 'anios', 'anio'));
    }

    // ════════════════════════════════════════════════════════════════════════
    // API PARA GRÁFICOS
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Datos para gráfico de penetración por departamento.
     */
    public function chartPenetracion(Request $request)
    {
        $anio = $request->anio ?? now()->year;

        $datos = DimDemografiaDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->where('departamento_codigo', '>', 0) // Excluir total país
            ->orderByDesc('pea_ocupada')
            ->get();

        return response()->json([
            'labels' => $datos->pluck('departamento_nombre'),
            'pea_ocupada' => $datos->pluck('pea_ocupada'),
            'aportantes_ips' => $datos->pluck('aportantes_ips'),
            'tasa_penetracion' => $datos->map(fn($d) => $d->tasa_penetracion),
        ]);
    }

    /**
     * Datos para gráfico de informalidad.
     */
    public function chartInformalidad(Request $request)
    {
        $anio = $request->anio ?? now()->year;

        $datos = DimDemografiaDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->where('departamento_codigo', '>', 0)
            ->orderByDesc('informalidad_total')
            ->limit(10)
            ->get();

        return response()->json([
            'labels' => $datos->pluck('departamento_nombre'),
            'formales' => $datos->map(fn($d) => $d->pea_ocupada - $d->informalidad_total),
            'informales' => $datos->pluck('informalidad_total'),
            'tasa' => $datos->map(fn($d) => $d->tasa_informalidad),
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // MÉTODOS PRIVADOS
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Variables del diccionario EPHC.
     */
    private function getVariablesDiccionario(): array
    {
        return [
            'GEOGRAFIA' => [
                'DPTOREP' => [
                    'nombre' => 'Departamento',
                    'descripcion' => 'Código de departamento (0=Total país, 1-17)',
                    'valores' => DimDemografiaDgeec::DEPARTAMENTOS,
                    'requerido' => true,
                ],
                'AREA' => [
                    'nombre' => 'Área geográfica',
                    'descripcion' => '1=Urbana, 6=Rural',
                    'valores' => [1 => 'Urbana', 6 => 'Rural'],
                    'requerido' => true,
                ],
            ],
            'POBLACION' => [
                'FEX' => [
                    'nombre' => 'Factor de Expansión',
                    'descripcion' => '⚠️ CRÍTICO: Peso estadístico. Usar SUM(FEX), nunca COUNT.',
                    'requerido' => true,
                ],
                'A02' => [
                    'nombre' => '¿Es PEA?',
                    'descripcion' => '1=Sí (Población Económicamente Activa)',
                    'valores' => [1 => 'Sí', 0 => 'No'],
                ],
                'A03' => [
                    'nombre' => '¿Está ocupado?',
                    'descripcion' => '1=Sí (PEA Ocupada)',
                    'valores' => [1 => 'Sí', 0 => 'No'],
                ],
                'A04' => [
                    'nombre' => '¿Está desocupado?',
                    'descripcion' => '1=Sí (PEA Desocupada)',
                    'valores' => [1 => 'Sí', 0 => 'No'],
                ],
                'A05' => [
                    'nombre' => '¿Es PEI?',
                    'descripcion' => '1=Sí (Población Económicamente Inactiva)',
                    'valores' => [1 => 'Sí', 0 => 'No'],
                ],
            ],
            'INFORMALIDAD' => [
                'B10' => [
                    'nombre' => '¿Aporta a caja de jubilación?',
                    'descripcion' => 'Determina informalidad laboral',
                    'valores' => [1 => 'Sí', 6 => 'No aporta', 9 => 'NS/NR'],
                ],
                'B11' => [
                    'nombre' => '¿A cuál caja aporta?',
                    'descripcion' => '⚠️ CRÍTICO: 1=IPS, otros=Otras cajas/informal',
                    'valores' => DimDemografiaDgeec::CODIGOS_B11,
                ],
            ],
            'CATEGORIA_OCUP' => [
                'A15' => [
                    'nombre' => 'Categoría ocupacional',
                    'descripcion' => 'Tipo de empleo (excluir categorías 7 y 8)',
                    'valores' => DimDemografiaDgeec::CODIGOS_A15,
                ],
            ],
            'INGRESOS' => [
                'B16G' => [
                    'nombre' => 'Ingreso en Guaraníes',
                    'descripcion' => 'Monto del ingreso laboral',
                ],
                'B16T' => [
                    'nombre' => 'Ingreso total mensual',
                    'descripcion' => 'Para cruce con tabla TR4 (SML)',
                ],
            ],
            'POBREZA' => [
                'POBREZAI' => [
                    'nombre' => 'Pobreza',
                    'descripcion' => 'Para cruce con red de establecimientos',
                    'valores' => [0 => 'No pobre', 1 => 'Pobre', 2 => 'Pobre extremo'],
                ],
            ],
            'JUBILACION' => [
                'A18' => [
                    'nombre' => 'Razón por la que dejó el trabajo',
                    'descripcion' => 'Código 8 = Se jubiló (para estimar demanda)',
                ],
            ],
        ];
    }

    /**
     * Detecta automáticamente columnas del JSON con variables EPHC.
     */
    private function detectarColumnasAutomatico(array $columnas): array
    {
        $mapeo = [];

        foreach ($columnas as $col) {
            $colUpper = strtoupper($col);
            $colLower = strtolower($col);

            // Buscar coincidencias
            if (in_array(strtoupper($col), ['FEX', 'FEX_P', 'FEX_2022', 'FACPOB', 'FACTOR_EXPANSION', 'FACTOR'])) {
                    $mapeo['fex'] = $col;
                }
                if (strpos($colUpper, 'DPTO') !== false || $colUpper === 'DEPARTAMENTO' || $colUpper === 'DPTOREP') {
                $mapeo['dptorep'] = $col;
            }
            if ($colUpper === 'AREA' || strpos($colUpper, 'URBANO') !== false) {
                $mapeo['area'] = $col;
            }
            if ($colUpper === 'A02') $mapeo['a02'] = $col;
            if ($colUpper === 'A03') $mapeo['a03'] = $col;
            if ($colUpper === 'A04') $mapeo['a04'] = $col;
            if ($colUpper === 'A05') $mapeo['a05'] = $col;
            if ($colUpper === 'A15') $mapeo['a15'] = $col;
            if ($colUpper === 'B10') $mapeo['b10'] = $col;
            if ($colUpper === 'B11') $mapeo['b11'] = $col;
            if (in_array($colUpper, ['B16T', 'B16', 'INGRESO', 'INGRESO_TOTAL'])) {
                $mapeo['b16t'] = $col;
            }
            if (strpos($colUpper, 'POBRE') !== false) {
                $mapeo['pobrezai'] = $col;
            }
            if ($colUpper === 'A18') $mapeo['a18'] = $col;
        }

        return $mapeo;
    }

    /**
     * Calcula agregados aplicando factor de expansión.
     * 
     * REGLA: Siempre SUM(FEX), nunca COUNT de registros.
     */
    private function calcularAgregados(array $datos, array $mapeo, int $anio): array
    {
        $agregados = [];

        // Normalizar acceso a columnas (case-insensitive)
        $get = function($fila, $key) use ($mapeo) {
            if (!isset($mapeo[$key])) return null;
            $col = $mapeo[$key];
            return $fila[$col] ?? $fila[strtoupper($col)] ?? $fila[strtolower($col)] ?? null;
        };

        foreach ($datos as $fila) {
            // Dimensiones
            $dpto = (int) ($get($fila, 'dptorep') ?? 0);
            $areaCod = (int) ($get($fila, 'area') ?? 0);
            $fex = (float) ($get($fila, 'fex') ?? 1);

            // Normalizar área
            $areaStr = match($areaCod) {
                1 => 'urbana',
                6 => 'rural',
                default => 'total',
            };

            // Keys para agregación
            $keys = [
                "{$anio}-0-total",      // Total país
                "{$anio}-{$dpto}-total", // Departamento total
                "{$anio}-{$dpto}-{$areaStr}", // Departamento + Área
            ];

            // Inicializar si no existe
            foreach ($keys as $key) {
                if (!isset($agregados[$key])) {
                    [$a, $d, $ar] = explode('-', $key);
                    $agregados[$key] = $this->inicializarRegistro($anio, (int)$d, $ar);
                }
            }

            // ── Acumular usando FEX ───────────────────────────────────────
            foreach ($keys as $key) {
                // Población total
                $agregados[$key]['poblacion_total'] += $fex;

                // PEA (A02=1)
                if ((int)($get($fila, 'a02') ?? 0) === 1) {
                    $agregados[$key]['pea_total'] += $fex;
                }

                // PEA Ocupada (A03=1)
                if ((int)($get($fila, 'a03') ?? 0) === 1) {
                    $agregados[$key]['pea_ocupada'] += $fex;
                }

                // PEA Desocupada (A04=1)
                if ((int)($get($fila, 'a04') ?? 0) === 1) {
                    $agregados[$key]['pea_desocupada'] += $fex;
                }

                // PEI (A05=1)
                if ((int)($get($fila, 'a05') ?? 0) === 1) {
                    $agregados[$key]['pei'] += $fex;
                }

                // Aportantes IPS (B11=1)
                $b11 = (int)($get($fila, 'b11') ?? 0);
                if ($b11 === 1) {
                    $agregados[$key]['aportantes_ips'] += $fex;
                }

                // Aportantes otras cajas (B11 en 2,3,4,5)
                if (in_array($b11, [2, 3, 4, 5])) {
                    $agregados[$key]['aportantes_otras_cajas'] += $fex;
                }

                // No aportantes (B10=6)
                $b10 = (int)($get($fila, 'b10') ?? 0);
                if ($b10 === 6) {
                    $agregados[$key]['no_aportantes'] += $fex;
                }

                // Informalidad (ocupado pero no aporta al IPS)
                if ((int)($get($fila, 'a03') ?? 0) === 1 && $b11 !== 1) {
                    $agregados[$key]['informalidad_total'] += $fex;
                }

                // Categoría ocupacional (A15)
                $a15 = (int)($get($fila, 'a15') ?? 0);
                if ($a15 >= 1 && $a15 <= 6 && (int)($get($fila, 'a03') ?? 0) === 1) {
                    $campoA15 = match($a15) {
                        1 => 'ocup_empleado_publico',
                        2 => 'ocup_empleado_privado',
                        3 => 'ocup_cuenta_propia',
                        4 => 'ocup_empleador',
                        5 => 'ocup_trabajador_familiar',
                        6 => 'ocup_empleado_domestico',
                        default => null,
                    };
                    if ($campoA15) {
                        $agregados[$key][$campoA15] += $fex;
                    }
                }

                // Pobreza
                $pobreza = (int)($get($fila, 'pobrezai') ?? 0);
                if ($pobreza === 1) {
                    $agregados[$key]['poblacion_pobre'] += $fex;
                } elseif ($pobreza === 2) {
                    $agregados[$key]['poblacion_pobre_extremo'] += $fex;
                }

                // Jubilados (A18=8)
                if ((int)($get($fila, 'a18') ?? 0) === 8) {
                    $agregados[$key]['jubilados_encuesta'] += $fex;
                }
            }
        }

        return $agregados;
    }

    /**
     * Inicializa un registro de agregación.
     */
    private function inicializarRegistro(int $anio, int $dpto, string $area): array
    {
        return [
            'anio' => $anio,
            'departamento_codigo' => $dpto,
            'area' => $area,
            'poblacion_total' => 0,
            'pea_total' => 0,
            'pea_ocupada' => 0,
            'pea_desocupada' => 0,
            'pei' => 0,
            'aportantes_ips' => 0,
            'aportantes_otras_cajas' => 0,
            'no_aportantes' => 0,
            'informalidad_total' => 0,
            'ocup_empleado_publico' => 0,
            'ocup_empleado_privado' => 0,
            'ocup_cuenta_propia' => 0,
            'ocup_empleador' => 0,
            'ocup_trabajador_familiar' => 0,
            'ocup_empleado_domestico' => 0,
            'ingreso_promedio_publico' => null,
            'ingreso_promedio_privado' => null,
            'ingreso_promedio_cuenta_propia' => null,
            'ingreso_mediana_nacional' => null,
            'poblacion_pobre' => 0,
            'poblacion_pobre_extremo' => 0,
            'jubilados_encuesta' => 0,
        ];
    }
}
