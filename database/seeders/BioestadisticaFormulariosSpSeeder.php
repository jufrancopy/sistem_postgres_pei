<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class BioestadisticaFormulariosSpSeeder extends Seeder
{
    public function run(): void
    {
        $configured = 0;
        $configured += $this->publishTabular(
            'SP2',
            'Servicios de enfermería del período, agrupados por tipo de registro.',
            'Servicios de enfermería',
            'Una tabla por tipo de prestación de enfermería.',
            $this->detalles('13'),
            $this->columns(['total' => 'Total'])
        );
        $configured += $this->publishTabular(
            'SP3',
            'Estudios de baja complejidad: pacientes y estudios del período.',
            'Estudios de baja complejidad',
            'Métodos diagnósticos de baja complejidad.',
            $this->detalles('12'),
            $this->columns(['pacientes' => 'Pacientes', 'estudios' => 'Estudios'])
        );
        $configured += $this->publishTabular(
            'SP4',
            'Estudios de alta complejidad: pacientes y estudios del período.',
            'Estudios de alta complejidad',
            'Métodos diagnósticos de alta complejidad.',
            $this->detalles('11'),
            $this->columns(['pacientes' => 'Pacientes', 'estudios' => 'Estudios'])
        );
        $configured += $this->publishTabular(
            'SP5',
            'Determinaciones de laboratorio del período.',
            'Análisis clínicos',
            'Pacientes atendidos y determinaciones por prestación de laboratorio.',
            $this->detalles('10'),
            $this->columns(['total' => 'Total'])
        );
        $configured += $this->publishTabular(
            'SP6',
            'Prestaciones odontológicas del período.',
            'Odontología',
            'Pacientes atendidos y prestaciones odontológicas.',
            $this->detalles('14', exact: ['VAR_14_PROCEDIMIENTOS_ODONTOLOGICOS']),
            $this->columns(['total' => 'Total'])
        );
        $configured += $this->publishTabular(
            'SP7',
            'Procedimientos no odontológicos del período.',
            'Procedimientos',
            'Pacientes y prestaciones por tipo de procedimiento.',
            $this->detalles('14', except: ['VAR_14_PROCEDIMIENTOS_ODONTOLOGICOS']),
            $this->columns(['pacientes' => 'Pacientes', 'prestaciones' => 'Prestaciones'])
        );
        $configured += $this->publishTabular(
            'SP8',
            'Dosis aplicadas por vacuna, sexo y grupo etario.',
            'Vacunación',
            'Filas de vacuna y columnas cruzadas de sexo por edad.',
            $this->detalles('16', exact: ['VAR_16_CLASIFICACION_DE_VACUNACION']),
            $this->vaccinationColumns(),
            'Vacuna',
            [
                'row_total' => [
                    'code' => 'total',
                    'sum_columns' => $this->vaccinationBreakdownColumnCodes(),
                ],
            ]
        );
        $configured += $this->publishTabular(
            'SP9',
            'Atenciones de urgencias de adultos, pediátricas y por convenio.',
            'Urgencias',
            'Consultas, observación y procedimientos de urgencias.',
            $this->detalles('4'),
            $this->urgenciasColumns(),
            'Prestación',
            [
                'row_total' => [
                    'code' => 'total',
                    'sum_columns' => $this->urgenciasBreakdownColumnCodes(),
                ],
            ]
        );
        $this->syncSp9DictionaryRows();
        $configured += $this->publishTabular(
            'SP12',
            'Indicadores de VIH y tuberculosis del período.',
            'VIH y tuberculosis',
            'Prestaciones de epidemiología de VIH y TB/EPOC.',
            $this->detalles('17', exact: [
                'VAR_17_VIH',
                'VAR_17_TUBERCULOSIS_Y_EPOC',
                'VAR_17_EPIDEMIOLOGIA_Y_VIGILANCIA',
            ]),
            $this->columns(['total' => 'Total'])
        );
        $configured += $this->publishTabular(
            'SP13',
            'Programas de salud sexual y reproductiva, crónicos, nutrición y violencia.',
            'Programas de salud',
            'Prestaciones de programas distintos de VIH/TB.',
            $this->detalles('17', except: [
                'VAR_17_VIH',
                'VAR_17_TUBERCULOSIS_Y_EPOC',
                'VAR_17_EPIDEMIOLOGIA_Y_VIGILANCIA',
            ]),
            $this->columns(['total' => 'Total'])
        );
        $configured += $this->publishTabular(
            'SP14',
            'Medicamentos prescritos e insumos entregados en el período.',
            'Medicamentos e insumos',
            'Entrega de medicamentos y empadronamiento de crónicos.',
            $this->detalles('x', exact: ['VAR_X_MEDICAMENTOS_PRESCRIPTOS']),
            $this->columns(['total' => 'Total'])
        );

        $this->command?->info("Formularios SP tabulares configurados: {$configured}.");
    }

    /**
     * @param  Collection<int, VariableDetalle>  $detalles
     * @param  array<int, array<string, mixed>>  $columns
     */
    private function publishTabular(
        string $code,
        string $description,
        string $sectionTitle,
        string $sectionDescription,
        Collection $detalles,
        array $columns,
        string $rowLabel = 'Prestación',
        array $fieldConfigExtra = []
    ): int {
        $formulario = Formulario::where('codigo', $code)->first();
        if (! $formulario) {
            $this->command?->warn("{$code} no existe; ejecute BioestadisticaFormulariosSeeder primero.");

            return 0;
        }
        if ($detalles->isEmpty()) {
            $this->command?->warn("{$code} sin detalles en el diccionario; ejecute BioestadisticaVariablesSeeder primero.");

            return 0;
        }

        $formulario->update([
            'descripcion' => $description,
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => $sectionTitle],
            [
                'descripcion' => $sectionDescription,
                'orden' => 1,
            ]
        );

        $orden = 1;
        foreach ($detalles as $index => $detalle) {
            $this->upsertTable(
                $seccion,
                DictionaryCodes::fieldCode($detalle),
                $detalle->nombre,
                $detalle,
                $columns,
                $orden++,
                $index === 0,
                $rowLabel,
                $fieldConfigExtra
            );
        }

        $observaciones = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Observaciones'],
            [
                'descripcion' => 'Comentarios del establecimiento sobre el período informado.',
                'orden' => 2,
            ]
        );
        $field = $observaciones->fields()->withTrashed()->firstOrNew(['code' => 'observaciones']);
        if ($field->trashed()) {
            $field->restore();
        }
        $field->fill([
            'label' => 'Observaciones de la planilla',
            'type' => 'textarea',
            'required' => false,
            'orden' => 1,
        ])->save();

        $this->command?->info("{$code} publicado con {$detalles->count()} tablas.");

        return 1;
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     */
    private function upsertTable(
        $seccion,
        string $code,
        string $label,
        VariableDetalle $detalle,
        array $columns,
        int $orden,
        bool $required,
        string $rowLabel,
        array $fieldConfigExtra = []
    ): void {
        $field = $seccion->fields()->withTrashed()->firstOrNew(['code' => $code]);
        if ($field->trashed()) {
            $field->restore();
        }
        $field->fill([
            'label' => $label,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => 'Las prestaciones sin actividad pueden quedar vacías.',
            'config' => array_merge([
                'row_source' => 'detalle_catalogo',
                'row_detalle_id' => $detalle->id,
                'row_label' => $rowLabel,
                'totals' => true,
                'columns' => $columns,
            ], $fieldConfigExtra),
            'orden' => $orden,
        ])->save();
    }

    /**
     * @param  array<int, string>  $except
     * @param  array<int, string>  $exact
     * @return Collection<int, VariableDetalle>
     */
    private function detalles(string $variableCodigo, array $except = [], array $exact = []): Collection
    {
        $detalles = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', $variableCodigo)->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0)
            ->sortBy(fn (VariableDetalle $detalle) => DictionaryCodes::slug($detalle))
            ->values();

        if ($exact !== []) {
            $detalles = $detalles->filter(
                fn (VariableDetalle $detalle) => in_array(DictionaryCodes::slug($detalle), $exact, true)
            )->values();
        }
        if ($except !== []) {
            $detalles = $detalles->reject(
                fn (VariableDetalle $detalle) => in_array(DictionaryCodes::slug($detalle), $except, true)
            )->values();
        }

        return $detalles;
    }

    /**
     * @param  array<string, string>  $labels
     * @return array<int, array<string, mixed>>
     */
    private function columns(array $labels): array
    {
        $columns = [];
        foreach ($labels as $code => $label) {
            $columns[] = [
                'code' => $code,
                'label' => $label,
                'type' => 'integer',
                'min' => 0,
            ];
        }

        return $columns;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function vaccinationColumns(): array
    {
        $columns = [
            ['code' => 'total', 'label' => 'Total', 'type' => 'integer', 'min' => 0],
        ];
        foreach ($this->vaccinationBreakdownColumnCodes() as $code) {
            $columns[] = [
                'code' => $code,
                'label' => $this->vaccinationBreakdownLabel($code),
                'type' => 'integer',
                'min' => 0,
            ];
        }

        return $columns;
    }

    /**
     * @return array<int, string>
     */
    private function vaccinationBreakdownColumnCodes(): array
    {
        $groups = [
            'menores_1' => 'Menores de 1 año',
            '1_3' => '1 a 3 años',
            '4_14' => '4 a 14 años',
            '15_59' => '15 a 59 años',
            '60_mas' => '60 y más',
        ];
        $codes = [];
        foreach (array_keys($groups) as $code) {
            $codes[] = "m_{$code}";
            $codes[] = "f_{$code}";
        }

        return $codes;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function urgenciasColumns(): array
    {
        $columns = [];
        foreach ($this->urgenciasBreakdownColumnCodes() as $code) {
            $columns[] = [
                'code' => $code,
                'label' => $this->urgenciasBreakdownLabel($code),
                'type' => 'integer',
                'min' => 0,
            ];
        }
        $columns[] = [
            'code' => 'total',
            'label' => 'Total',
            'type' => 'integer',
            'min' => 0,
        ];

        return $columns;
    }

    /**
     * @return array<int, string>
     */
    private function urgenciasBreakdownColumnCodes(): array
    {
        return ['consultas', 'observacion', 'procedimiento'];
    }

    private function urgenciasBreakdownLabel(string $code): string
    {
        return match ($code) {
            'consultas' => 'Consultas',
            'observacion' => 'Observación',
            'procedimiento' => 'Procedimiento',
            default => $code,
        };
    }

    private function syncSp9DictionaryRows(): void
    {
        $variable = Variable::query()->where('codigo', '4')->first();
        if (! $variable) {
            $this->command?->warn('SP9: variable 4 no encontrada; omitiendo ajuste de filas del diccionario.');

            return;
        }

        (new HealthVariableDictionary())->remember(
            '4',
            $variable->nombre,
            'ATENCION DE URGENCIAS PEDIATRICAS',
            'ATENCION DE URGENCIAS PEDIATRICAS'
        );
    }

    private function vaccinationBreakdownLabel(string $code): string
    {
        $map = [
            'm_menores_1' => 'M Menores de 1 año',
            'f_menores_1' => 'F Menores de 1 año',
            'm_1_3' => 'M 1 a 3 años',
            'f_1_3' => 'F 1 a 3 años',
            'm_4_14' => 'M 4 a 14 años',
            'f_4_14' => 'F 4 a 14 años',
            'm_15_59' => 'M 15 a 59 años',
            'f_15_59' => 'F 15 a 59 años',
            'm_60_mas' => 'M 60 y más',
            'f_60_mas' => 'F 60 y más',
        ];

        return $map[$code] ?? $code;
    }
}
