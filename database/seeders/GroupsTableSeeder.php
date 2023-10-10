<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => 'Equipos de Trabajo - IPS',
            'parent_id' => null,
        ]);

        DB::table('groups')->insert([
            'name' => 'Equipo Naranja',
            'parent_id' => 1,
        ]);

        DB::table('groups')->insert([
            'name' => 'Equipo Rosa',
            'parent_id' => 2,
        ]);

        DB::table('groups')->insert([
            'name' => 'Equipo Amarillo',
            'parent_id' => 3,
        ]);
    }
}
