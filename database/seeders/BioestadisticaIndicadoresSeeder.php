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
            ['TOTAL_ODONTOLOGIA', 'Prestaciones odontológicas', 'SP6', 'total', 'prestaciones', 'Suma de prestaciones odontológicas (SP6).'],
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

        $this->upsert(
            'TOTAL_PACIENTES_LAB',
            'Pacientes de laboratorio',
            'Pacientes atendidos en análisis clínicos (SP5).',
            'pacientes',
            [
                'op' => 'sum',
                'form' => 'SP5',
                'field' => 'var_10_analisis_clinicos',
                'metric' => 'total',
            ]
        );
        $this->upsert(
            'TOTAL_DETERMINACIONES_LAB',
            'Determinaciones de laboratorio',
            'Suma de determinaciones de laboratorio (SP5).',
            'determinaciones',
            [
                'op' => 'sum',
                'form' => 'SP5',
                'field' => 'var_10_analisis_clinicos_determinaciones',
                'metric' => 'total',
            ]
        );

        $vacunas = BioestadisticaAnalyticsSupport::sumFormMetric('SP8', 'total');
        if ($vacunas) {
            $this->upsert(
                'TOTAL_VACUNAS',
                'Dosis de vacunación',
                'Suma de dosis aplicadas por vacuna (SP8, columna total).',
                'dosis',
                $vacunas
            );
        } else {
            $this->command?->warn('Indicador TOTAL_VACUNAS omitido: SP8 sin columna total.');
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
