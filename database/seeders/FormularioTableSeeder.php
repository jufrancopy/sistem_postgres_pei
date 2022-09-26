<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Admin\Globales\Formulario\Formulario;

class FormularioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formulario = Formulario::create([
            'formulario' => 'Formulario Test',
            'dependencia_emisor_id' => 1,
            'dependencia_receptor_id' => 2,
            'user_id' => 1,
            'status' => "PENDIENTE",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $variables = 1 ;

        $formulario->variables()->attach($variables);
    }
}
