<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('planificacion.scope', function ($user) {
            if (!$user) {
                return false;
            }
            if ($user->hasRole('Administrador')) {
                return true;
            }
            return $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación']);
        });

        Gate::define('planificacion.manage-groups', function ($user) {
            if (!$user) {
                return false;
            }
            if ($user->hasRole('Administrador')) {
                return true;
            }
            return $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación']);
        });

        Gate::define('planificacion.create-activity', function ($user) {
            if (!$user) {
                return false;
            }
            if ($user->hasRole('Administrador')) {
                return true;
            }
            return $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación', 'Gestor de Actividades']);
        });

        Gate::define('planificacion.view-tree', function ($user) {
            if (!$user) {
                return false;
            }
            if ($user->hasRole('Administrador')) {
                return true;
            }
            return $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación']);
        });

        Gate::define('planificacion.manage-organigrama', function ($user) {
            if (!$user) {
                return false;
            }
            if ($user->hasRole('Administrador')) {
                return true;
            }
            return $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación']);
        });
    }
}
