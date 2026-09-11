<?php

namespace App\Application\Bioestadistica;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BioestadisticaConfigNavigation
{
    /**
     * @return array<int, array{label: string, route: string, permission: string, active: array<int, string>}>
     */
    public static function tabs(): array
    {
        return [
            [
                'label' => 'Formularios',
                'route' => 'bioestadistica.formularios.index',
                'permission' => 'bio.form.view',
                'active' => [
                    'bioestadistica.formularios.*',
                    'bioestadistica.secciones.*',
                    'bioestadistica.fields.*',
                ],
            ],
            [
                'label' => 'Variables',
                'route' => 'bioestadistica.diccionario.index',
                'permission' => 'bio.catalog.view',
                'active' => ['bioestadistica.diccionario.*'],
            ],
            [
                'label' => 'Indicadores',
                'route' => 'bioestadistica.indicadores.index',
                'permission' => 'bio.indicator.view',
                'active' => ['bioestadistica.indicadores.*'],
            ],
            [
                'label' => 'Dashboards',
                'route' => 'bioestadistica.dashboards.index',
                'permission' => 'bio.dashboard.view',
                'active' => ['bioestadistica.dashboards.*'],
            ],
            [
                'label' => 'Importaciones',
                'route' => 'bioestadistica.importaciones.index',
                'permission' => 'bio.import.view',
                'active' => ['bioestadistica.importaciones.*'],
            ],
            [
                'label' => 'Auditoría',
                'route' => 'bioestadistica.auditoria.index',
                'permission' => 'bio.audit.view',
                'active' => ['bioestadistica.auditoria.*'],
            ],
            [
                'label' => 'Establecimientos',
                'route' => 'bioestadistica.geografia.index',
                'permission' => 'bio.geo.view',
                'active' => ['bioestadistica.geografia.*'],
            ],
            [
                'label' => 'Organigrama',
                'route' => 'bioestadistica.organos.index',
                'permission' => 'bio.geo.view',
                'active' => ['bioestadistica.organos.*'],
            ],
            [
                'label' => 'Clasificaciones',
                'route' => 'bioestadistica.clasificaciones.index',
                'permission' => 'bio.geo.view',
                'active' => ['bioestadistica.clasificaciones.*'],
            ],
            [
                'label' => 'Asignaciones',
                'route' => 'bioestadistica.asignaciones.index',
                'permission' => 'bio.assignment.manage',
                'active' => ['bioestadistica.asignaciones.*'],
            ],
        ];
    }

    public static function isConfigRoute(): bool
    {
        if (request()->routeIs('bioestadistica.configuraciones.*')) {
            return true;
        }

        foreach (self::tabs() as $tab) {
            foreach ($tab['active'] as $pattern) {
                if (request()->routeIs($pattern)) {
                    return true;
                }
            }
        }

        $path = trim(request()->path(), '/');
        foreach (self::configPathPrefixes() as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    public static function configPathPrefixes(): array
    {
        return [
            'bioestadistica/formularios',
            'bioestadistica/secciones',
            'bioestadistica/campos',
            'bioestadistica/diccionario',
            'bioestadistica/indicadores',
            'bioestadistica/dashboards',
            'bioestadistica/importaciones',
            'bioestadistica/auditoria',
            'bioestadistica/geografia',
            'bioestadistica/organos',
            'bioestadistica/clasificaciones',
            'bioestadistica/asignaciones',
            'bioestadistica/configuraciones',
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, permission: string, active: array<int, string>, is_active: bool}>
     */
    public static function visibleTabs(?User $user = null): array
    {
        $user ??= Auth::user();
        if (! $user) {
            return [];
        }

        $visible = [];
        foreach (self::tabs() as $tab) {
            if (! $user->can($tab['permission'])) {
                continue;
            }
            $tab['is_active'] = self::tabIsActive($tab);

            $visible[] = $tab;
        }

        return $visible;
    }

    public static function hasVisibleTabs(?User $user = null): bool
    {
        return self::visibleTabs($user) !== [];
    }

    public static function firstAccessibleRoute(?User $user = null): ?string
    {
        $tabs = self::visibleTabs($user);

        return $tabs[0]['route'] ?? null;
    }

    /**
     * @param  array{label: string, route: string, permission: string, active: array<int, string>}  $tab
     */
    public static function tabIsActive(array $tab): bool
    {
        foreach ($tab['active'] as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        $path = trim(request()->path(), '/');
        $prefixes = match ($tab['route']) {
            'bioestadistica.formularios.index' => ['bioestadistica/formularios', 'bioestadistica/secciones', 'bioestadistica/campos'],
            'bioestadistica.diccionario.index' => ['bioestadistica/diccionario'],
            'bioestadistica.indicadores.index' => ['bioestadistica/indicadores'],
            'bioestadistica.dashboards.index' => ['bioestadistica/dashboards'],
            'bioestadistica.importaciones.index' => ['bioestadistica/importaciones'],
            'bioestadistica.auditoria.index' => ['bioestadistica/auditoria'],
            'bioestadistica.geografia.index' => ['bioestadistica/geografia'],
            'bioestadistica.organos.index' => ['bioestadistica/organos'],
            'bioestadistica.clasificaciones.index' => ['bioestadistica/clasificaciones'],
            'bioestadistica.asignaciones.index' => ['bioestadistica/asignaciones'],
            default => [],
        };
        foreach ($prefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        return false;
    }
}
