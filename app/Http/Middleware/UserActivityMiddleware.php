<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\UserActivity;

class UserActivityMiddleware
{
    /**
     * Registrar la actividad del usuario autenticado en memoria y base de datos (Telemetría).
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $expiresAt = now()->addMinutes(5);
            Cache::put('user-is-online-' . $userId, true, $expiresAt);

            $path = $request->path();
            $method = $request->method();

            $module = 'general';
            if (str_contains($path, 'pei') || str_contains($path, 'planificacion')) {
                $module = 'pei';
            } elseif (str_contains($path, 'indicador') || str_contains($path, 'siess')) {
                $module = 'indicadores';
            } elseif (str_contains($path, 'foda')) {
                $module = 'foda';
            } elseif (str_contains($path, 'group') || str_contains($path, 'grupo')) {
                $module = 'grupos';
            } elseif (str_contains($path, 'proyect') || str_contains($path, 'actividad')) {
                $module = 'proyectos';
            } elseif (str_contains($path, 'organigrama')) {
                $module = 'organigrama';
            }

            $cacheKey = "user-activity-log-{$userId}-{$module}";
            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, now()->addSeconds(15));

                $action = strtolower($method) === 'get' ? 'view' : (strtolower($method) === 'post' ? 'create' : 'update');
                
                try {
                    UserActivity::create([
                        'user_id'     => $userId,
                        'module'      => $module,
                        'action'      => $action,
                        'ip_address'  => $request->ip(),
                        'user_agent'  => substr((string) $request->userAgent(), 0, 255),
                        'description' => "Navegó en el módulo " . strtoupper($module) . " ({$method} /{$path})",
                    ]);
                } catch (\Throwable $e) {
                    // Ignorar silenciosamente cualquier excepción puntual
                }
            }
        }

        return $next($request);
    }
}
