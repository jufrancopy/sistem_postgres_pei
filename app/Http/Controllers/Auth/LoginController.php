<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated($request, $user)
    {
        if ($user->hasRole(['Administrador', 'Analista de Planificación', 'Coordinador de Planificación'])) {
            return redirect()->route('planificacion-dashboard');
        } elseif ($user->hasRole('Analista - RIISS')) {
            return redirect()->route('riiss.establecimientos.index');
        } elseif ($user->hasRole('Analista de Monitoreo PEI')) {
            return redirect()->route('pei.monitoreo.dashboard');
        } elseif ($user->hasRole(['Gestor de Actividades', 'Colaborador de Actividades'])) {
            return redirect()->route('globales.activities.mis-actividades');
        } elseif ($user->hasRole('Analista de Bioestadística')) {
            return redirect()->route('bioestadistica.dashboard');
        } else {
            return redirect()->route('planificacion-dashboard');
        }
    }
}
