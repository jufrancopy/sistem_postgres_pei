<?php

use Illuminate\Database\Seeder;

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
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'Consejero',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'Gerente',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'Director',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'Departamento',
            'guard_name'=> 'web'
        ]);

        DB::table('roles')->insert([
            'name' => 'Seccion',
            'guard_name'=> 'web'
        ]);
    }
}
