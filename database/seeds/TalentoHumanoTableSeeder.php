<?php

use Illuminate\Database\Seeder;

use App\Admin\Proyecto\EPC\TalentoHumano;
use Carbon\Carbon;

class TalentoHumanoTableSeeder extends Seeder
{
    public function run()
        {
            TalentoHumano::create([
                'item' => 'Pediatra',
                // 'specialty_id' => '1',
                'hours'=> '24 horas',
                'type'=> 'medico_de_consultorio',
                'cost' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
    
            TalentoHumano::create([
                'item' => 'Ginecologa',
                // 'specialty_id' => '2',
                'hours'=> '24 horas',
                'type'=> 'medico_de_consultorio',
                'cost' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
    
            TalentoHumano::create([
                'item' => 'Traumatologo',
                // 'specialty_id' => '3',
                'hours'=> '24 horas',
                'type'=> 'medico_de_consultorio',
                'cost' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
}
