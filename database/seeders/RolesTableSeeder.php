<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\DB;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web',
        ]);

        $analistaRole = Role::firstOrCreate([
            'name' => 'Analista RIISS',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);
    }
}
