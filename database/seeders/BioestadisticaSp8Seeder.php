<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP8 (Vacunación): filas = vacunas del tipo CLASIFICACION DE VACUNACION (dominio 16).
 * Columnas = cruce sexo × grupo etario (layout de la planilla / tipo beneficiarios).
 */
class BioestadisticaSp8Seeder extends Seeder
{
    private const VACUNA_SLUG = 'VAR_16_CLASIFICACION_DE_VACUNACION';

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP8')->first();
        if (! $formulario) {
            $this->command?->warn('SP8 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesVacunacion();
        if ($detalles->isEmpty()) {
            $this->command?->warn('No hay CLASIFICACION DE VACUNACION en dominio 16; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Dosis aplicadas por vacuna, sexo y grupo etario (dominio 16).',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Vacunación'],
            [
                'descripcion' => 'Filas de vacuna y columnas cruzadas de sexo por edad.',
                'orden' => 1,
            ]
        );

        $breakdown = $this->vaccinationBreakdownColumnCodes();
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

        $this->command?->info('SP8 publicado con '.$detalles->count().' tabla(s) de vacunación.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesVacunacion(): Collection
    {
        return VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '16')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0
                && DictionaryCodes::slug($detalle) === self::VACUNA_SLUG)
            ->values();
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

        $columns = [
            ['code' => 'total', 'label' => 'Total', 'type' => 'integer', 'min' => 0],
        ];
        foreach ($breakdown as $colCode) {
            $columns[] = [
                'code' => $colCode,
                'label' => $this->vaccinationBreakdownLabel($colCode),
                'type' => 'integer',
                'min' => 0,
            ];
        }

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => 'Las vacunas sin actividad pueden quedar vacías.',
            'config' => [
                'row_source' => 'detalle_catalogo',
                'row_detalle_id' => $detalle->id,
                'row_label' => 'Vacuna',
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
    private function vaccinationBreakdownColumnCodes(): array
    {
        $groups = ['menores_1', '1_3', '4_14', '15_59', '60_mas'];
        $codes = [];
        foreach ($groups as $group) {
            $codes[] = "m_{$group}";
            $codes[] = "f_{$group}";
        }

        return $codes;
    }

    private function vaccinationBreakdownLabel(string $code): string
    {
        return match ($code) {
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
            default => $code,
        };
    }
}
