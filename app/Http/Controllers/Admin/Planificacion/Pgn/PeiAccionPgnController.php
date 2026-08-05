<?php

namespace App\Http\Controllers\Admin\Planificacion\Pgn;

use App\Http\Controllers\Controller;
use App\Models\Planificacion\PeiAccionPgn;
use App\Models\Planificacion\PgnNodo;
use App\Admin\Planificacion\Pei\PeiProfile;
use Illuminate\Http\Request;

class PeiAccionPgnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrador|Coordinador de Planificación|Analista PEI|Analista de Planificación|Analista');
    }

    // GET /pei-profiles/{actionId}/pgn
    // Retorna la vinculación PGN existente para pre-cargar el modal
    public function porAccion(string $actionId)
    {
        $vinculacion = PeiAccionPgn::with('nodo.estructura')
            ->where('pei_profile_id', $actionId)
            ->first();

        if (!$vinculacion) return response()->json(null);

        return response()->json([
            'pgn_nodo_id'         => $vinculacion->pgn_nodo_id,
            'pgn_nodo_text'       => $vinculacion->nodo->toSelect2()['text'],
            'pgn_nodo_nivel'      => $vinculacion->nodo->estructura?->nombre,
            'resultado'           => $vinculacion->resultado,
            'monto_vinculado_gs'  => $vinculacion->monto_vinculado_gs,
            'monto_ejecutado_gs'  => $vinculacion->monto_ejecutado_gs,
            'pct_ejecucion'       => $vinculacion->pct_ejecucion,
            'semaforo'            => $vinculacion->semaforo,
        ]);
    }

    // POST /pei-profiles/{actionId}/pgn
    // Guarda o actualiza la vinculación PGN de una acción
    public function sync(Request $request, string $actionId)
    {
        $request->validate([
            'pgn_nodo_id'        => 'nullable|integer',
            'pgn_resultado'      => 'nullable|string|max:500',
            'pgn_monto_vinculado'=> 'nullable|numeric|min:0',
            'pgn_monto_ejecutado'=> 'nullable|numeric|min:0',
        ]);

        // Si no viene nodo, eliminar vinculación existente
        if (!$request->pgn_nodo_id) {
            PeiAccionPgn::where('pei_profile_id', $actionId)->delete();
            return response()->json(['ok' => true, 'vinculacion' => null]);
        }

        $vinculacion = PeiAccionPgn::updateOrCreate(
            ['pei_profile_id' => $actionId],
            [
                'pgn_nodo_id'        => $request->pgn_nodo_id,
                'resultado'          => $request->pgn_resultado,
                'monto_vinculado_gs' => $request->pgn_monto_vinculado,
                'monto_ejecutado_gs' => $request->pgn_monto_ejecutado,
            ]
        );

        $vinculacion->load('nodo.estructura');

        return response()->json([
            'ok'          => true,
            'vinculacion' => [
                'pgn_nodo_text'      => $vinculacion->nodo->toSelect2()['text'],
                'resultado'          => $vinculacion->resultado,
                'monto_vinculado_gs' => $vinculacion->monto_vinculado_gs,
                'monto_ejecutado_gs' => $vinculacion->monto_ejecutado_gs,
                'pct_ejecucion'      => $vinculacion->pct_ejecucion,
                'semaforo'           => $vinculacion->semaforo,
            ],
        ]);
    }
}
