<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Proyecto\EPC\Especialidad;
use Carbon\Carbon;

class EspecialidadTableSeeder extends Seeder
{
    public function run()
    {
        Especialidad::create([
            'item' => 'Pediatria',
            'type' => 'primer_nivel',
            'detail'=> 'Detalles de la Especialidad Pediatria',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Especialidad::create([
            'item' => 'Ginecología',
            'type' => 'segundo_nivel',
            'detail'=> 'Detalles de la Especialidad Ginecologia',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Especialidad::create([
            'item' => 'Traumatología',
            'type' => 'tercer_nivel',
            'detail'=> 'Detalles de la Especialidad Traumatología',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
