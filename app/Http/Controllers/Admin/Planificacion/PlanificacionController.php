<?php

namespace App\Http\Controllers\Admin\Planificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Group;
use App\Models\Proyectos\ProyectoInstitucional;
use App\Models\HomeConfiguration;

class PlanificacionController extends Controller
{
    public function dashboard(Request $request)
    {
        return redirect()->route('globales.dashboard', $request->all());
    }

    /**
     * Guarda el PEI seleccionado en la configuración del dashboard para que persista entre sesiones.
     */
    public function guardarPeiSeleccionado(Request $request)
    {
        $request->validate(['pei_id' => 'required|string']);

        // Verificar que el PEI existe usando el modelo (evita problemas de schema en PostgreSQL)
        $pei = PeiProfile::find($request->pei_id);
        if (!$pei) {
            return response()->json(['success' => false, 'message' => 'Plan Estratégico no encontrado.'], 422);
        }

        $config = HomeConfiguration::firstOrNew([]);
        $config->pei_profile_id = $request->pei_id;
        $config->save();

        return response()->json(['success' => true, 'pei_id' => $request->pei_id]);
    }

    /**
     * Ejecuta manualmente el respaldo de PostgreSQL y envío de reporte por correo a jucfra23@gmail.com
     */
    public function ejecutarDiagnostico(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('siplan:health-and-backup');
            $output = \Illuminate\Support\Facades\Artisan::output();

            return response()->json([
                'success' => true,
                'message' => '¡Diagnóstico y Respaldo de Base de Datos ejecutados con éxito! El reporte ha sido enviado a jucfra23@gmail.com.',
                'output'  => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al ejecutar el diagnóstico: ' . $e->getMessage()
            ], 500);
        }
    }
}
