<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Planificacion\Foda\FodaCategoria;
use Carbon\Carbon;

class FodaCategoriaTableSeeder extends Seeder
{
    
    public function run()
    {
        FodaCategoria::create([
            'user_id'       => 1,
            'nombre'        => 'Categoría A',
            'ambiente'      => 'Interno',
            'modelo_id'     => '1',
            'created_at'    => Carbon::now()
        ]);

        FodaCategoria::create([
            'user_id'       => 1,
            'nombre'        => 'Categoría B',
            'ambiente'      => 'Externo',
            'modelo_id'     => '1',
            'created_at'    => Carbon::now()
        ]);
    }
}
