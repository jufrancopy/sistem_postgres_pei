<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Reporte;
use Illuminate\Database\Seeder;

class BioestadisticaReportesDashboardsSeeder extends Seeder
{
    public function run(): void
    {
        $consultas = $this->report(
            'CONSULTAS_SP1',
            'Consultas médicas por establecimiento y período',
            'Suma de total_consultas de SP1. Debe coincidir con TOTAL_CONSULTAS.',
            [
                'form' => 'SP1',
                'field' => 'consultas_por_especialidad',
                'metric' => 'total_consultas',
                'agg' => 'sum',
                'indicator' => 'TOTAL_CONSULTAS',
                'dimensions' => ['establecimiento', 'periodo'],
                'label' => 'Consultas',
            ]
        );

        $this->report(
            'CONSULTAS_POR_PRESTACION',
            'Consultas por prestación',
            'Consultas médicas desagregadas por prestación del diccionario (SP1).',
            [
                'form' => 'SP1',
                'field' => 'consultas_por_especialidad',
                'metric' => 'total_consultas',
                'agg' => 'sum',
                'dimensions' => ['catalogo_item', 'periodo'],
                'label' => 'Consultas',
            ]
        );

        $this->reportFromForm(
            'URGENCIAS_SP9',
            'Urgencias por establecimiento y período',
            'Atenciones de urgencias (SP9).',
            'SP9',
            'total',
            'TOTAL_URGENCIAS',
            'Atenciones'
        );
        $this->reportFromForm(
            'ENFERMERIA_SP2',
            'Enfermería por establecimiento y período',
            'Prestaciones de enfermería (SP2).',
            'SP2',
            'total',
            'TOTAL_ENFERMERIA',
            'Prestaciones'
        );
        $this->reportFromForm(
            'LABORATORIO_SP5',
            'Determinaciones de laboratorio por establecimiento',
            'Determinaciones de análisis clínicos (SP5).',
            'SP5',
            'determinaciones',
            'TOTAL_DETERMINACIONES_LAB',
            'Determinaciones'
        );
        $this->reportFromForm(
            'ODONTOLOGIA_SP6',
            'Odontología por establecimiento y período',
            'Prestaciones odontológicas (SP6).',
            'SP6',
            'prestaciones',
            'TOTAL_ODONTOLOGIA',
            'Prestaciones'
        );
        $this->reportFromForm(
            'ESTUDIOS_BAJA_SP3',
            'Estudios de baja complejidad por establecimiento',
            'Estudios de baja complejidad (SP3).',
            'SP3',
            'estudios',
            'TOTAL_ESTUDIOS_BAJA',
            'Estudios'
        );
        $this->reportFromForm(
            'ESTUDIOS_ALTA_SP4',
            'Estudios de alta complejidad por establecimiento',
            'Estudios de alta complejidad (SP4).',
            'SP4',
            'estudios',
            'TOTAL_ESTUDIOS_ALTA',
            'Estudios'
        );
        $this->reportFromForm(
            'PROCEDIMIENTOS_SP7',
            'Procedimientos por establecimiento y período',
            'Procedimientos no odontológicos (SP7).',
            'SP7',
            'prestaciones',
            'TOTAL_PROCEDIMIENTOS',
            'Prestaciones'
        );
        $this->reportFromForm(
            'PROGRAMAS_SP13',
            'Programas de salud por establecimiento',
            'Prestaciones de programas de salud (SP13).',
            'SP13',
            'total',
            'TOTAL_PROGRAMAS',
            'Registros'
        );
        $this->reportFromForm(
            'EPIDEMIOLOGIA_SP12',
            'VIH y tuberculosis por establecimiento',
            'Prestaciones de epidemiología (SP12).',
            'SP12',
            'total',
            'TOTAL_EPIDEMIOLOGIA',
            'Registros'
        );
        $this->reportFromForm(
            'MEDICAMENTOS_SP14',
            'Medicamentos e insumos por establecimiento',
            'Medicamentos prescritos e insumos (SP14).',
            'SP14',
            'total',
            'TOTAL_MEDICAMENTOS',
            'Entregas'
        );

        $this->report(
            'SALUD_CONSOLIDADO',
            'Salud consolidado',
            'Consolidado dinámico de cantidades tabulares de todos los SP (equivalente a la planilla Salud consolidado).',
            [
                'consolidado' => true,
                'agg' => 'sum',
                'label' => 'Cantidad',
                'dimensions' => [
                    'area_gestion', 'departamento', 'establecimiento',
                    'estructura_departamento', 'estructura_servicio',
                    'variable', 'tipo_prestacion', 'campo', 'catalogo_item', 'prestador', 'periodo',
                ],
                'order_by' => [
                    ['ref' => 'periodo', 'dir' => 'asc'],
                    ['ref' => 'establecimiento', 'dir' => 'asc'],
                    ['ref' => 'variable', 'dir' => 'asc'],
                    ['ref' => 'catalogo_item', 'dir' => 'asc'],
                ],
                'limit' => 5000,
                'totales' => true,
                'filtros' => ['estado_record' => 'aprobado'],
            ]
        );

        $urgencias = BioestadisticaAnalyticsSupport::firstTableSource('SP9', 'total');
        $lab = BioestadisticaAnalyticsSupport::firstTableSource('SP5', 'determinaciones');
        $odonto = BioestadisticaAnalyticsSupport::firstTableSource('SP6', 'prestaciones');
        $consultasSource = [
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
            'agg' => 'sum',
        ];

        $institucional = BioestadisticaAnalyticsSupport::upsertDashboard(
            'INSTITUCIONAL',
            'Tablero institucional de bioestadística',
            'Piloto de consultas SP1 y vista rápida de producción ambulatoria.',
            array_values(array_filter([
                [
                    'tipo' => 'kpi',
                    'titulo' => 'Total de consultas',
                    'query_config' => ['indicator' => 'TOTAL_CONSULTAS', 'label' => 'Consultas'],
                    'pos_x' => 0, 'pos_y' => 0, 'ancho' => 3, 'alto' => 2,
                ],
                [
                    'tipo' => 'indicador',
                    'titulo' => 'Total consultas (semáforo)',
                    'query_config' => [
                        'indicator' => 'TOTAL_CONSULTAS',
                        'umbrales' => [
                            'verde' => [1, 999999999],
                            'amarillo' => [0, 0],
                            'rojo' => [-1, -0.01],
                        ],
                    ],
                    'pos_x' => 3, 'pos_y' => 0, 'ancho' => 3, 'alto' => 2,
                ],
                $this->indicatorWidget('kpi', 'Urgencias', 'TOTAL_URGENCIAS', 6, 0, 3, 2),
                $this->indicatorWidget('kpi', 'Laboratorio', 'TOTAL_DETERMINACIONES_LAB', 9, 0, 3, 2),
                [
                    'tipo' => 'lineas',
                    'titulo' => 'Consultas por período',
                    'query_config' => [...$consultasSource, 'dimension' => 'periodo', 'label' => 'Consultas'],
                    'pos_x' => 0, 'pos_y' => 2, 'ancho' => 6, 'alto' => 3,
                ],
                [
                    'tipo' => 'barras',
                    'titulo' => 'Consultas por establecimiento',
                    'query_config' => [...$consultasSource, 'dimension' => 'establecimiento', 'label' => 'Consultas'],
                    'pos_x' => 6, 'pos_y' => 2, 'ancho' => 6, 'alto' => 3,
                ],
                [
                    'tipo' => 'tabla',
                    'titulo' => 'Detalle SP1',
                    'query_config' => ['reporte_id' => $consultas->id],
                    'pos_x' => 0, 'pos_y' => 5, 'ancho' => 12, 'alto' => 4,
                ],
            ])),
            true
        );

        Dashboard::query()
            ->whereNull('user_id')
            ->where('id', '<>', $institucional->id)
            ->update(['es_default' => false]);

        $prestacionId = Reporte::where('codigo', 'CONSULTAS_POR_PRESTACION')->value('id');
        BioestadisticaAnalyticsSupport::upsertDashboard(
            'AMBULATORIO',
            'Tablero de producción ambulatoria',
            'Consultas, urgencias, laboratorio, odontología y demás SP de producción.',
            array_values(array_filter([
                $this->indicatorWidget('kpi', 'Consultas', 'TOTAL_CONSULTAS', 0, 0, 3, 2),
                $this->indicatorWidget('kpi', 'Urgencias', 'TOTAL_URGENCIAS', 3, 0, 3, 2),
                $this->indicatorWidget('kpi', 'Consultas + urgencias', 'CONSULTAS_Y_URGENCIAS', 6, 0, 3, 2),
                $this->indicatorWidget('kpi', 'Vacunación', 'TOTAL_VACUNAS', 9, 0, 3, 2),
                $this->indicatorWidget('kpi', 'Enfermería', 'TOTAL_ENFERMERIA', 0, 2, 3, 2),
                $this->indicatorWidget('kpi', 'Laboratorio', 'TOTAL_DETERMINACIONES_LAB', 3, 2, 3, 2),
                $this->indicatorWidget('kpi', 'Odontología', 'TOTAL_ODONTOLOGIA', 6, 2, 3, 2),
                $this->indicatorWidget('kpi', 'Procedimientos', 'TOTAL_PROCEDIMIENTOS', 9, 2, 3, 2),
                [
                    'tipo' => 'lineas',
                    'titulo' => 'Consultas 12 meses',
                    'query_config' => [...$consultasSource, 'dimension' => 'periodo', 'label' => 'Consultas'],
                    'pos_x' => 0, 'pos_y' => 4, 'ancho' => 6, 'alto' => 3,
                ],
                $urgencias ? [
                    'tipo' => 'barras',
                    'titulo' => 'Urgencias por establecimiento',
                    'query_config' => [...$urgencias, 'agg' => 'sum', 'dimension' => 'establecimiento', 'label' => 'Atenciones'],
                    'pos_x' => 6, 'pos_y' => 4, 'ancho' => 6, 'alto' => 3,
                ] : null,
                [
                    'tipo' => 'pastel',
                    'titulo' => 'Consultas por establecimiento',
                    'query_config' => [...$consultasSource, 'dimension' => 'establecimiento', 'label' => 'Consultas'],
                    'pos_x' => 0, 'pos_y' => 7, 'ancho' => 4, 'alto' => 4,
                ],
                $lab ? [
                    'tipo' => 'barras',
                    'titulo' => 'Laboratorio por establecimiento',
                    'query_config' => [...$lab, 'agg' => 'sum', 'dimension' => 'establecimiento', 'label' => 'Determinaciones'],
                    'pos_x' => 4, 'pos_y' => 7, 'ancho' => 4, 'alto' => 4,
                ] : null,
                $odonto ? [
                    'tipo' => 'barras',
                    'titulo' => 'Odontología por establecimiento',
                    'query_config' => [...$odonto, 'agg' => 'sum', 'dimension' => 'establecimiento', 'label' => 'Prestaciones'],
                    'pos_x' => 8, 'pos_y' => 7, 'ancho' => 4, 'alto' => 4,
                ] : null,
                $this->indicatorWidget('kpi', 'Estudios baja complejidad', 'TOTAL_ESTUDIOS_BAJA', 0, 11, 3, 2),
                $this->indicatorWidget('kpi', 'Estudios alta complejidad', 'TOTAL_ESTUDIOS_ALTA', 3, 11, 3, 2),
                $this->indicatorWidget('kpi', 'Programas de salud', 'TOTAL_PROGRAMAS', 6, 11, 3, 2),
                $this->indicatorWidget('kpi', 'Medicamentos', 'TOTAL_MEDICAMENTOS', 9, 11, 3, 2),
                [
                    'tipo' => 'heatmap',
                    'titulo' => 'Consultas establecimiento × período',
                    'query_config' => [
                        ...$consultasSource,
                        'dimension_x' => 'periodo',
                        'dimension_y' => 'establecimiento',
                    ],
                    'pos_x' => 0, 'pos_y' => 13, 'ancho' => 12, 'alto' => 4,
                ],
                $prestacionId ? [
                    'tipo' => 'tabla',
                    'titulo' => 'Consultas por prestación',
                    'query_config' => ['reporte_id' => $prestacionId],
                    'pos_x' => 0, 'pos_y' => 17, 'ancho' => 12, 'alto' => 4,
                ] : null,
            ]))
        );

        $this->command?->info('Reportes de producción y tableros INSTITUCIONAL / AMBULATORIO configurados.');
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    private function report(string $code, string $name, string $description, array $definition): Reporte
    {
        $definition = array_merge([
            'agg' => 'sum',
            'filtros' => ['estado_record' => 'aprobado'],
            'order_by' => [
                ['ref' => 'periodo', 'dir' => 'asc'],
                ['ref' => 'establecimiento', 'dir' => 'asc'],
            ],
            'limit' => 500,
            'totales' => true,
        ], $definition);
        if (empty($definition['consolidado'])
            && in_array('catalogo_item', $definition['dimensions'] ?? [], true)) {
            $definition['order_by'] = [
                ['ref' => 'valor', 'dir' => 'desc'],
            ];
        }

        $reporte = BioestadisticaAnalyticsSupport::upsertReport($code, $name, $description, $definition);
        $this->command?->info("Reporte {$code} configurado.");

        return $reporte;
    }

    private function reportFromForm(
        string $code,
        string $name,
        string $description,
        string $form,
        string $metric,
        string $indicator,
        string $label
    ): ?Reporte {
        $source = BioestadisticaAnalyticsSupport::firstTableSource($form, $metric);
        if (! $source) {
            $this->command?->warn("Reporte {$code} omitido: {$form} sin métrica {$metric}.");

            return null;
        }

        return $this->report($code, $name, $description, [
            ...$source,
            'agg' => 'sum',
            'indicator' => \App\Models\Bioestadistica\Indicador::activos()->where('codigo', $indicator)->exists()
                ? $indicator
                : null,
            'dimensions' => ['establecimiento', 'periodo'],
            'label' => $label,
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function indicatorWidget(
        string $tipo,
        string $titulo,
        string $indicator,
        int $x,
        int $y,
        int $w,
        int $h
    ): ?array {
        if (! \App\Models\Bioestadistica\Indicador::activos()->where('codigo', $indicator)->exists()) {
            return null;
        }

        return [
            'tipo' => $tipo,
            'titulo' => $titulo,
            'query_config' => ['indicator' => $indicator],
            'pos_x' => $x,
            'pos_y' => $y,
            'ancho' => $w,
            'alto' => $h,
        ];
    }
}
