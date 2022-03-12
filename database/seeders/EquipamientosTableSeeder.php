<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Proyecto\EPC\Equipamiento;
use Carbon\Carbon;

class EquipamientosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Equipamiento::create([
            'item' => 'Escritorio',
            'type' => 'equipo_mobiliario',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Equipamiento::create([
            'item' => 'Silla',
            'type' => 'equipo_mobiliario',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Equipamiento::create([
            'item' => 'Computadora',
            'type' => 'equipo_informatico',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
