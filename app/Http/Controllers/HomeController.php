<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Administrador → dashboard de planificación (acceso completo)
        if ($user->hasRole('Administrador')) {
            return redirect()->route('planificacion-dashboard');
        }

        // Analista RIISS → módulo de establecimientos
        if ($user->hasRole('Analista - RIISS')) {
            return redirect()->route('riiss.establecimientos.index');
        }

        // Analista de Planificación → sus actividades
        if ($user->hasRole('Analista de Planificación')) {
            return redirect()->route('globales.activities.mis-actividades');
        }

        // Participantes → sus notificaciones SIESS + perfil
        return redirect()->route('siess.home');
    }
}
