<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            'name' => 'crud_actividades'
            'guard_name'=> 'web'
        ]);
    
    }
}
