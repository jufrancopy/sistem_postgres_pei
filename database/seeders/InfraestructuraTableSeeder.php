<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Proyecto\EPC\Infraestructura;
use Carbon\Carbon;

class InfraestructuraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        Infraestructura::create([
            'item' => 'Consultorio de Pediatría',
            'type' => 'servicio',
            'measurement' => '211',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Infraestructura::create([
            'item' => 'Agendamiento',
            'type' => 'administrativo',
            'measurement' => '211',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Infraestructura::create([
            'item' => 'Sala de Internado',
            'type' => 'hospitalizacion',
            'measurement' => '211',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Infraestructura::create([
            'item' => 'Sala de Urgencias Quirúrgicas',
            'type' => 'quirurgico',
            'measurement' => '211',
            'cost' => '11',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
