<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalidadController extends Controller
{
    /**
     * GET /riiss/localidades/departamentos
     * Devuelve todos los departamentos en formato Select2.
     */
    public function departamentos(Request $request): JsonResponse
    {
        $q = $request->get('q', '');

        $rows = DB::table('localities')
            ->select('cod_dpto', 'desc_dpto')
            ->distinct()
            ->when($q, fn($query) => $query->where('desc_dpto', 'ILIKE', "%{$q}%"))
            ->orderBy('desc_dpto')
            ->get();

        $results = $rows->map(fn($r) => [
            'id'   => $r->cod_dpto,
            'text' => $r->desc_dpto,
        ]);

        return response()->json(['results' => $results]);
    }

    /**
     * GET /riiss/localidades/distritos?cod_dpto=1
     * Devuelve los distritos/ciudades de un departamento.
     */
    public function distritos(Request $request): JsonResponse
    {
        $request->validate(['cod_dpto' => 'required']);

        $q = $request->get('q', '');

        $rows = DB::table('localities')
            ->select('cod_dist', 'desc_dist')
            ->distinct()
            ->where('cod_dpto', $request->cod_dpto)
            ->when($q, fn($query) => $query->where('desc_dist', 'ILIKE', "%{$q}%"))
            ->orderBy('desc_dist')
            ->get();

        $results = $rows->map(fn($r) => [
            'id'   => $r->cod_dist,
            'text' => $r->desc_dist,
        ]);

        return response()->json(['results' => $results]);
    }

    /**
     * GET /riiss/localidades/barrios?cod_dpto=1&cod_dist=2
     * Devuelve los barrios/localidades de un distrito.
     */
    public function barrios(Request $request): JsonResponse
    {
        $request->validate([
            'cod_dpto' => 'required',
            'cod_dist' => 'required',
        ]);

        $q = $request->get('q', '');

        $rows = DB::table('localities')
            ->select('id', 'cod_barrio_loc', 'desc_barrio_loc')
            ->where('cod_dpto', $request->cod_dpto)
            ->where('cod_dist', $request->cod_dist)
            ->when($q, fn($query) => $query->where('desc_barrio_loc', 'ILIKE', "%{$q}%"))
            ->orderBy('desc_barrio_loc')
            ->get();

        $results = $rows->map(fn($r) => [
            'id'   => $r->id,
            'text' => $r->desc_barrio_loc,
        ]);

        return response()->json(['results' => $results]);
    }
}
