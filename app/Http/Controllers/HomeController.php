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

        // Administrador → dashboard global
        if ($user->hasRole('Administrador')) {
            return redirect()->route('globales.dashboard');
        }

        // Analista de Monitoreo PEI → su dashboard de planes
        if ($user->hasRole('Analista de Monitoreo PEI')) {
            return redirect()->route('pei.monitoreo.dashboard');
        }

        // Analista RIISS → módulo de establecimientos
        if ($user->hasRole('Analista - RIISS')) {
            return redirect()->route('riiss.establecimientos.index');
        }

        // Gestor o Colaborador de Actividades → sus actividades
        if ($user->hasRole(['Gestor de Actividades', 'Colaborador de Actividades'])) {
            return redirect()->route('globales.activities.mis-actividades');
        }

        // Fallback → dashboard global
        return redirect()->route('globales.dashboard');
    }
}
