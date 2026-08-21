<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\Formulario;
use Illuminate\Database\Seeder;

class BioestadisticaFormulariosSeeder extends Seeder
{
    public function run(): void
    {
        $forms = [
            ['SP1', 'Consultas Médicas', 'mensual', 'tabular'],
            ['SP2', 'Enfermería', 'mensual', 'tabular'],
            ['SP3', 'Estudios Baja Complejidad', 'mensual', 'tabular'],
            ['SP4', 'Estudios Alta Complejidad', 'mensual', 'tabular'],
            ['SP5', 'Laboratorio', 'mensual', 'tabular'],
            ['SP6', 'Odontología', 'mensual', 'tabular'],
            ['SP7', 'Procedimientos', 'mensual', 'tabular'],
            ['SP8', 'Vacunación', 'mensual', 'tabular'],
            ['SP9', 'Urgencias', 'mensual', 'tabular'],
            ['SP10', 'Hospitalización', 'mensual', 'nominativo'],
            ['SP11', 'Paciente Día', 'diaria', 'matriz'],
            ['SP12', 'VIH y Tuberculosis', 'mensual', 'tabular'],
            ['SP13', 'Programas de Salud', 'mensual', 'tabular'],
            ['SP14', 'Medicamentos e Insumos', 'mensual', 'tabular'],
        ];

        foreach ($forms as [$code, $name, $periodicity, $layout]) {
            $form = Formulario::updateOrCreate(
                ['codigo' => $code],
                [
                    'nombre' => $name,
                    'descripcion' => 'Estructura inicial; los campos se completan desde el constructor o importador Excel.',
                    'periodicidad' => $periodicity,
                    'layout_type' => $layout,
                    'estado' => 'borrador',
                ]
            );
        }
    }
}
