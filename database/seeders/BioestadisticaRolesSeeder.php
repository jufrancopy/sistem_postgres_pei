<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BioestadisticaRolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'bio.dashboard.view',
            'bio.form.view', 'bio.form.create', 'bio.form.update', 'bio.form.delete', 'bio.form.publish',
            'bio.catalog.view', 'bio.catalog.create', 'bio.catalog.update', 'bio.catalog.delete',
            'bio.geo.view', 'bio.geo.create', 'bio.geo.update', 'bio.geo.delete',
            'bio.record.view', 'bio.record.create', 'bio.record.update', 'bio.record.delete',
            'bio.record.submit', 'bio.record.approve',
            'bio.indicator.view', 'bio.indicator.manage', 'bio.indicator.evaluate',
            'bio.report.view', 'bio.report.manage', 'bio.report.export',
            'bio.dashboard.manage', 'bio.dashboard.personalize',
            'bio.import.view', 'bio.import.execute',
            'bio.hosp.view', 'bio.hosp.manage', 'bio.hosp.view_pii', 'bio.hosp.export',
            'bio.audit.view',
            'bio.assignment.manage',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $legacyDashboard = Permission::firstOrCreate([
            'name' => 'bioestadistica-dashboard',
            'guard_name' => 'web',
        ]);

        $roles = [
            'Administrador',
            'Analista de Bioestadística',
            'Digitador Bioestadística',
            'Consultor Bioestadística',
            'Auditor Bioestadística',
        ];
        foreach ($roles as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        Role::findByName('Analista de Bioestadística')->syncPermissions(array_filter(
            $permissions,
            fn (string $permission) => $permission !== 'bio.audit.view'
        ));

        Role::findByName('Digitador Bioestadística')->syncPermissions([
            'bio.dashboard.view', 'bio.form.view', 'bio.catalog.view', 'bio.geo.view',
            'bio.record.view', 'bio.record.create', 'bio.record.update', 'bio.record.submit',
            'bio.indicator.view', 'bio.indicator.evaluate',
            'bio.report.view', 'bio.report.export',
            'bio.dashboard.personalize', 'bio.hosp.view', 'bio.hosp.manage', 'bio.hosp.view_pii',
        ]);

        Role::findByName('Consultor Bioestadística')->syncPermissions([
            'bio.dashboard.view', 'bio.form.view', 'bio.catalog.view', 'bio.geo.view',
            'bio.record.view', 'bio.indicator.view', 'bio.indicator.evaluate',
            'bio.report.view', 'bio.report.export',
            'bio.dashboard.personalize', 'bio.hosp.view',
        ]);

        Role::findByName('Auditor Bioestadística')->syncPermissions([
            'bio.dashboard.view', 'bio.form.view', 'bio.catalog.view', 'bio.geo.view',
            'bio.record.view', 'bio.indicator.view', 'bio.indicator.evaluate',
            'bio.report.view', 'bio.report.export',
            'bio.dashboard.personalize',
            'bio.hosp.view', 'bio.hosp.view_pii', 'bio.audit.view',
        ]);

        Role::findByName('Analista de Bioestadística')->givePermissionTo($legacyDashboard);
        Role::findByName('Administrador')->givePermissionTo([...$permissions, $legacyDashboard->name]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
