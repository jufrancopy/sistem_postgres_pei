<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Admin\Planificacion\Foda\FodaPerfil;
use Carbon\Carbon;

class FodaPerfilTableSeeder extends Seeder
{
    public function run()
    {
        FodaPerfil::create([
            'user_id'       =>1, 
            'nombre'        =>'Análisis FODA IPS',
            'contexto'      => 'Planificación Estratégica 2021-2025',
            'modelo_id'     => 1,
            'created_at'    => Carbon::now()
        ]);
    }
}
