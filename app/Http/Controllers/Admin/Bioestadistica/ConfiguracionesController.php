<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\BioestadisticaConfigNavigation;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ConfiguracionesController extends Controller
{
    public function index(): RedirectResponse
    {
        $route = BioestadisticaConfigNavigation::firstAccessibleRoute();
        if (! $route) {
            throw new AccessDeniedHttpException('No tiene permisos para acceder a las configuraciones de bioestadística.');
        }

        return redirect()->route($route);
    }
}
