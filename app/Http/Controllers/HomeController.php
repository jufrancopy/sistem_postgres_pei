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

        // Coordinador RIISS → Centro RIISS unificado
        if ($user->hasRole(['Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            return redirect()->route('riiss.index');
        }

        // Analista RIISS (relevamiento in situ) → sus asignaciones directas
        if ($user->hasRole(['Analista - RIISS', 'Analista RIISS'])) {
            return redirect()->route('riiss.mis-asignaciones');
        }

        // Roles de Bioestadística → módulo de bioestadística
        if ($user->hasRole([
            'Analista de Bioestadística',
            'Digitador Bioestadística',
            'Consultor Bioestadística',
            'Auditor Bioestadística',
        ])) {
            return redirect()->route('bioestadistica.dashboard');
        }

        // Analista de Planificación / Coordinador de Planificación / Coordinador de Proyectos → dashboard global unificado
        if ($user->hasRole(['Analista de Planificación', 'Coordinador de Planificación', 'Coordinación de Planificación', 'Coordinador de Proyectos', 'Coordinación de Proyectos'])) {
            return redirect()->route('globales.dashboard');
        }

        // Fallback → dashboard global
        return redirect()->route('globales.dashboard');
    }
}
