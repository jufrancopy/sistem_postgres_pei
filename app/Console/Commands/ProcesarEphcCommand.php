<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Estadistica\EphDataset;
use App\Models\Estadistica\DimDemografiaDgeec;

/**
 * Comando para procesar datasets EPHC y poblar la tabla dim_demografia_dgeec.
 * 
 * REGLA CRÍTICA: La EPHC es una muestra, no un censo.
 * Cada persona tiene un FACTOR DE EXPANSIÓN (FEX) que representa su peso estadístico.
 * 
 * NO usar COUNT() de registros.
 * SIEMPRE usar SUM(FEX) para proyectar a nivel nacional.
 * 
 * @see database/migrations/2026_05_06_000001_create_dim_demografia_dgeec_table.php
 */
class ProcesarEphcCommand extends Command
{
    protected $signature = 'ephc:procesar 
                            {--dataset= : ID del dataset EPH a procesar}
                            {--anio= : Año de los datos (ej: 2024)}
                            {--periodo= : Período de referencia (ej: 2024-III)}
                            {--dry-run : Solo mostrar qué se procesaría, sin guardar}';

    protected $description = 'Procesa datasets EPHC aplicando factor de expansión (FEX) y pobla dim_demografia_dgeec';

    // Mapeo de códigos DPTOREP a nombres
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
    ];

    // Mapeo de códigos AREA
    const AREAS = [
        1 => 'urbana',
        6 => 'rural',
    ];

    public function handle(): int
    {
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('  PROCESADOR EPHC - Factor de Expansión (FEX)');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Buscar dataset
        $datasetId = $this->option('dataset');
        $dataset = null;

        if ($datasetId) {
            $dataset = EphDataset::find($datasetId);
        } else {
            // Mostrar datasets disponibles
            $datasets = EphDataset::orderBy('anio', 'desc')->get();
            
            if ($datasets->isEmpty()) {
                $this->error('No hay datasets EPH cargados. Use primero el importador.');
                return self::FAILURE;
            }

            $this->newLine();
            $this->info('Datasets disponibles:');
            foreach ($datasets as $d) {
                $this->line("  [{$d->id}] {$d->titulo} ({$d->anio}) - {$d->total_filas} filas");
            }

            $datasetId = $this->ask('Seleccione el ID del dataset a procesar');
            $dataset = EphDataset::find($datasetId);

            if (!$dataset) {
                $this->error('Dataset no encontrado.');
                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->info("Procesando: {$dataset->titulo}");
        $this->line("  Filas: {$dataset->total_filas}");
        $this->line("  Año: {$dataset->anio}");

        // Verificar estructura del JSON
        $datos = $dataset->datos;
        if (empty($datos)) {
            $this->error('El dataset no contiene datos.');
            return self::FAILURE;
        }

        // Detectar columnas disponibles
        $columnas = $this->detectarColumnasEPHC($datos);
        $this->newLine();
        $this->info('Columnas detectadas:');
        foreach ($columnas as $col => $descripcion) {
            $this->line("  - {$col}: {$descripcion}");
        }

        // Verificar columna FEX (CRÍTICO)
        $fexCol = $this->buscarColumnaFex(array_keys($columnas));
        if (!$fexCol) {
            $this->warn('⚠️  No se detectó columna FEX (factor de expansión).');
            $fexCol = $this->ask('Ingrese el nombre de la columna de factor de expansión', 'FEX');
        }
        $this->info("  Factor de expansión: {$fexCol}");

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->warn('MODO DRY-RUN - No se guardarán cambios');
            $this->mostrarVistaPrevia($datos, $fexCol);
            return self::SUCCESS;
        }

        // Procesar por departamento y área
        $this->newLine();
        $this->info('Procesando agregados...');

        $resultados = $this->calcularAgregados($datos, $fexCol, $dataset->anio);

        // Guardar en dim_demografia_dgeec
        $guardados = 0;
        foreach ($resultados as $registro) {
            $registro['fuente'] = 'EPHC-DGEEC';
            $registro['periodo_referencia'] = $this->option('periodo') ?? "{$dataset->anio}";
            $registro['cargado_por'] = $this->option('dry-run') ? null : (auth()->id() ?? 1);

            DimDemografiaDgeec::updateOrCreate(
                [
                    'anio' => $registro['anio'],
                    'departamento_codigo' => $registro['departamento_codigo'],
                    'area' => $registro['area'],
                ],
                $registro
            );
            $guardados++;
        }

        $this->newLine();
        $this->info("✓ {$guardados} registros guardados en dim_demografia_dgeec");

        // Mostrar resumen
        $this->mostrarResumen($resultados);

        return self::SUCCESS;
    }

    /**
     * Detecta columnas típicas de la EPHC.
     */
    private function detectarColumnasEPHC(array $datos): array
    {
        if (empty($datos)) return [];

        $primera = $datos[0] ?? [];
        $columnas = [];

        $mapeo = [
            'DPTOREP' => 'Código de departamento (0-17)',
            'AREA' => 'Área geográfica (1=Urbana, 6=Rural)',
            'FEX' => 'Factor de expansión (peso estadístico)',
            'FEX_P' => 'Factor de expansión alternativo',
            'A02' => '¿Es PEA? (1=Sí)',
            'A03' => '¿Está ocupado? (1=Sí)',
            'A04' => '¿Está desocupado? (1=Sí)',
            'A05' => '¿Es PEI? (1=Sí)',
            'A15' => 'Categoría ocupacional (1-8)',
            'B10' => '¿Aporta a caja de jubilación? (1-6)',
            'B11' => '¿A cuál caja aporta? (1=IPS)',
            'B16G' => 'Ingreso en guaraníes',
            'B16T' => 'Ingreso total mensual',
            'POBREZAI' => 'Pobreza (1=Pobre, 2=Extremo)',
            'A18' => 'Razón por la que dejó el trabajo (8=Se jubiló)',
        ];

        foreach (array_keys($primera) as $col) {
            $colUpper = strtoupper($col);
            $columnas[$col] = $mapeo[$colUpper] ?? 'Variable sin descripción';
        }

        return $columnas;
    }

    /**
     * Busca la columna de factor de expansión.
     */
    private function buscarColumnaFex(array $columnas): ?string
    {
        $candidatos = ['FEX', 'FEX_P', 'fex', 'factor_expansion', 'FACTOR'];
        
        foreach ($candidatos as $c) {
            foreach ($columnas as $col) {
                if (strtoupper($col) === strtoupper($c)) {
                    return $col;
                }
            }
        }

        return null;
    }

    /**
     * Calcula los agregados aplicando FEX.
     * 
     * IMPORTANTE: Siempre SUMA del FEX, nunca COUNT de registros.
     */
    private function calcularAgregados(array $datos, string $fexCol, int $anio): array
    {
        $agregados = [];

        // Normalizar keys a minúsculas para búsqueda case-insensitive
        $normalizarKey = fn($arr, $key) => $arr[$key] ?? $arr[strtoupper($key)] ?? $arr[strtolower($key)] ?? null;

        foreach ($datos as $fila) {
            // Obtener dimensiones
            $dpto = (int) ($normalizarKey($fila, 'DPTOREP') ?? 0);
            $area = (int) ($normalizarKey($fila, 'AREA') ?? 0);
            $fex = (float) ($normalizarKey($fila, $fexCol) ?? 1);

            // Normalizar área
            $areaStr = match($area) {
                1 => 'urbana',
                6 => 'rural',
                default => 'total',
            };

            // Keys para agregación
            $keyTotal = "{$anio}-0-total";
            $keyDpto = "{$anio}-{$dpto}-total";
            $keyDptoArea = "{$anio}-{$dpto}-{$areaStr}";

            // Inicializar si no existe
            foreach ([$keyTotal, $keyDpto, $keyDptoArea] as $key) {
                if (!isset($agregados[$key])) {
                    $agregados[$key] = $this->inicializarRegistro($anio, $key);
                }
            }

            // ── Acumular usando FEX ───────────────────────────────────────
            // Población total
            $agregados[$keyTotal]['poblacion_total'] += $fex;
            $agregados[$keyDpto]['poblacion_total'] += $fex;
            $agregados[$keyDptoArea]['poblacion_total'] += $fex;

            // PEA (A02=1)
            $a02 = (int) ($normalizarKey($fila, 'A02') ?? 0);
            if ($a02 === 1) {
                $agregados[$keyTotal]['pea_total'] += $fex;
                $agregados[$keyDpto]['pea_total'] += $fex;
                $agregados[$keyDptoArea]['pea_total'] += $fex;
            }

            // PEA Ocupada (A03=1)
            $a03 = (int) ($normalizarKey($fila, 'A03') ?? 0);
            if ($a03 === 1) {
                $agregados[$keyTotal]['pea_ocupada'] += $fex;
                $agregados[$keyDpto]['pea_ocupada'] += $fex;
                $agregados[$keyDptoArea]['pea_ocupada'] += $fex;
            }

            // PEA Desocupada (A04=1)
            $a04 = (int) ($normalizarKey($fila, 'A04') ?? 0);
            if ($a04 === 1) {
                $agregados[$keyTotal]['pea_desocupada'] += $fex;
                $agregados[$keyDpto]['pea_desocupada'] += $fex;
                $agregados[$keyDptoArea]['pea_desocupada'] += $fex;
            }

            // PEI (A05=1)
            $a05 = (int) ($normalizarKey($fila, 'A05') ?? 0);
            if ($a05 === 1) {
                $agregados[$keyTotal]['pei'] += $fex;
                $agregados[$keyDpto]['pei'] += $fex;
                $agregados[$keyDptoArea]['pei'] += $fex;
            }

            // Aportantes IPS (B11=1)
            $b11 = (int) ($normalizarKey($fila, 'B11') ?? 0);
            if ($b11 === 1) {
                $agregados[$keyTotal]['aportantes_ips'] += $fex;
                $agregados[$keyDpto]['aportantes_ips'] += $fex;
                $agregados[$keyDptoArea]['aportantes_ips'] += $fex;
            }

            // Aportantes otras cajas (B11 en 2,3,4,5)
            if (in_array($b11, [2, 3, 4, 5])) {
                $agregados[$keyTotal]['aportantes_otras_cajas'] += $fex;
                $agregados[$keyDpto]['aportantes_otras_cajas'] += $fex;
                $agregados[$keyDptoArea]['aportantes_otras_cajas'] += $fex;
            }

            // No aportantes (B10=6 o B10!=1)
            $b10 = (int) ($normalizarKey($fila, 'B10') ?? 0);
            if ($b10 === 6 || ($b10 !== 1 && $b10 !== 0)) {
                $agregados[$keyTotal]['no_aportantes'] += $fex;
                $agregados[$keyDpto]['no_aportantes'] += $fex;
                $agregados[$keyDptoArea]['no_aportantes'] += $fex;
            }

            // Informalidad total (no aporta al IPS)
            if ($b11 !== 1 && $a03 === 1) {
                $agregados[$keyTotal]['informalidad_total'] += $fex;
                $agregados[$keyDpto]['informalidad_total'] += $fex;
                $agregados[$keyDptoArea]['informalidad_total'] += $fex;
            }

            // Categoría ocupacional (A15)
            $a15 = (int) ($normalizarKey($fila, 'A15') ?? 0);
            if ($a15 >= 1 && $a15 <= 6) { // Excluir 7 y 8 (extranjero)
                $campoA15 = match($a15) {
                    1 => 'ocup_empleado_publico',
                    2 => 'ocup_empleado_privado',
                    3 => 'ocup_cuenta_propia',
                    4 => 'ocup_empleador',
                    5 => 'ocup_trabajador_familiar',
                    6 => 'ocup_empleado_domestico',
                    default => null,
                };

                if ($campoA15 && $a03 === 1) {
                    $agregados[$keyTotal][$campoA15] += $fex;
                    $agregados[$keyDpto][$campoA15] += $fex;
                    $agregados[$keyDptoArea][$campoA15] += $fex;
                }
            }

            // Pobreza (POBREZAI)
            $pobreza = (int) ($normalizarKey($fila, 'POBREZAI') ?? 0);
            if ($pobreza === 1) {
                $agregados[$keyTotal]['poblacion_pobre'] += $fex;
                $agregados[$keyDpto]['poblacion_pobre'] += $fex;
                $agregados[$keyDptoArea]['poblacion_pobre'] += $fex;
            } elseif ($pobreza === 2) {
                $agregados[$keyTotal]['poblacion_pobre_extremo'] += $fex;
                $agregados[$keyDpto]['poblacion_pobre_extremo'] += $fex;
                $agregados[$keyDptoArea]['poblacion_pobre_extremo'] += $fex;
            }

            // Jubilados (A18=8)
            $a18 = (int) ($normalizarKey($fila, 'A18') ?? 0);
            if ($a18 === 8) {
                $agregados[$keyTotal]['jubilados_encuesta'] += $fex;
                $agregados[$keyDpto]['jubilados_encuesta'] += $fex;
                $agregados[$keyDptoArea]['jubilados_encuesta'] += $fex;
            }
        }

        return $agregados;
    }

    /**
     * Inicializa un registro con valores por defecto.
     */
    private function inicializarRegistro(int $anio, string $key): array
    {
        [$a, $dpto, $area] = explode('-', $key);

        return [
            'anio' => $anio,
            'departamento_codigo' => (int) $dpto,
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

    /**
     * Muestra una vista previa de los cálculos.
     */
    private function mostrarVistaPrevia(array $datos, string $fexCol): void
    {
        $this->newLine();
        $this->info('Vista previa de cálculos:');

        $normalizarKey = fn($arr, $key) => $arr[$key] ?? $arr[strtoupper($key)] ?? $arr[strtolower($key)] ?? null;

        $totalFex = 0;
        $totalPea = 0;
        $totalIps = 0;

        foreach (array_slice($datos, 0, 1000) as $fila) {
            $fex = (float) ($normalizarKey($fila, $fexCol) ?? 1);
            $totalFex += $fex;

            $a02 = (int) ($normalizarKey($fila, 'A02') ?? 0);
            if ($a02 === 1) $totalPea += $fex;

            $b11 = (int) ($normalizarKey($fila, 'B11') ?? 0);
            if ($b11 === 1) $totalIps += $fex;
        }

        $this->table(
            ['Indicador', 'Valor (proyección FEX)'],
            [
                ['Población (muestra 1000 filas)', number_format($totalFex, 0)],
                ['PEA', number_format($totalPea, 0)],
                ['Aportantes IPS', number_format($totalIps, 0)],
                ['Tasa penetración estimada', $totalPea > 0 ? round(($totalIps / $totalPea) * 100, 2) . '%' : 'N/A'],
            ]
        );
    }

    /**
     * Muestra un resumen de los resultados.
     */
    private function mostrarResumen(array $resultados): void
    {
        $this->newLine();
        $this->info('Resumen de datos procesados:');

        $totalPais = collect($resultados)->firstWhere('departamento_codigo', 0);

        if ($totalPais) {
            $this->table(
                ['Indicador', 'Valor'],
                [
                    ['Población total', number_format($totalPais['poblacion_total'], 0)],
                    ['PEA total', number_format($totalPais['pea_total'], 0)],
                    ['PEA ocupada', number_format($totalPais['pea_ocupada'], 0)],
                    ['Aportantes IPS', number_format($totalPais['aportantes_ips'], 0)],
                    ['Informalidad', number_format($totalPais['informalidad_total'], 0)],
                    ['Tasa penetración', $totalPais['pea_ocupada'] > 0 
                        ? round(($totalPais['aportantes_ips'] / $totalPais['pea_ocupada']) * 100, 2) . '%' 
                        : 'N/A'],
                    ['Tasa informalidad', $totalPais['pea_ocupada'] > 0 
                        ? round(($totalPais['informalidad_total'] / $totalPais['pea_ocupada']) * 100, 2) . '%' 
                        : 'N/A'],
                ]
            );
        }
    }
}
