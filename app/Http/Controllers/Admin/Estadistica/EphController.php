<?php

namespace App\Http\Controllers\Admin\Estadistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Estadistica\EphDataset;

class EphController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Listado / Dashboard ───────────────────────────────────────────────────
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Excluir los campos JSONB pesados (datos, columnas) — no se necesitan en el listado
            $query = EphDataset::select([
                    'id', 'titulo', 'categoria', 'anio', 'fuente',
                    'descripcion', 'total_filas', 'cargado_por',
                    'created_at', 'updated_at',
                ])
                ->with('cargadoPor:id,name')
                ->when($request->categoria, fn($q) => $q->where('categoria', $request->categoria))
                ->when($request->anio,      fn($q) => $q->where('anio', $request->anio))
                ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('categoria_label', fn($r) => $r->categoriaLabel())
                ->addColumn('cargado_por_nombre', fn($r) => $r->cargadoPor?->name ?? '—')
                ->addColumn('action', fn($r) =>
                    '<a href="'.route('siess.eph.show', $r->id).'" class="btn btn-info btn-circle" title="Ver datos"><i class="fa fa-table"></i></a> '.
                    '<a href="'.route('siess.dgeec.mapear', $r->id).'" class="btn btn-warning btn-circle" title="Interpretar EPHC"><i class="fa fa-cogs"></i></a> '.
                    '<a href="javascript:void(0)" class="btn btn-danger btn-circle deleteEph" data-id="'.$r->id.'" title="Eliminar"><i class="fa fa-trash"></i></a>'
                )
                ->rawColumns(['action'])
                ->make(true);
        }

        // KPIs — queries livianas, sin tocar el campo datos
        $totalDatasets    = EphDataset::count();
        $porCategoria     = EphDataset::selectRaw('categoria, COUNT(*) as total')
            ->groupBy('categoria')->pluck('total', 'categoria');
        $aniosDisponibles = EphDataset::selectRaw('anio')->distinct()
            ->orderByDesc('anio')->pluck('anio');
        $categorias = EphDataset::CATEGORIAS;

        return view('admin.estadisticas.eph.index', compact(
            'totalDatasets', 'porCategoria', 'aniosDisponibles', 'categorias'
        ));
    }

    // ── Formulario de carga ───────────────────────────────────────────────────
    public function create()
    {
        $categorias = EphDataset::CATEGORIAS;
        $anios      = range(now()->year, 2000);
        return view('admin.estadisticas.eph.create', compact('categorias', 'anios'));
    }

    // ── Guardar dataset ───────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'titulo'    => 'required|string|max:255',
            'categoria' => 'required|string',
            'anio'      => 'required|integer|min:2000|max:'.now()->year,
        ]);

        $columnas   = [];
        $totalFilas = 0;
        $archivoPath = null;
        $extension   = null;

        // ── Archivo subido (CSV o JSON) ───────────────────────────────────────
        if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
            $archivo   = $request->file('archivo');
            $extension = strtolower($archivo->getClientOriginalExtension());

            if (!in_array($extension, ['csv', 'json'])) {
                return back()->withErrors(['archivo' => 'Solo se aceptan archivos .json o .csv'])->withInput();
            }

            // Guardar el archivo físicamente en storage (no en BD)
            $nombreArchivo = 'eph_' . now()->format('Ymd_His') . '_' . Str::slug($request->titulo) . '.' . $extension;
            $archivoPath   = $archivo->storeAs('eph', $nombreArchivo, 'local');

            // Leer solo las primeras filas para detectar columnas y contar total
            // SIN cargar todo en memoria
            [$columnas, $totalFilas] = $this->inspeccionarArchivo(
                storage_path('app/' . $archivoPath),
                $extension
            );

            $dataset = EphDataset::create([
                'titulo'             => $request->titulo,
                'categoria'          => $request->categoria,
                'anio'               => $request->anio,
                'fuente'             => $request->fuente ?: 'INE Paraguay — EPH',
                'descripcion'        => $request->descripcion,
                'archivo_path'       => $archivoPath,
                'archivo_extension'  => $extension,
                'datos'              => null,   // no guardamos en BD
                'columnas'           => $columnas,
                'total_filas'        => $totalFilas,
                'cargado_por'        => Auth::id(),
            ]);

        // ── JSON pegado en textarea (solo para datasets pequeños) ─────────────
        } elseif ($request->filled('json_raw')) {
            $datos = json_decode($request->json_raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['json_raw' => 'El JSON no es válido: '.json_last_error_msg()])->withInput();
            }
            if (!is_array($datos) || empty($datos)) {
                return back()->withErrors(['json_raw' => 'El JSON debe ser un array con al menos un elemento.'])->withInput();
            }

            $columnas = EphDataset::detectarColumnas($datos);

            $dataset = EphDataset::create([
                'titulo'      => $request->titulo,
                'categoria'   => $request->categoria,
                'anio'        => $request->anio,
                'fuente'      => $request->fuente ?: 'INE Paraguay — EPH',
                'descripcion' => $request->descripcion,
                'datos'       => $datos,
                'columnas'    => $columnas,
                'total_filas' => count($datos),
                'cargado_por' => Auth::id(),
            ]);
        } else {
            return back()->withErrors(['archivo' => 'Debés subir un archivo CSV/JSON o pegar el contenido JSON.'])->withInput();
        }

        return redirect()->route('siess.eph.show', $dataset->id)
            ->with('success', "Dataset '{$dataset->titulo}' registrado con {$dataset->total_filas} filas y ".count($columnas)." columnas.");
    }

    /**
     * Inspecciona el archivo para obtener columnas y total de filas
     * SIN cargar todo en memoria.
     */
    private function inspeccionarArchivo(string $ruta, string $extension): array
    {
        $columnas   = [];
        $totalFilas = 0;

        if ($extension === 'csv') {
            $handle = fopen($ruta, 'r');
            if (!$handle) return [[], 0];

            // Detectar BOM y separador
            $primeraLinea = fgets($handle);
            $primeraLinea = ltrim($primeraLinea, "\xEF\xBB\xBF");
            $separador    = substr_count($primeraLinea, ';') >= substr_count($primeraLinea, ',') ? ';' : ',';
            $columnas     = array_map('trim', str_getcsv($primeraLinea, $separador));

            // Contar filas sin cargar en memoria
            while (fgets($handle) !== false) {
                $totalFilas++;
            }
            fclose($handle);

        } elseif ($extension === 'json') {
            // Para JSON, leer solo el inicio para detectar columnas
            $handle = fopen($ruta, 'r');
            $buffer = '';
            while (!feof($handle) && strlen($buffer) < 10240) {
                $buffer .= fread($handle, 4096);
            }
            fclose($handle);

            // Buscar primer objeto del array
            $inicio = strpos($buffer, '{');
            $fin    = strpos($buffer, '}', $inicio);
            if ($inicio !== false && $fin !== false) {
                $primerObjeto = json_decode(substr($buffer, $inicio, $fin - $inicio + 1), true);
                if ($primerObjeto) {
                    $columnas = array_keys($primerObjeto);
                }
            }

            // Contar objetos contando '{' al inicio de línea (aproximado)
            $totalFilas = substr_count(file_get_contents($ruta), '{"') ?: 0;
        }

        return [$columnas, $totalFilas];
    }

    /**
     * Parsea un string CSV y devuelve array de arrays asociativos.
     * Detecta automáticamente el separador (, o ;)
     */
    private function parsearCsv(string $contenido): array
    {
        // Eliminar BOM si existe
        $contenido = ltrim($contenido, "\xEF\xBB\xBF");
        $lineas    = preg_split('/\r\n|\r|\n/', trim($contenido));

        if (count($lineas) < 2) return [];

        // Detectar separador
        $primeraLinea = $lineas[0];
        $separador    = substr_count($primeraLinea, ';') >= substr_count($primeraLinea, ',') ? ';' : ',';

        // Encabezados
        $encabezados = str_getcsv($primeraLinea, $separador);
        $encabezados = array_map('trim', $encabezados);

        $datos = [];
        for ($i = 1; $i < count($lineas); $i++) {
            $linea = trim($lineas[$i]);
            if (empty($linea)) continue;

            $valores = str_getcsv($linea, $separador);
            // Asegurar que tenga el mismo número de columnas
            while (count($valores) < count($encabezados)) {
                $valores[] = null;
            }

            $fila = [];
            foreach ($encabezados as $j => $col) {
                $val = $valores[$j] ?? null;
                // Intentar convertir a número si corresponde
                if (is_numeric($val)) {
                    $fila[$col] = strpos($val, '.') !== false ? (float)$val : (int)$val;
                } else {
                    $fila[$col] = $val;
                }
            }
            $datos[] = $fila;
        }

        return $datos;
    }

    // ── Ver dataset con tabla dinámica ────────────────────────────────────────
    public function show(Request $request, $id)
    {
        // Para DataTable AJAX — leer desde archivo en streaming
        if ($request->has('draw') || ($request->ajax() && !$request->isMethod('get'))) {
            $dataset = EphDataset::select(['id', 'archivo_path', 'archivo_extension', 'total_filas'])
                ->findOrFail($id);

            $start  = (int)($request->start  ?? 0);
            $length = (int)($request->length ?? 25);
            $buscar = strtolower($request->search['value'] ?? '');

            // Leer desde archivo físico en streaming (sin cargar todo en RAM)
            if ($dataset->archivo_path) {
                $rutaFisica = storage_path('app/' . $dataset->archivo_path);
                [$paginated, $total] = $this->leerPaginadoDesdeArchivo(
                    $rutaFisica,
                    $dataset->archivo_extension,
                    $start, $length, $buscar
                );
            } else {
                // Fallback: leer desde campo datos (datasets antiguos pequeños)
                $datasetConDatos = EphDataset::withDatos()->find($id);
                $datos = collect($datasetConDatos->datos ?? []);

                if ($buscar) {
                    $datos = $datos->filter(function($fila) use ($buscar) {
                        foreach ((array)$fila as $valor) {
                            if (str_contains(strtolower((string)$valor), $buscar)) return true;
                        }
                        return false;
                    });
                }

                $total     = $datos->count();
                $paginated = $datos->slice($start, $length)->values();
            }

            return response()->json([
                'draw'            => (int)$request->draw,
                'recordsTotal'    => $dataset->total_filas,
                'recordsFiltered' => $total,
                'data'            => $paginated,
            ]);
        }

        // Vista normal — sin cargar datos pesados
        $dataset = EphDataset::select([
                'id', 'titulo', 'categoria', 'anio', 'fuente',
                'descripcion', 'total_filas', 'cargado_por',
                'columnas', 'archivo_path', 'archivo_extension',
                'created_at', 'updated_at',
            ])
            ->with('cargadoPor:id,name')
            ->findOrFail($id);

        $categorias = EphDataset::CATEGORIAS;

        $relacionados = EphDataset::select(['id', 'titulo', 'categoria', 'anio', 'total_filas'])
            ->where('id', '!=', $id)
            ->where('anio', $dataset->anio)
            ->limit(5)->get();

        return view('admin.estadisticas.eph.show', compact('dataset', 'categorias', 'relacionados'));
    }

    /**
     * Lee filas paginadas desde un archivo CSV/JSON en streaming.
     * Nunca carga el archivo completo en memoria.
     */
    private function leerPaginadoDesdeArchivo(string $ruta, string $extension, int $start, int $length, string $buscar = ''): array
    {
        if (!file_exists($ruta)) {
            return [collect([]), 0];
        }

        $filas      = [];
        $totalMatch = 0;
        $filaActual = 0;
        $encabezados = [];

        if ($extension === 'csv') {
            $handle = fopen($ruta, 'r');
            if (!$handle) return [collect([]), 0];

            // Leer encabezados
            $primeraLinea = fgets($handle);
            $primeraLinea = ltrim($primeraLinea, "\xEF\xBB\xBF");
            $separador    = substr_count($primeraLinea, ';') >= substr_count($primeraLinea, ',') ? ';' : ',';
            $encabezados  = array_map('trim', str_getcsv($primeraLinea, $separador));

            while (($linea = fgets($handle)) !== false) {
                $linea = trim($linea);
                if (empty($linea)) continue;

                // Filtro de búsqueda sin construir el array completo
                if ($buscar && stripos($linea, $buscar) === false) continue;

                $totalMatch++;

                // Solo construir el array para las filas de la página actual
                if ($totalMatch > $start && count($filas) < $length) {
                    $valores = str_getcsv($linea, $separador);
                    $fila    = [];
                    foreach ($encabezados as $i => $col) {
                        $val = $valores[$i] ?? null;
                        $fila[$col] = is_numeric($val) ? (strpos($val, '.') !== false ? (float)$val : (int)$val) : $val;
                    }
                    $filas[] = $fila;
                }
            }
            fclose($handle);

        } elseif ($extension === 'json') {
            // Para JSON, usar un parser de streaming línea por línea
            $handle = fopen($ruta, 'r');
            if (!$handle) return [collect([]), 0];

            $buffer = '';
            $depth  = 0;
            $enObjeto = false;

            while (!feof($handle)) {
                $char = fread($handle, 1);
                if ($char === '{') {
                    $depth++;
                    $enObjeto = true;
                }
                if ($enObjeto) {
                    $buffer .= $char;
                }
                if ($char === '}') {
                    $depth--;
                    if ($depth === 0 && $enObjeto) {
                        $obj = json_decode($buffer, true);
                        $buffer   = '';
                        $enObjeto = false;

                        if (!$obj) continue;

                        if ($buscar && stripos(json_encode($obj), $buscar) === false) continue;

                        $totalMatch++;
                        if ($totalMatch > $start && count($filas) < $length) {
                            $filas[] = $obj;
                        }
                    }
                }
            }
            fclose($handle);
        }

        return [collect($filas), $totalMatch ?: 0];
    }

    // ── Eliminar ──────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        EphDataset::findOrFail($id)->delete();
        return response()->json(['success' => 'Dataset eliminado.']);
    }
}
