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

        // Analista RIISS → módulo RIISS unificado
        if ($user->hasRole('Analista - RIISS')) {
            return redirect()->route('riiss.index');
        }

        // Analista de Planificación / Coordinador de Planificación → su dashboard de planificación
        if ($user->hasRole(['Analista de Planificación', 'Coordinador de Planificación'])) {
            return redirect()->route('planificacion-dashboard');
        }

        // Fallback → dashboard global
        return redirect()->route('globales.dashboard');
    }
}
