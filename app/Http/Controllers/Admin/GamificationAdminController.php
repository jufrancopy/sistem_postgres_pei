<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class GamificationAdminController extends Controller
{
    /**
     * Recalcula retroactivamente los puntos e insignias de gamificación para todos los usuarios o uno en específico.
     */
    public function recalculate(Request $request)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos de Administrador para recalcular puntos.'
            ], 403);
        }

        try {
            $params = ['--reset' => true];
            if ($request->filled('user_id')) {
                $params['--user'] = $request->input('user_id');
            }

            Artisan::call('gamification:recalculate', $params);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => '¡Recálculo completado exitosamente! Los puntos e insignias de todos los equipos han sido actualizados con los parámetros correctos.',
                'output'  => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al recalcular puntos: ' . $e->getMessage()
            ], 500);
        }
    }
}
