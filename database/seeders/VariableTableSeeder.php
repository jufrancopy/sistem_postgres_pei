<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VariableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'Consulta Médica',
                'parent_id' => null,
                'type' => 'service',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
    
            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'Servicios de Limpieza e Higiéne',
                'parent_id' => 1,
                'type' => 'require',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'Posee Baños?',
                'parent_id' => 2,
                'type' => 'item',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'SI',
                'parent_id' => 3,
                'type' => 'response',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'Posee Lavatorios?',
                'parent_id' => 2,
                'type' => 'item',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'SI',
                'parent_id' => 5,
                'type' => 'response',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'Posee rampa para discapacitados?',
                'parent_id' => 2,
                'type' => 'item',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('estadistica.formulario_variables')->insert([
                'name' => 'SI',
                'parent_id' => 7,
                'type' => 'response',
                'user_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
