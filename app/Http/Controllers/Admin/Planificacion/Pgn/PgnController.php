<?php

namespace App\Http\Controllers\Admin\Planificacion\Pgn;

use App\Http\Controllers\Controller;
use App\Models\Planificacion\PgnEstructura;
use App\Models\Planificacion\PgnNodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PgnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrador|Analista PEI');
    }

    // ── Vista principal del módulo PGN ────────────────────────────────────────

    public function index()
    {
        $anios     = PgnEstructura::aniosDisponibles();
        $anioActual = $anios->first() ?? date('Y');
        $estructura = PgnEstructura::deAnio($anioActual);
        return view('admin.planificacion.pgn.index', compact('anios', 'anioActual', 'estructura'));
    }

    // ── CRUD Estructura de niveles ────────────────────────────────────────────

    public function estructuraDeAnio(int $anio)
    {
        return response()->json(PgnEstructura::deAnio($anio));
    }

    public function storeEstructura(Request $request)
    {
        $this->middleware('role:Administrador');
        $request->validate([
            'anio'   => 'required|integer|min:2000|max:2100',
            'orden'  => 'required|integer|min:1',
            'nombre' => 'required|string|max:100',
        ]);

        $nivel = PgnEstructura::create($request->only('anio', 'orden', 'nombre', 'descripcion'));
        return response()->json($nivel);
    }

    public function updateEstructura(Request $request, PgnEstructura $estructura)
    {
        $request->validate([
            'orden'  => 'required|integer|min:1',
            'nombre' => 'required|string|max:100',
        ]);
        $estructura->update($request->only('orden', 'nombre', 'descripcion', 'activo'));
        return response()->json($estructura);
    }

    public function destroyEstructura(PgnEstructura $estructura)
    {
        if ($estructura->nodos()->exists()) {
            return response()->json(['error' => 'El nivel tiene nodos asociados. Eliminá los nodos primero.'], 422);
        }
        $estructura->delete();
        return response()->json(['ok' => true]);
    }

    // ── CRUD Nodos ────────────────────────────────────────────────────────────

    public function nodosDeAnio(int $anio)
    {
        $nodos = PgnNodo::with('estructura')
            ->deAnio($anio)
            ->whereNull('parent_id')
            ->with(['hijos.estructura', 'hijos.hijos.estructura', 'hijos.hijos.hijos.estructura'])
            ->orderBy('codigo')
            ->get();
        return response()->json($nodos);
    }

    public function storeNodo(Request $request)
    {
        $request->validate([
            'anio'              => 'required|integer',
            'pgn_estructura_id' => 'required|integer|exists:planificacion.pgn_estructura,id',
            'nombre'            => 'required|string|max:500',
            'parent_id'         => 'nullable|integer',
            'codigo'            => 'nullable|string|max:50',
            'monto_asignado_gs' => 'nullable|numeric|min:0',
        ]);

        $nodo = PgnNodo::create($request->only(
            'anio', 'pgn_estructura_id', 'parent_id', 'codigo', 'nombre', 'monto_asignado_gs'
        ));

        return response()->json($nodo->load('estructura'));
    }

    public function updateNodo(Request $request, PgnNodo $nodo)
    {
        $request->validate([
            'nombre'            => 'required|string|max:500',
            'codigo'            => 'nullable|string|max:50',
            'monto_asignado_gs' => 'nullable|numeric|min:0',
            'activo'            => 'nullable|boolean',
        ]);
        $nodo->update($request->only('nombre', 'codigo', 'monto_asignado_gs', 'activo'));
        return response()->json($nodo->load('estructura'));
    }

    public function destroyNodo(PgnNodo $nodo)
    {
        if ($nodo->hijos()->exists()) {
            return response()->json(['error' => 'El nodo tiene subnodos. Eliminá los hijos primero.'], 422);
        }
        if ($nodo->accionesPei()->exists()) {
            return response()->json(['error' => 'El nodo está vinculado a acciones del PEI.'], 422);
        }
        $nodo->delete();
        return response()->json(['ok' => true]);
    }

    // ── Búsqueda Select2 (solo nodos hoja) ───────────────────────────────────

    public function buscar(Request $request)
    {
        $q    = $request->get('q', '');
        $anio = $request->get('anio', date('Y'));

        $nodos = PgnNodo::with('estructura')
            ->deAnio($anio)
            ->hojas()
            ->where('activo', true)
            ->buscar($q)
            ->limit(20)
            ->get();

        return response()->json($nodos->map->toSelect2());
    }

    // ── Importación CSV ───────────────────────────────────────────────────────

    public function importar(Request $request)
    {
        $request->validate([
            'anio'    => 'required|integer',
            'archivo' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $anio    = $request->anio;
        $archivo = $request->file('archivo');
        $handle  = fopen($archivo->getRealPath(), 'r');
        $header  = fgetcsv($handle); // primera fila = encabezados
        $errores = [];
        $creados = 0;

        // Cachear estructura del año
        $niveles = PgnEstructura::deAnio($anio)->keyBy('nombre');

        DB::beginTransaction();
        try {
            while (($fila = fgetcsv($handle)) !== false) {
                $row = array_combine($header, $fila);
                // Columnas esperadas: nivel, codigo, nombre, monto_asignado_gs, codigo_padre
                $nombreNivel = trim($row['nivel'] ?? '');
                $estructura  = $niveles->get($nombreNivel);

                if (!$estructura) {
                    $errores[] = "Nivel '{$nombreNivel}' no existe para el año {$anio}";
                    continue;
                }

                $padre = null;
                if (!empty($row['codigo_padre'])) {
                    $padre = PgnNodo::deAnio($anio)->where('codigo', trim($row['codigo_padre']))->first();
                }

                PgnNodo::updateOrCreate(
                    ['anio' => $anio, 'codigo' => trim($row['codigo'] ?? '')],
                    [
                        'pgn_estructura_id' => $estructura->id,
                        'parent_id'         => $padre?->id,
                        'nombre'            => trim($row['nombre']),
                        'monto_asignado_gs' => !empty($row['monto_asignado_gs'])
                            ? (float) str_replace(['.', ','], ['', '.'], $row['monto_asignado_gs'])
                            : null,
                    ]
                );
                $creados++;
            }
            fclose($handle);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['creados' => $creados, 'errores' => $errores]);
    }
}
