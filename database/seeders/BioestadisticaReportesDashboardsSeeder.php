<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Reports\ReportDefinitionValidator;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Reporte;
use Illuminate\Database\Seeder;

class BioestadisticaReportesDashboardsSeeder extends Seeder
{
    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP1')->first();
        $definition = app(ReportDefinitionValidator::class)->validate([
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
            'agg' => 'sum',
            'indicator' => 'TOTAL_CONSULTAS',
            'dimensions' => ['establecimiento', 'periodo'],
            'filtros' => ['estado_record' => 'aprobado'],
            'order_by' => [
                ['ref' => 'periodo', 'dir' => 'asc'],
                ['ref' => 'establecimiento', 'dir' => 'asc'],
            ],
            'limit' => 500,
            'totales' => true,
            'label' => 'Consultas',
        ]);

        $reporte = Reporte::withTrashed()->updateOrCreate(
            ['codigo' => 'CONSULTAS_SP1'],
            [
                'nombre' => 'Consultas médicas por establecimiento y período',
                'descripcion' => 'Suma de total_consultas de SP1. Debe coincidir con TOTAL_CONSULTAS.',
                'formulario_id' => $formulario?->id,
                'definicion' => $definition,
                'publico' => true,
                'deleted_at' => null,
            ]
        );

        $dashboard = Dashboard::withTrashed()
            ->where('codigo', 'INSTITUCIONAL')
            ->whereNull('user_id')
            ->first();
        if (! $dashboard) {
            $dashboard = Dashboard::create([
                'codigo' => 'INSTITUCIONAL',
                'nombre' => 'Tablero institucional de bioestadística',
                'descripcion' => 'Plantilla predeterminada con el piloto TOTAL_CONSULTAS / SP1.',
                'user_id' => null,
                'es_default' => true,
            ]);
        } else {
            $dashboard->restore();
            $dashboard->update([
                'nombre' => 'Tablero institucional de bioestadística',
                'descripcion' => 'Plantilla predeterminada con el piloto TOTAL_CONSULTAS / SP1.',
                'es_default' => true,
            ]);
        }

        Dashboard::query()
            ->whereNull('user_id')
            ->where('id', '<>', $dashboard->id)
            ->update(['es_default' => false]);

        $dashboard->widgets()->delete();
        $source = [
            'form' => 'SP1',
            'field' => 'consultas_por_especialidad',
            'metric' => 'total_consultas',
            'agg' => 'sum',
        ];
        $widgets = [
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
            [
                'tipo' => 'lineas',
                'titulo' => 'Consultas por período',
                'query_config' => [...$source, 'dimension' => 'periodo', 'label' => 'Consultas'],
                'pos_x' => 6, 'pos_y' => 0, 'ancho' => 6, 'alto' => 3,
            ],
            [
                'tipo' => 'barras',
                'titulo' => 'Consultas por establecimiento',
                'query_config' => [...$source, 'dimension' => 'establecimiento', 'label' => 'Consultas'],
                'pos_x' => 0, 'pos_y' => 3, 'ancho' => 6, 'alto' => 4,
            ],
            [
                'tipo' => 'tabla',
                'titulo' => 'Detalle SP1',
                'query_config' => ['reporte_id' => $reporte->id],
                'pos_x' => 6, 'pos_y' => 3, 'ancho' => 6, 'alto' => 4,
            ],
        ];
        foreach ($widgets as $widget) {
            $dashboard->widgets()->create($widget);
        }

        $this->command?->info('Reporte CONSULTAS_SP1 y dashboard institucional configurados.');
    }
}
