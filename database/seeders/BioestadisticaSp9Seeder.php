<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP9 con tipos del dominio 4 (Urgencias) que tengan catálogo de especialidades.
 * Columnas: consultas / observación / procedimiento (+ total).
 */
class BioestadisticaSp9Seeder extends Seeder
{
    /** Orden preferido de bloques en el formulario. */
    private const DETALLE_ORDER = [
        'VAR_4_ATENCION_DE_URGENCIAS_ADULTOS',
        'VAR_4_ATENCION_DE_URGENCIAS_PEDIATRICAS',
        'VAR_4_ATENCION_URGENCIAS_POR_CONVENIO',
    ];

    public function run(): void
    {
        $this->syncSp9DictionaryRows();

        $formulario = Formulario::where('codigo', 'SP9')->first();
        if (! $formulario) {
            $this->command?->warn('SP9 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesUrgencias();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 4 sin tipos con especialidades; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Atenciones de urgencias de adultos, pediátricas y por convenio.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Urgencias'],
            [
                'descripcion' => 'Tipos de registro del dominio Urgencias. Filas = especialidades; columnas = consulta / observación / procedimiento.',
                'orden' => 1,
            ]
        );

        $breakdown = $this->urgenciasBreakdownColumnCodes();
        $orden = 1;
        $published = [];
        foreach ($detalles as $index => $detalle) {
            $code = DictionaryCodes::fieldCode($detalle);
            $this->upsertTable($seccion, $code, $detalle, $orden++, $index === 0, $breakdown);
            $published[] = $detalle->nombre.' ('.$detalle->catalogo_items_count.')';
        }

        $keepCodes = $detalles->map(fn (VariableDetalle $d) => DictionaryCodes::fieldCode($d))->all();
        $seccion->fields()
            ->where('type', 'tabla')
            ->whereNotIn('code', $keepCodes)
            ->each(fn ($field) => $field->delete());

        $observaciones = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Observaciones'],
            [
                'descripcion' => 'Comentarios del establecimiento sobre el período informado.',
                'orden' => 2,
            ]
        );

        $obsField = $observaciones->fields()->withTrashed()->firstOrNew(['code' => 'observaciones']);
        if ($obsField->trashed()) {
            $obsField->restore();
        }
        $obsField->fill([
            'label' => 'Observaciones de la planilla',
            'type' => 'textarea',
            'required' => false,
            'orden' => 1,
        ])->save();

        $this->command?->info('SP9 publicado con '.$detalles->count().' tablas / tipos de urgencias.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesUrgencias(): Collection
    {
        $detalles = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '4')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0)
            ->values();

        $ordered = collect();
        foreach (self::DETALLE_ORDER as $slug) {
            $hit = $detalles->first(fn (VariableDetalle $d) => DictionaryCodes::slug($d) === $slug);
            if ($hit) {
                $ordered->push($hit);
            }
        }
        foreach ($detalles as $detalle) {
            if ($ordered->contains(fn (VariableDetalle $d) => $d->id === $detalle->id)) {
                continue;
            }
            $ordered->push($detalle);
        }

        return $ordered->values();
    }

    /**
     * @param  array<int, string>  $breakdown
     */
    private function upsertTable(
        $seccion,
        string $code,
        VariableDetalle $detalle,
        int $orden,
        bool $required,
        array $breakdown
    ): void {
        $field = $seccion->fields()->withTrashed()->firstOrNew(['code' => $code]);
        if ($field->trashed()) {
            $field->restore();
        }

        $columns = [];
        foreach ($breakdown as $colCode) {
            $columns[] = [
                'code' => $colCode,
                'label' => $this->urgenciasBreakdownLabel($colCode),
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

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => 'Las especialidades sin actividad pueden quedar vacías.',
            'config' => [
                'row_source' => 'detalle_catalogo',
                'row_detalle_id' => $detalle->id,
                'row_label' => 'Especialidad',
                'totals' => true,
                'columns' => $columns,
                'row_total' => [
                    'code' => 'total',
                    'sum_columns' => $breakdown,
                ],
            ],
            'orden' => $orden,
        ])->save();
    }

    /** @return array<int, string> */
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

        (new HealthVariableDictionary)->remember(
            '4',
            $variable->nombre,
            'ATENCION DE URGENCIAS PEDIATRICAS',
            'ATENCION DE URGENCIAS PEDIATRICAS'
        );
    }
}
