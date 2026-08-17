<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Models\Bioestadistica\Indicador;
use Illuminate\Database\Seeder;

class BioestadisticaIndicadoresSeeder extends Seeder
{
    public function run(): void
    {
        $indicator = Indicador::updateOrCreate(
            ['codigo' => 'TOTAL_CONSULTAS'],
            [
                'nombre' => 'Total de consultas médicas',
                'descripcion' => 'Suma de consultas informadas en SP1 para el período y ámbito seleccionados.',
                'unidad' => 'consultas',
                'ambito' => 'establecimiento',
                'decimales' => 0,
                'activo' => true,
            ]
        );
        $expression = [
            'op' => 'sum',
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
        ];
        app(FormulaAstValidator::class)->validate($expression, $indicator);
        $indicator->formulas()->updateOrCreate(
            ['vigente_desde' => null, 'vigente_hasta' => null],
            ['expresion' => $expression]
        );

        $this->command?->info('Indicador piloto TOTAL_CONSULTAS configurado.');
    }
}
