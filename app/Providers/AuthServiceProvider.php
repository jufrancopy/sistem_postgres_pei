<?php

namespace App\Providers;

use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\ImportJob;
use App\Models\Bioestadistica\Record;
use App\Policies\Bioestadistica\DashboardPolicy;
use App\Policies\Bioestadistica\HospEpisodioPolicy;
use App\Policies\Bioestadistica\ImportJobPolicy;
use App\Policies\Bioestadistica\RecordPolicy;
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
        Record::class => RecordPolicy::class,
        HospEpisodio::class => HospEpisodioPolicy::class,
        Dashboard::class => DashboardPolicy::class,
        ImportJob::class => ImportJobPolicy::class,
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
