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
            // Si el Administrador está navegando en modo Vista Previa (Impersonación):
            // 1. Mantenemos 'En Línea' al Administrador original.
            // 2. NO marcamos al usuario simulado 'En Línea'.
            // 3. NO registramos telemetría/actividades falsas a nombre del usuario simulado.
            if (session()->has('impersonator_id')) {
                $adminId = session('impersonator_id');
                Cache::put('user-is-online-' . $adminId, true, now()->addMinutes(5));
                Cache::forget('user-is-online-' . Auth::id());
                return $next($request);
            }

            $userId = Auth::id();
            $expiresAt = now()->addMinutes(5);
            Cache::put('user-is-online-' . $userId, true, $expiresAt);

            $path = $request->path();
            $method = $request->method();

            // Ignorar peticiones de fondo, polling AJAX de notificaciones y la propia telemetría
            $ignoredPaths = [
                'siess/notificaciones',
                'telemetry',
                'get-dependencies',
                'notifications',
                'livewire',
                'debugbar',
                'check-session',
            ];

            foreach ($ignoredPaths as $ignored) {
                if (str_contains($path, $ignored)) {
                    return $next($request);
                }
            }

            // Ignorar peticiones AJAX automáticas en segundo plano (GET AJAX) para no distorsionar la navegación del usuario
            if ($request->ajax() && strtolower($method) === 'get') {
                return $next($request);
            }

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

            $cacheKey = "user-activity-log-{$userId}-{$module}-{$path}";
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
