<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Proyecto\EPC\OtroServicio;
use Carbon\Carbon;

class OtroServicioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OtroServicio::create([
            'item' => 'Limpieza General',
            'type' => 'limpieza',
            'cost' => '22',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        OtroServicio::create([
            'item' => 'Seguridad',
            'type' => 'seguridad',
            'cost' => '22',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        OtroServicio::create([
            'item' => 'Cocina y Gastronomía',
            'type' => 'gastronomia',
            'cost' => '22',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        OtroServicio::create([
            'item' => 'Ambulancia',
            'type' => 'ambulancia',
            'cost' => '22',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
