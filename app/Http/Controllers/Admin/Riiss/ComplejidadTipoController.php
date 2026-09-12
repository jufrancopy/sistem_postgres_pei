<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\ComplejidadTipo;
use App\Models\Riiss\Establecimiento;
use Illuminate\Http\Request;

class ComplejidadTipoController extends Controller
{
    public function index()
    {
        return redirect()->to(route('riiss.configuracion') . '#tab-complejidad');
    }

    public function edit(ComplejidadTipo $complejidadTipo)
    {
        return view('admin.riiss.complejidad.edit', ['tipo' => $complejidadTipo]);
    }

    public function update(Request $request, ComplejidadTipo $complejidadTipo)
    {
        $data = $request->validate([
            'nombre'               => 'required|string|max:80',
            'nivel_atencion'       => 'required|integer|min:1|max:4',
            'tipo_establecimiento' => 'nullable|string|max:60',
            'es_hospitalario'      => 'boolean',
            'requiere_internacion' => 'boolean',
            'requiere_quirofano'   => 'boolean',
            'requiere_uti'         => 'boolean',
            'requiere_urgencias'   => 'boolean',
            'color'                => 'required|string|max:10',
            'activo'               => 'boolean',
        ]);

        $nivelAnterior        = $complejidadTipo->nivel_atencion;
        $esHospitalarioAnterior = $complejidadTipo->es_hospitalario;

        $complejidadTipo->update($data);

        // Recalcular todos los establecimientos de este grado si cambió algo relevante
        $cambioCritico = $nivelAnterior !== $complejidadTipo->nivel_atencion
            || $esHospitalarioAnterior !== $complejidadTipo->es_hospitalario
            || array_key_exists('requiere_internacion', $data)
            || array_key_exists('requiere_quirofano', $data)
            || array_key_exists('requiere_uti', $data)
            || array_key_exists('requiere_urgencias', $data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => "Grado {$complejidadTipo->grado} ({$complejidadTipo->nombre}) actualizado correctamente.",
                'data' => $complejidadTipo->fresh(),
            ]);
        }

        return redirect()->to(route('riiss.configuracion') . '#tab-complejidad')
            ->with('success', "Grado {$complejidadTipo->grado} ({$complejidadTipo->nombre}) actualizado correctamente.");
    }
}
