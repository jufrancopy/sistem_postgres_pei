<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Reporte;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;

class BioestadisticaHospitalizacionSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = $this->detalle('2', 'HOSPITALIZACIÓN', 'SERVICIOS HOSPITALARIOS', HospEpisodio::SERVICIOS);
        $sexos = $this->detalle('2', 'HOSPITALIZACIÓN', 'SEXO', ['M' => 'Masculino', 'F' => 'Femenino']);

        $sp10 = Formulario::updateOrCreate(
            ['codigo' => 'SP10'],
            [
                'nombre' => 'Hospitalización',
                'descripcion' => 'Consolidado mensual generado desde hosp_episodios. El detalle nominativo no se edita aquí.',
                'periodicidad' => 'mensual',
                'layout_type' => 'nominativo',
                'estado' => 'activo',
            ]
        );
        $section = $sp10->secciones()->firstOrCreate(
            ['titulo' => 'Consolidado mensual'],
            ['descripcion' => 'Valores agregados desde episodios nominativos. Solo lectura en captura.', 'orden' => 1]
        );
        $scalars = [
            'ingresos_total' => 'Ingresos',
            'egresos_total' => 'Egresos',
            'dias_estancia' => 'Días de estancia',
            'fallecidos' => 'Fallecidos',
            'cirugias' => 'Cirugías',
            'cesareas' => 'Cesáreas',
            'partos' => 'Partos (maternidad)',
            'recien_nacidos' => 'Recién nacidos',
        ];
        $order = 1;
        foreach ($scalars as $code => $label) {
            $field = $section->fields()->withTrashed()->firstOrNew(['code' => $code]);
            if ($field->trashed()) {
                $field->restore();
            }
            $field->fill([
                'label' => $label,
                'type' => 'integer',
                'required' => false,
                'min_value' => 0,
                'help_text' => 'Consolidado automático desde episodios SP10. No editar en captura.',
                'orden' => $order++,
            ])->save();
        }
        foreach ([
            ['egresos_por_servicio', 'Egresos por servicio', $servicios],
            ['egresos_por_sexo', 'Egresos por sexo', $sexos],
        ] as [$code, $label, $detalle]) {
            $field = $section->fields()->withTrashed()->firstOrNew(['code' => $code]);
            if ($field->trashed()) {
                $field->restore();
            }
            $field->fill([
                'label' => $label,
                'type' => 'tabla',
                'required' => false,
                'detalle_id' => $detalle->id,
                'config' => [
                    'row_source' => 'detalle_catalogo',
                    'row_detalle_id' => $detalle->id,
                    'row_label' => $label,
                    'totals' => true,
                    'columns' => [['code' => 'total', 'label' => 'Total', 'type' => 'integer', 'min' => 0]],
                ],
                'help_text' => 'Consolidado automático desde episodios SP10.',
                'orden' => $order++,
            ])->save();
        }

        $sp11 = Formulario::updateOrCreate(
            ['codigo' => 'SP11'],
            [
                'nombre' => 'Paciente Día',
                'descripcion' => 'Censo diario del mes: principio del día, ingresos, egresos desglosados y pacientes día.',
                'periodicidad' => 'mensual',
                'layout_type' => 'matriz',
                'estado' => 'activo',
            ]
        );
        $sp11Section = $sp11->secciones()->orderBy('orden')->orderBy('id')->first();
        if (! $sp11Section) {
            $sp11Section = $sp11->secciones()->create([
                'titulo' => 'Censo diario',
                'descripcion' => 'Principio del día, ingresos, egresos (altas/traslados/óbitos/abandono) y totales calculados.',
                'orden' => 1,
            ]);
        } else {
            $sp11Section->update([
                'titulo' => 'Censo diario',
                'descripcion' => 'Principio del día, ingresos, egresos (altas/traslados/óbitos/abandono) y totales calculados.',
                'orden' => 1,
            ]);
        }

        // Una sola sección/campo: elimina duplicados de seeds previos.
        $sp11->secciones()
            ->where('id', '<>', $sp11Section->id)
            ->get()
            ->each(function ($section) {
                $section->fields()->withTrashed()->get()->each->delete();
                $section->delete();
            });

        $matrix = $sp11Section->fields()->withTrashed()->firstOrNew(['code' => 'paciente_dia']);
        if ($matrix->trashed()) {
            $matrix->restore();
        }
        $matrix->fill([
            'label' => 'Matriz mensual',
            'type' => 'matriz',
            'required' => true,
            'config' => Sp11Matrix::defaultConfig(),
            'orden' => 1,
            'help_text' => 'Total egresos y total pacientes día se calculan automáticamente.',
        ])->save();

        Field::query()
            ->where('code', 'paciente_dia')
            ->where('seccion_id', '<>', $sp11Section->id)
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $sp11->id))
            ->get()
            ->each->delete();

        $this->indicators();
        $this->reports();
        $this->dashboard();
        $this->command?->info('Metadata SP10/SP11, indicadores hospitalarios, reportes y panel HOSPITALARIO configurados.');
    }

    /**
     * @param  array<string, string>  $items
     */
    private function detalle(string $codigo, string $variable, string $tipo, array $items): VariableDetalle
    {
        $dictionary = new HealthVariableDictionary();
        $detalle = null;
        foreach ($items as $label) {
            $detalle = $dictionary->remember($codigo, $variable, $tipo, $label)['detalle'];
        }

        return $detalle;
    }

    private function indicators(): void
    {
        $definitions = [
            ['EGRESOS_HOSP', 'Egresos hospitalarios', 'egresos', 'egresos', 0],
            ['INGRESOS_HOSP', 'Ingresos hospitalarios', 'ingresos', 'ingresos', 0],
            ['ESTANCIA_MEDIA', 'Estancia media', 'días', null, 2],
            ['MORTALIDAD_HOSP', 'Mortalidad hospitalaria', '%', null, 2],
            ['CIRUGIAS_HOSP', 'Cirugías', 'cirugías', 'cirugias', 0],
            ['CESAREAS_HOSP', 'Cesáreas', 'cesáreas', 'cesareas', 0],
            ['PCT_CESAREAS', 'Porcentaje de cesáreas', '%', null, 2],
            ['RECIEN_NACIDOS', 'Recién nacidos', 'RN', 'recien_nacidos', 0],
            ['OCUPACION_HOSPITALARIA', 'Ocupación hospitalaria', '%', null, 2, false],
            ['ROTACION_CAMAS', 'Índice de rotación de camas', 'índice', null, 2, false],
            ['INTERVALO_SUSTITUCION', 'Intervalo de sustitución', 'días', null, 2, false],
        ];
        foreach ($definitions as $definition) {
            [$code, $name, $unit, $metric, $decimals] = $definition;
            $activo = $definition[5] ?? true;
            $indicator = Indicador::updateOrCreate(
                ['codigo' => $code],
                [
                    'nombre' => $name,
                    'descripcion' => 'Indicador hospitalario F6.',
                    'unidad' => $unit,
                    'ambito' => 'establecimiento',
                    'decimales' => $decimals,
                    'activo' => $activo,
                ]
            );
            app(\App\Application\Bioestadistica\Sync\CatalogSyncRegistry::class)
                ->rememberIndicador($code);
            if (! $activo) {
                $indicator->formulas()->delete();
                continue;
            }
            $expression = match ($code) {
                'ESTANCIA_MEDIA' => [
                    'op' => 'div',
                    'args' => [
                        ['op' => 'hosp_count', 'metric' => 'dias_estancia'],
                        ['op' => 'hosp_count', 'metric' => 'egresos'],
                    ],
                ],
                'MORTALIDAD_HOSP' => [
                    'op' => 'rate',
                    'factor' => 100,
                    'args' => [
                        ['op' => 'hosp_count', 'metric' => 'fallecidos'],
                        ['op' => 'hosp_count', 'metric' => 'egresos'],
                    ],
                ],
                'PCT_CESAREAS' => [
                    'op' => 'pct',
                    'args' => [
                        ['op' => 'hosp_count', 'metric' => 'cesareas'],
                        ['op' => 'hosp_count', 'metric' => 'partos'],
                    ],
                ],
                default => ['op' => 'hosp_count', 'metric' => $metric],
            };
            app(FormulaAstValidator::class)->validate($expression, $indicator);
            $indicator->formulas()->updateOrCreate(
                ['vigente_desde' => null, 'vigente_hasta' => null],
                ['expresion' => $expression]
            );
        }
    }

    private function reports(): void
    {
        if (! Formulario::where('codigo', 'SP10')->exists()) {
            return;
        }

        BioestadisticaAnalyticsSupport::upsertReport(
            'EGRESOS_ESTABLECIMIENTO',
            'Egresos hospitalarios por establecimiento y período',
            'Egresos consolidados del SP10.',
            [
                'form' => 'SP10',
                'field' => 'egresos_total',
                'agg' => 'sum',
                'indicator' => 'EGRESOS_HOSP',
                'dimensions' => ['establecimiento', 'periodo'],
                'filtros' => ['estado_record' => 'aprobado'],
                'order_by' => [
                    ['ref' => 'periodo', 'dir' => 'asc'],
                    ['ref' => 'establecimiento', 'dir' => 'asc'],
                ],
                'limit' => 500,
                'totales' => true,
                'label' => 'Egresos',
            ]
        );
        BioestadisticaAnalyticsSupport::upsertReport(
            'INGRESOS_ESTABLECIMIENTO',
            'Ingresos hospitalarios por establecimiento y período',
            'Ingresos consolidados del SP10.',
            [
                'form' => 'SP10',
                'field' => 'ingresos_total',
                'agg' => 'sum',
                'indicator' => 'INGRESOS_HOSP',
                'dimensions' => ['establecimiento', 'periodo'],
                'filtros' => ['estado_record' => 'aprobado'],
                'order_by' => [
                    ['ref' => 'periodo', 'dir' => 'asc'],
                    ['ref' => 'establecimiento', 'dir' => 'asc'],
                ],
                'limit' => 500,
                'totales' => true,
                'label' => 'Ingresos',
            ]
        );
        BioestadisticaAnalyticsSupport::upsertReport(
            'EGRESOS_POR_SERVICIO',
            'Egresos por servicio hospitalario',
            'Egresos del SP10 desagregados por servicio del diccionario.',
            [
                'form' => 'SP10',
                'field' => 'egresos_por_servicio',
                'metric' => 'total',
                'agg' => 'sum',
                'dimensions' => ['catalogo_item', 'establecimiento'],
                'filtros' => ['estado_record' => 'aprobado'],
                'order_by' => [['ref' => 'valor', 'dir' => 'desc']],
                'limit' => 500,
                'totales' => true,
                'label' => 'Egresos',
            ]
        );
        if (Formulario::where('codigo', 'SP11')->exists()) {
            BioestadisticaAnalyticsSupport::upsertReport(
                'PACIENTE_DIA_SP11',
                'Paciente día por establecimiento y período',
                'Suma de paciente día informada en SP11.',
                [
                    'form' => 'SP11',
                    'field' => 'paciente_dia',
                    'metric' => 'total_pacientes_dia',
                    'agg' => 'sum',
                    'dimensions' => ['establecimiento', 'periodo'],
                    'filtros' => ['estado_record' => 'aprobado'],
                    'order_by' => [
                        ['ref' => 'periodo', 'dir' => 'asc'],
                        ['ref' => 'establecimiento', 'dir' => 'asc'],
                    ],
                    'limit' => 500,
                    'totales' => true,
                    'label' => 'Paciente día',
                ]
            );
        }
    }

    private function dashboard(): void
    {
        $dashboard = Dashboard::withTrashed()
            ->where('codigo', 'HOSPITALARIO')
            ->whereNull('user_id')
            ->first();
        if (! $dashboard) {
            $dashboard = Dashboard::create([
                'codigo' => 'HOSPITALARIO',
                'nombre' => 'Tablero hospitalario',
                'descripcion' => 'KPIs SP10/SP11: egresos, estancia, mortalidad, paciente día.',
                'user_id' => null,
                'es_default' => false,
            ]);
        } else {
            $dashboard->restore();
            $dashboard->update([
                'nombre' => 'Tablero hospitalario',
                'descripcion' => 'KPIs SP10/SP11: egresos, estancia, mortalidad, paciente día.',
            ]);
        }
        $egresos = Reporte::where('codigo', 'EGRESOS_ESTABLECIMIENTO')->first();
        $porServicio = Reporte::where('codigo', 'EGRESOS_POR_SERVICIO')->first();
        $dashboard->widgets()->delete();
        foreach ([
            ['kpi', 'Egresos', ['indicator' => 'EGRESOS_HOSP'], 0, 0, 3, 2],
            ['kpi', 'Estancia media', ['indicator' => 'ESTANCIA_MEDIA'], 3, 0, 3, 2],
            ['kpi', 'Mortalidad %', ['indicator' => 'MORTALIDAD_HOSP'], 6, 0, 3, 2],
            ['kpi', 'Ingresos', ['indicator' => 'INGRESOS_HOSP'], 9, 0, 3, 2],
            ['lineas', 'Egresos 12 meses', ['form' => 'SP10', 'field' => 'egresos_total', 'agg' => 'sum', 'dimension' => 'periodo', 'label' => 'Egresos'], 0, 2, 6, 3],
            ['barras', 'Cirugías', ['form' => 'SP10', 'field' => 'cirugias', 'agg' => 'sum', 'dimension' => 'establecimiento', 'label' => 'Cirugías'], 6, 2, 6, 3],
            ['indicador', 'Cesáreas', ['indicator' => 'PCT_CESAREAS', 'umbrales' => ['verde' => [0, 30], 'amarillo' => [30.01, 40], 'rojo' => [40.01, 100]]], 0, 5, 4, 2],
            ['kpi', 'Recién nacidos', ['indicator' => 'RECIEN_NACIDOS'], 4, 5, 4, 2],
            ['kpi', 'Cirugías', ['indicator' => 'CIRUGIAS_HOSP'], 8, 5, 4, 2],
            ['lineas', 'Paciente día', ['form' => 'SP11', 'field' => 'paciente_dia', 'metric' => 'total_pacientes_dia', 'agg' => 'sum', 'dimension' => 'periodo', 'label' => 'Paciente día'], 0, 7, 12, 2],
            $egresos ? ['tabla', 'Egresos por establecimiento', ['reporte_id' => $egresos->id], 0, 9, 6, 4] : null,
            $porServicio ? ['tabla', 'Egresos por servicio', ['reporte_id' => $porServicio->id], 6, 9, 6, 4] : null,
        ] as $widget) {
            if (! $widget) {
                continue;
            }
            [$tipo, $titulo, $config, $x, $y, $w, $h] = $widget;
            $dashboard->widgets()->create([
                'tipo' => $tipo,
                'titulo' => $titulo,
                'query_config' => $config,
                'pos_x' => $x,
                'pos_y' => $y,
                'ancho' => $w,
                'alto' => $h,
            ]);
        }
        app(\App\Application\Bioestadistica\Sync\CatalogSyncRegistry::class)
            ->rememberDashboard('HOSPITALARIO');
    }
}
