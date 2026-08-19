<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\Indicador;
use Illuminate\Database\Seeder;

class BioestadisticaIndicadoresSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsert(
            'TOTAL_CONSULTAS',
            'Total de consultas médicas',
            'Suma de consultas informadas en SP1 para el período y ámbito seleccionados.',
            'consultas',
            [
                'op' => 'sum',
                'form' => 'SP1',
                'field' => 'consultas_por_especialidad',
                'metric' => 'total_consultas',
            ]
        );

        $production = [
            ['TOTAL_ENFERMERIA', 'Servicios de enfermería', 'SP2', 'total', 'prestaciones', 'Suma de prestaciones de enfermería (SP2).'],
            ['TOTAL_ESTUDIOS_BAJA', 'Estudios de baja complejidad', 'SP3', 'estudios', 'estudios', 'Suma de estudios de baja complejidad (SP3).'],
            ['TOTAL_PACIENTES_BAJA', 'Pacientes en estudios de baja complejidad', 'SP3', 'pacientes', 'pacientes', 'Pacientes con estudios de baja complejidad (SP3).'],
            ['TOTAL_ESTUDIOS_ALTA', 'Estudios de alta complejidad', 'SP4', 'estudios', 'estudios', 'Suma de estudios de alta complejidad (SP4).'],
            ['TOTAL_PACIENTES_ALTA', 'Pacientes en estudios de alta complejidad', 'SP4', 'pacientes', 'pacientes', 'Pacientes con estudios de alta complejidad (SP4).'],
            ['TOTAL_DETERMINACIONES_LAB', 'Determinaciones de laboratorio', 'SP5', 'determinaciones', 'determinaciones', 'Suma de determinaciones de laboratorio (SP5).'],
            ['TOTAL_PACIENTES_LAB', 'Pacientes de laboratorio', 'SP5', 'pacientes', 'pacientes', 'Pacientes con análisis clínicos (SP5).'],
            ['TOTAL_ODONTOLOGIA', 'Prestaciones odontológicas', 'SP6', 'prestaciones', 'prestaciones', 'Suma de prestaciones odontológicas (SP6).'],
            ['TOTAL_PROCEDIMIENTOS', 'Procedimientos no odontológicos', 'SP7', 'prestaciones', 'prestaciones', 'Suma de procedimientos (SP7).'],
            ['TOTAL_URGENCIAS', 'Atenciones de urgencias', 'SP9', 'total', 'atenciones', 'Suma de atenciones de urgencias (SP9).'],
            ['TOTAL_EPIDEMIOLOGIA', 'Indicadores de VIH y tuberculosis', 'SP12', 'total', 'registros', 'Suma de prestaciones de epidemiología VIH/TB (SP12).'],
            ['TOTAL_PROGRAMAS', 'Programas de salud', 'SP13', 'total', 'registros', 'Suma de prestaciones de programas de salud (SP13).'],
            ['TOTAL_MEDICAMENTOS', 'Medicamentos e insumos', 'SP14', 'total', 'entregas', 'Suma de medicamentos e insumos informados (SP14).'],
        ];

        foreach ($production as [$code, $name, $form, $metric, $unit, $description]) {
            $expression = BioestadisticaAnalyticsSupport::sumFormMetric($form, $metric);
            if (! $expression) {
                $this->command?->warn("Indicador {$code} omitido: {$form} no tiene la métrica {$metric}.");
                continue;
            }
            $this->upsert($code, $name, $description, $unit, $expression);
        }

        $vacunas = BioestadisticaAnalyticsSupport::sumAllNumericColumns('SP8');
        if ($vacunas) {
            $this->upsert(
                'TOTAL_VACUNAS',
                'Dosis de vacunación',
                'Suma de dosis aplicadas por vacuna, sexo y edad (SP8).',
                'dosis',
                $vacunas
            );
        } else {
            $this->command?->warn('Indicador TOTAL_VACUNAS omitido: SP8 sin columnas numéricas.');
        }

        if (Indicador::activos()->whereIn('codigo', ['TOTAL_CONSULTAS', 'TOTAL_URGENCIAS'])->count() === 2) {
            $this->upsert(
                'CONSULTAS_Y_URGENCIAS',
                'Consultas + urgencias',
                'Suma de consultas médicas (SP1) y atenciones de urgencias (SP9).',
                'atenciones',
                [
                    'op' => 'add',
                    'args' => [
                        ['indicator' => 'TOTAL_CONSULTAS'],
                        ['indicator' => 'TOTAL_URGENCIAS'],
                    ],
                ]
            );
        }

        $this->command?->info('Indicadores de producción ambulatoria configurados.');
    }

    private function upsert(
        string $code,
        string $name,
        string $description,
        string $unit,
        array $expression,
        int $decimals = 0
    ): void {
        BioestadisticaAnalyticsSupport::upsertIndicator(
            $code,
            $name,
            $description,
            $unit,
            $expression,
            $decimals
        );
        $this->command?->info("Indicador {$code} configurado.");
    }
}
