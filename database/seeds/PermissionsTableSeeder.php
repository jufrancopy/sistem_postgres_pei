<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
<<<<<<< HEAD
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete'
         ];
 
 
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
=======
          DB::table('permissions')->insert([
            'name' => 'todos_los_permisos',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'crud_m_v_o_v',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'crud_estrategias',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'crud_programas',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'crud_proyectos',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'crud_actividades',
            'guard_name'=> 'web'
        ]);
>>>>>>> 241a26be8edd2bbe030de9237187923b504d412f
    
    }
}
