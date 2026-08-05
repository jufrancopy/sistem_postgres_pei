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
            if (! $user || ! method_exists($user, 'group')) {
                return false;
            }

            if (! $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación'])) {
                return false;
            }

            $group = $user->group;
            if (! $group) {
                return false;
            }

            $root = $group->ancestors()->withDepth()->orderByDesc('depth')->first() ?? $group;

            if ($root && $root->pei()->exists()) {
                return $user->perteneceAlArbol($root);
            }

            return true;
        });

        Gate::define('planificacion.manage-groups', function ($user) {
            if (! Gate::allows('planificacion.scope', $user)) {
                return false;
            }

            return $user->hasRole('Coordinador de Planificación');
        });

        Gate::define('planificacion.create-activity', function ($user) {
            if (! Gate::allows('planificacion.scope', $user)) {
                return false;
            }

            return $user->hasRole('Coordinador de Planificación');
        });

        Gate::define('planificacion.view-tree', function ($user) {
            return Gate::allows('planificacion.scope', $user);
        });

        Gate::define('planificacion.manage-organigrama', function ($user) {
            if (! Gate::allows('planificacion.scope', $user)) {
                return false;
            }

            return $user->hasAnyRole(['Coordinador de Planificación', 'Analista de Planificación']);
        });
    }
}
