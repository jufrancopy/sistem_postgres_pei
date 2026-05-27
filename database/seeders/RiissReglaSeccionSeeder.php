<?php

namespace Database\Seeders;

use App\Models\Riiss\FormularioSeccion;
use App\Models\Riiss\ReglaSeccionFormulario;
use Illuminate\Database\Seeder;

class RiissReglaSeccionSeeder extends Seeder
{
    public function run(): void
    {
        $secciones = FormularioSeccion::all()->keyBy(fn($s) => $s->seccion . '|' . ($s->sub_seccion ?? ''));

        // Secciones base que aplican a TODOS los tipos
        $base = [
            'Introducción|'                                          => [true, true, null],
            'Datos de Identificación|'                               => [true, true, null],
            'Datos del encargado de llenado del formulario|'         => [true, true, null],
            'Requerimientos documentales|'                           => [true, true, null],
            'Datos generales del establecimiento|Edificio y terreno' => [true, true, null],
            'Datos generales del establecimiento|Tipo de servicio'   => [true, true, null],
            'Datos generales del establecimiento|Regencia - Dirección' => [true, true, null],
            'Datos generales del establecimiento|Instalaciones'      => [true, true, null],
            'Datos generales del establecimiento|Funcionalidad'      => [true, false, null],
            'Datos generales del establecimiento|Circulación'        => [true, true, null],
            'Datos generales del establecimiento|Accesibilidad'      => [true, true, null],
            'Datos generales del establecimiento|Baños'              => [true, true, null],
            'Consulta externa|Consulta externa'                      => [true, true, null],
            'Consulta externa|Consulta externa, Baños'               => [true, true, null],
            'Consulta externa|Características de consultorios'       => [true, true, null],
            'Servicios intermedios|El establecimiento cuenta con los Servicios intermedios:' => [true, true, null],
            'Farmacia|Características de la Farmacia'                => [true, true, null],
            'Servicios generales|El establecimiento cuenta área de Servicios generales con:' => [true, false, null],
            'Área administrativa|El establecimiento cuenta en el área de Administrativa con:' => [true, true, null],
        ];

        // Secciones adicionales para US y CP
        $usExtras = [
            'Laboratorio|El establecimiento cuenta en Laboratorio con los de:' => [true, true, null],
            'Imágenes|El área para la realización de ultrasonido cuenta con:'  => [true, false, null],
        ];

        // Secciones adicionales para hospitales
        $hospitalExtras = [
            'Internación|Internación, con lugar destinado para:'              => [true, true, null],
            'Internación|Características de las salas de internación'         => [true, true, 'Solo si tiene internacion'],
            'Urgencias|El Establecimiento sanitario cuenta en el área de Urgencias con:' => [true, true, null],
            'Urgencias|Características del área de Urgencias'                 => [true, true, null],
            'Quirófano|El Establecimiento sanitario cuenta en el área de Quirúrgica con:' => [true, true, 'Solo si tiene quirófano'],
            'Quirófano|Características del Quirófano'                         => [true, true, 'Solo si tiene quirófano'],
            'Laboratorio|Regencia'                                            => [true, true, null],
            'Laboratorio|El establecimiento cuenta en Laboratorio con los de:' => [true, true, null],
            'Imágenes|Regencia'                                               => [true, true, null],
            'Imágenes|Características del área de Diagnóstico:'               => [true, true, null],
            'Imágenes|El área para la realización de radiología convencional cuenta con:' => [true, true, null],
            'Imágenes|El área para la realización de ultrasonido cuenta con:' => [true, false, null],
        ];

        // Secciones para mediana complejidad
        $medianaExtras = [
            'U.T.I.|U.T.I.'                                                   => [true, true, null],
            'U.T.I.|El Establecimiento sanitario cuenta en el de Terapia Intensiva cuenta con:' => [true, true, null],
            'Rehabilitación|El área de Rehabilitación cuenta con:'            => [true, false, null],
        ];

        // Secciones para alta complejidad
        $altaExtras = [
            'Oncologia|El área de servicios oncológicos cuenta con:'          => [true, false, null],
            'Nefrología|Servicio de Hemodiálisis cuenta con:'                 => [true, false, null],
        ];

        $tipos = [
            'PUESTO SANITARIO'    => [$base, [], [], [], []],
            'UNIDAD SANITARIA'    => [$base, $usExtras, [], [], []],
            'CLINICA PERIFERICA'  => [$base, $usExtras, [], [], []],
            'CENTROS'             => [$base, $usExtras, [], [], []],
            'HOSPITAL REGIONAL'   => [$base, $usExtras, $hospitalExtras, $medianaExtras, $altaExtras],
            'HOSPITAL'            => [$base, $usExtras, $hospitalExtras, $medianaExtras, $altaExtras],
            'HOSPITAL ESPECIALIZADO' => [$base, $usExtras, $hospitalExtras, $medianaExtras, $altaExtras],
        ];

        $count = 0;
        foreach ($tipos as $tipologia => [$b, $us, $hosp, $med, $alta]) {
            $todasSecciones = array_merge($b, $us, $hosp, $med, $alta);
            foreach ($todasSecciones as $key => [$aplica, $requerida, $condicion]) {
                if (!isset($secciones[$key])) continue;
                try {
                    ReglaSeccionFormulario::create([
                        'tipologia_clasificacion' => $tipologia,
                        'complejidad'             => null,
                        'formulario_seccion_id'   => $secciones[$key]->id,
                        'requerida'               => $requerida,
                        'aplica'                  => $aplica,
                        'condicion'               => $condicion,
                        'nota'                    => "Regla para {$tipologia}",
                    ]);
                    $count++;
                } catch (\Exception $e) {
                    // Ignorar duplicados
                }
            }
        }

        $this->command->info("Reglas insertadas: {$count}");
    }
}
