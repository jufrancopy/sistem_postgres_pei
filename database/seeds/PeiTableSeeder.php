<?php

use Illuminate\Database\Seeder;

use App\Admin\Planificacion\Pei\Pei;
use Carbon\Carbon;

class PeiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pei = Pei::create([
            'nivel'         => 'Planificación Estratégica 2020-2024', 
            'tipo'          => 'plan', 
            'nivel_id'      => NULL,
            'user_id'       => 1, 
            'mision'        => 'Garantizar, oportuna y eficientemente, las prestaciones del Seguros Social, con calidad y calidez en el servicio, a nuestros asegurados.',       
            'vision'        => 'Ser la institución que administra el Seguro Social, con amplia cobertura, garantizando la sostenibilidad del sistema, en base al continuo perfeccionamiento de la gestión, contribuyendo al desarrollo del país.', 
            'periodo'       => '2020-2024', 
            'numerador'     => 0, 
            'operador'      => 0, 
            'denominador'   => 0, 
            'meta'          => 0,
            'valores'       => '1°) Ampliar la población obligada al Seguro Social integral, priorizando la formalización, promoviendo la educación, la inclusión de nuevos colectivos y modalidades de trabajo.
            2°) Estructurar una red integrada e integral de servicios de salud, con enfoques preventivos y asistenciales, contemplando la ampliación de la población objetivo y la demografía.
            3°) Impulsar reformas legales orientadas a mejorar la calidad del gasto, aumentar el rendimiento y seguridad de las inversiones, a fin de garantizar la sostenibilidad del sistema.
            4°) Optimizar la gestión administrativa, a través de modelos innovadores y tecnológicos.
            5°) Optimizar las capacidades de talento humano y lograr su bienestar general.', 
            'avance'        => 0,
            'created_at'    => Carbon::now()
            ]);
    
            $pei->dependencies()->attach(1);
    }
}
