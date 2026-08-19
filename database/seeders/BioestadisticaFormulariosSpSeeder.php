<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Formulario;
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
            'Pacientes y determinaciones por prestación de laboratorio.',
            $this->detalles('10'),
            $this->columns(['pacientes' => 'Pacientes', 'determinaciones' => 'Determinaciones'])
        );
        $configured += $this->publishTabular(
            'SP6',
            'Prestaciones odontológicas del período.',
            'Odontología',
            'Pacientes y prestaciones odontológicas.',
            $this->detalles('14', exact: ['VAR_14_PROCEDIMIENTOS_ODONTOLOGICOS']),
            $this->columns(['pacientes' => 'Pacientes', 'prestaciones' => 'Prestaciones'])
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
            'Vacuna'
        );
        $configured += $this->publishTabular(
            'SP9',
            'Atenciones de urgencias de adultos, pediátricas y por convenio.',
            'Urgencias',
            'Consultas, observación y procedimientos de urgencias.',
            $this->detalles('4'),
            $this->columns(['total' => 'Total'])
        );
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
        string $rowLabel = 'Prestación'
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
                $rowLabel
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
        string $rowLabel
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
            'config' => [
                'row_source' => 'diccionario',
                'row_detalle_id' => $detalle->id,
                'row_label' => $rowLabel,
                'totals' => true,
                'columns' => $columns,
            ],
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
            ->withCount(['prestaciones as prestaciones_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->prestaciones_count > 0)
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
        $groups = [
            'menores_1' => 'Menores de 1 año',
            '1_3' => '1 a 3 años',
            '4_14' => '4 a 14 años',
            '15_59' => '15 a 59 años',
            '60_mas' => '60 y más',
        ];
        $columns = [];
        foreach ($groups as $code => $label) {
            $columns[] = ['code' => "m_{$code}", 'label' => "M {$label}", 'type' => 'integer', 'min' => 0];
            $columns[] = ['code' => "f_{$code}", 'label' => "F {$label}", 'type' => 'integer', 'min' => 0];
        }

        return $columns;
    }
}
