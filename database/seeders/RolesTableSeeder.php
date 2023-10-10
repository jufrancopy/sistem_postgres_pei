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
        DB::table('roles')->insert([
            'name' => 'Administrador',
            'guard_name' => 'web',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // Obtener todos los permisos
        $permissions = DB::table('permissions')->pluck('id');

        // Asociar todos los permisos al rol "Administrador"
        foreach ($permissions as $permissionId) {
            DB::table('role_has_permissions')->insert([
                'role_id' => 1, // ID del rol "Administrador"
                'permission_id' => $permissionId,
            ]);
        }
    }
}
