<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Planificacion\Foda\FodaModelo;
use Carbon\Carbon;

class FodaModeloTableSeeder extends Seeder
{
    protected $fillable = ['user_id','nombre', 'autor'];

    public function run()
    {
        FodaModelo::create([
            'user_id' => 1,
            'nombre' => "Análisis FODA",
            'autor'  => "Instituto de Previsión Social",
            'created_at' => Carbon::now()
        ]);
    }
}
