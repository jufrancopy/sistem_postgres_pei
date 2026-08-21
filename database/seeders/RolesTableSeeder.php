<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Administrador',
            'Coordinador de Planificación',
            'Analista de Planificación',
            'Analista',
            'Gestor de Actividades',
            'Colaborador de Actividades',
            'Analista de Monitoreo PEI',
            'Analista - RIISS',
            'Analista de Bioestadística',
            'Líder MECIP',
            'Participantes',
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        $bioPermission = Permission::firstOrCreate([
            'name' => 'bioestadistica-dashboard',
            'guard_name' => 'web',
        ]);

        $bioRole = Role::where('name', 'Analista de Bioestadística')->first();
        if ($bioRole) {
            $bioRole->givePermissionTo($bioPermission);
        }

        $adminRole = Role::where('name', 'Administrador')->first();
        if ($adminRole) {
            $permissions = Permission::all();
            $adminRole->syncPermissions($permissions);
        }
    }
}
