<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplejidadTiposSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'grado'               => 1,
                'nombre'              => 'No Hospitalaria de Baja Complejidad',
                'nombre_legacy'       => 'No Hospitalario de Baja Complejidad',
                'nivel_atencion'      => 1,
                'tipo_establecimiento'=> 'Puesto Sanitario',
                'es_hospitalario'     => false,
                'requiere_internacion'=> false,
                'requiere_quirofano'  => false,
                'requiere_uti'        => false,
                'requiere_urgencias'  => false,
                'color'               => '#22c55e',
                'activo'              => true,
            ],
            [
                'grado'               => 2,
                'nombre'              => 'No Hospitalaria de Mediana Complejidad',
                'nombre_legacy'       => 'No Hospitalario de Mediana Complejidad',
                'nivel_atencion'      => 2,
                'tipo_establecimiento'=> 'Clínica Periférica',
                'es_hospitalario'     => false,
                'requiere_internacion'=> false,
                'requiere_quirofano'  => false,
                'requiere_uti'        => false,
                'requiere_urgencias'  => false,
                'color'               => '#84cc16',
                'activo'              => true,
            ],
            [
                'grado'               => 3,
                'nombre'              => 'Hospitalaria de Baja Complejidad',
                'nombre_legacy'       => 'Hospitalario 1 Baja Complejidad',
                'nivel_atencion'      => 2,
                'tipo_establecimiento'=> 'Hospital - Unidad Sanitaria',
                'es_hospitalario'     => true,
                'requiere_internacion'=> true,
                'requiere_quirofano'  => true,
                'requiere_uti'        => false,
                'requiere_urgencias'  => true,
                'color'               => '#eab308',
                'activo'              => true,
            ],
            [
                'grado'               => 4,
                'nombre'              => 'Hospitalaria de Mediana Complejidad',
                'nombre_legacy'       => 'Hospitalario 2 Mediana Complejidad',
                'nivel_atencion'      => 3,
                'tipo_establecimiento'=> 'Hospital General Regional',
                'es_hospitalario'     => true,
                'requiere_internacion'=> true,
                'requiere_quirofano'  => true,
                'requiere_uti'        => true,
                'requiere_urgencias'  => true,
                'color'               => '#f97316',
                'activo'              => true,
            ],
            [
                'grado'               => 5,
                'nombre'              => 'Hospitalaria de Alta Complejidad',
                'nombre_legacy'       => 'Hospitalario 3 Alta Complejidad',
                'nivel_atencion'      => 3,
                'tipo_establecimiento'=> 'Hospital General Interregional',
                'es_hospitalario'     => true,
                'requiere_internacion'=> true,
                'requiere_quirofano'  => true,
                'requiere_uti'        => true,
                'requiere_urgencias'  => true,
                'color'               => '#ef4444',
                'activo'              => true,
            ],
            [
                'grado'               => 6,
                'nombre'              => 'Hospitalaria Especializada de Alta Complejidad',
                'nombre_legacy'       => null,
                'nivel_atencion'      => 4,
                'tipo_establecimiento'=> 'Hospital Especializado',
                'es_hospitalario'     => true,
                'requiere_internacion'=> true,
                'requiere_quirofano'  => true,
                'requiere_uti'        => true,
                'requiere_urgencias'  => true,
                'color'               => '#7c3aed',
                'activo'              => true,
            ],
        ];

        foreach ($tipos as $tipo) {
            DB::table('complejidad_tipos')->updateOrInsert(
                ['grado' => $tipo['grado']],
                array_merge($tipo, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
