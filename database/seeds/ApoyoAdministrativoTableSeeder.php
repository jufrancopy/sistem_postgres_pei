<?php

use App\Admin\Proyecto\EPC\ApoyoAdministrativo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ApoyoAdministrativoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApoyoAdministrativo::create([
            'item' => 'Agendamiento',
            'type' => 'servicio_agendamiento',
            'cost' => '450000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        ApoyoAdministrativo::create([
            'item' => 'Archivos y Ficheros',
            'type' => 'servicio_archivo_fichero',
            'cost' => '450000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        ApoyoAdministrativo::create([
            'item' => 'Farmacia',
            'type' => 'servicio_farmacia',
            'cost' => '500000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
