<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Planificacion\Foda\FodaAspecto;
use Carbon\Carbon;

class FodaAspectoTableSeeder extends Seeder
{
    public function run()
    {
        FodaAspecto::create([
            'user_id'           => 1,
            'nombre'            => 'Aspecto A', 
            'categoria_id'      => 1
        ]);

        FodaAspecto::create([
            'user_id'           => 1,
            'nombre'            => 'Aspecto B', 
            'categoria_id'      => 1
        ]);

        FodaAspecto::create([
            'user_id'           => 1,
            'nombre'            => 'Aspecto A', 
            'categoria_id'      => 2
        ]);

        FodaAspecto::create([
            'user_id'           => 1,
            'nombre'            => 'Aspecto B', 
            'categoria_id'      => 2
        ]);
    }
}
