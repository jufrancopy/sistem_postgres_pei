<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Models\Bioestadistica\CatalogItem;
use App\Models\Bioestadistica\Catalogo;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Indicador;
use Illuminate\Database\Seeder;

class BioestadisticaHospitalizacionSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = $this->catalog('HOSP_SERVICIOS', 'Servicios hospitalarios', HospEpisodio::SERVICIOS);
        $sexos = $this->catalog('HOSP_SEXO', 'Sexo', ['M' => 'Masculino', 'F' => 'Femenino']);

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
        ] as [$code, $label, $catalog]) {
            $field = $section->fields()->withTrashed()->firstOrNew(['code' => $code]);
            if ($field->trashed()) {
                $field->restore();
            }
            $field->fill([
                'label' => $label,
                'type' => 'tabla',
                'required' => false,
                'catalogo_id' => $catalog->id,
                'config' => [
                    'row_source' => 'catalogo',
                    'row_catalog_id' => $catalog->id,
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
                'descripcion' => 'Matriz calendario de paciente día y camas del período.',
                'periodicidad' => 'mensual',
                'layout_type' => 'matriz',
                'estado' => 'activo',
            ]
        );
        $sp11Section = $sp11->secciones()->firstOrCreate(
            ['titulo' => 'Paciente día'],
            ['descripcion' => 'Una fila por indicador y una columna por día del mes.', 'orden' => 1]
        );
        $matrix = $sp11Section->fields()->withTrashed()->firstOrNew(['code' => 'paciente_dia']);
        if ($matrix->trashed()) {
            $matrix->restore();
        }
        $matrix->fill([
            'label' => 'Paciente día',
            'type' => 'matriz',
            'required' => true,
            'config' => Sp11Matrix::defaultConfig(),
            'orden' => 1,
        ])->save();

        $this->indicators();
        $this->dashboard();
        $this->command?->info('Metadata SP10/SP11, indicadores hospitalarios y panel HOSPITALARIO configurados.');
    }

    private function catalog(string $code, string $name, array $items): Catalogo
    {
        $catalog = Catalogo::updateOrCreate(
            ['codigo' => $code],
            ['nombre' => $name, 'descripcion' => 'Catálogo canónico de hospitalización.', 'activo' => true]
        );
        $order = 1;
        foreach ($items as $itemCode => $label) {
            CatalogItem::updateOrCreate(
                ['catalogo_id' => $catalog->id, 'codigo' => $itemCode],
                ['label' => $label, 'orden' => $order++, 'activo' => true]
            );
        }

        return $catalog->load('items');
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
            ['OCUPACION_HOSPITALARIA', 'Ocupación hospitalaria', '%', null, 2],
            ['ROTACION_CAMAS', 'Índice de rotación de camas', 'índice', null, 2],
            ['INTERVALO_SUSTITUCION', 'Intervalo de sustitución', 'días', null, 2],
        ];
        foreach ($definitions as [$code, $name, $unit, $metric, $decimals]) {
            $indicator = Indicador::updateOrCreate(
                ['codigo' => $code],
                [
                    'nombre' => $name,
                    'descripcion' => 'Indicador hospitalario F6.',
                    'unidad' => $unit,
                    'ambito' => 'establecimiento',
                    'decimales' => $decimals,
                    'activo' => true,
                ]
            );
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
                'OCUPACION_HOSPITALARIA' => [
                    'op' => 'pct',
                    'args' => [
                        ['op' => 'sum', 'form' => 'SP11', 'field' => 'paciente_dia', 'metric' => 'pacientes_dia'],
                        ['op' => 'sum', 'form' => 'SP11', 'field' => 'paciente_dia', 'metric' => 'camas_operativas'],
                    ],
                ],
                'ROTACION_CAMAS' => [
                    'op' => 'div',
                    'args' => [
                        ['op' => 'hosp_count', 'metric' => 'egresos'],
                        ['op' => 'sum', 'form' => 'SP11', 'field' => 'paciente_dia', 'metric' => 'camas_operativas'],
                    ],
                ],
                'INTERVALO_SUSTITUCION' => [
                    'op' => 'div',
                    'args' => [
                        [
                            'op' => 'sub',
                            'args' => [
                                ['op' => 'sum', 'form' => 'SP11', 'field' => 'paciente_dia', 'metric' => 'camas_disponibles'],
                                ['op' => 'sum', 'form' => 'SP11', 'field' => 'paciente_dia', 'metric' => 'pacientes_dia'],
                            ],
                        ],
                        ['op' => 'hosp_count', 'metric' => 'egresos'],
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
                'descripcion' => 'KPIs SP10/SP11: egresos, estancia, mortalidad, ocupación.',
                'user_id' => null,
                'es_default' => false,
            ]);
        } else {
            $dashboard->restore();
            $dashboard->update([
                'nombre' => 'Tablero hospitalario',
                'descripcion' => 'KPIs SP10/SP11: egresos, estancia, mortalidad, ocupación.',
            ]);
        }
        $dashboard->widgets()->delete();
        foreach ([
            ['kpi', 'Egresos', ['indicator' => 'EGRESOS_HOSP'], 0, 0, 3, 2],
            ['kpi', 'Estancia media', ['indicator' => 'ESTANCIA_MEDIA'], 3, 0, 3, 2],
            ['kpi', 'Mortalidad %', ['indicator' => 'MORTALIDAD_HOSP'], 6, 0, 3, 2],
            ['kpi', 'Ocupación %', ['indicator' => 'OCUPACION_HOSPITALARIA'], 9, 0, 3, 2],
            ['lineas', 'Egresos 12 meses', ['indicator' => 'EGRESOS_HOSP'], 0, 2, 6, 3],
            ['barras', 'Cirugías', ['indicator' => 'CIRUGIAS_HOSP', 'dimension' => 'establecimiento'], 6, 2, 6, 3],
            ['indicador', 'Cesáreas', ['indicator' => 'PCT_CESAREAS', 'umbrales' => ['verde' => [0, 30], 'amarillo' => [30.01, 40], 'rojo' => [40.01, 100]]], 0, 5, 4, 2],
            ['kpi', 'Recién nacidos', ['indicator' => 'RECIEN_NACIDOS'], 4, 5, 4, 2],
            ['kpi', 'Rotación de camas', ['indicator' => 'ROTACION_CAMAS'], 8, 5, 4, 2],
        ] as [$tipo, $titulo, $config, $x, $y, $w, $h]) {
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
    }
}
