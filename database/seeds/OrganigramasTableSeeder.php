<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrganigramasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organigramas')->insert([
            'nombre' => 'Consejo de Administracion',
            'user_id' => 1,
            'responsable' => 'Econ. Armando Rodriguez',
            'rango' => 'Presidencia Consejo',
            'nivel' => 'estrategico',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
