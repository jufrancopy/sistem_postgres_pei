<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;

/**
 * Publica SP8 (Vacunación) según planilla interior:
 * 1) Vacunas en fila + Total (series por prestador).
 * 2) Clasificación agregada (beneficiarios / sexo / edad) en fila + Total.
 */
class BioestadisticaSp8Seeder extends Seeder
{
    private const VACUNA_SLUG = 'VAR_16_CLASIFICACION_DE_VACUNACION';

    private const BENEFICIARIOS_SLUG = 'VAR_16_CLASIFICACION_BENEFICIARIOS_DE_VACUNACION';

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP8')->first();
        if (! $formulario) {
            $this->command?->warn('SP8 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $vacunas = $this->detalleBySlug(self::VACUNA_SLUG);
        $beneficiarios = $this->detalleBySlug(self::BENEFICIARIOS_SLUG);

        if (! $vacunas) {
            $this->command?->warn('No hay CLASIFICACION DE VACUNACION en dominio 16; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Dosis por vacuna (total) y clasificación agregada por beneficiarios, sexo y grupo etario (dominio 16).',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Vacunación'],
            [
                'descripcion' => 'Tabla de vacunas y tabla de clasificación (beneficiarios / sexo / edad).',
                'orden' => 1,
            ]
        );

        $orden = 1;
        $published = [];
        $keepCodes = [];

        $this->upsertTable(
            $seccion,
            DictionaryCodes::fieldCode($vacunas),
            $vacunas,
            $orden++,
            true,
            'Vacuna',
            'Informe el total de dosis por vacuna. Las vacunas sin actividad pueden quedar vacías.'
        );
        $keepCodes[] = DictionaryCodes::fieldCode($vacunas);
        $published[] = $vacunas->nombre.' ('.$vacunas->catalogo_items_count.')';

        if ($beneficiarios && $beneficiarios->catalogo_items_count > 0) {
            $this->upsertTable(
                $seccion,
                DictionaryCodes::fieldCode($beneficiarios),
                $beneficiarios,
                $orden++,
                false,
                'Clasificación',
                'Clasificación agregada del período (no por vacuna): beneficiarios, sexo y rangos de edad.'
            );
            $keepCodes[] = DictionaryCodes::fieldCode($beneficiarios);
            $published[] = $beneficiarios->nombre.' ('.$beneficiarios->catalogo_items_count.')';
        } else {
            $this->command?->warn('CLASIFICACION BENEFICIARIOS sin ítems; ejecute BioestadisticaSp8VacunacionInteriorDictionarySeeder.');
        }

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

        $this->command?->info('SP8 publicado con '.count($published).' tabla(s) de vacunación.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    private function detalleBySlug(string $slug): ?VariableDetalle
    {
        return VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '16')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->first(fn (VariableDetalle $detalle) => $detalle->catalogo_items_count > 0
                && DictionaryCodes::slug($detalle) === $slug);
    }

    private function upsertTable(
        $seccion,
        string $code,
        VariableDetalle $detalle,
        int $orden,
        bool $required,
        string $rowLabel,
        string $helpText
    ): void {
        $field = $seccion->fields()->withTrashed()->firstOrNew(['code' => $code]);
        if ($field->trashed()) {
            $field->restore();
        }

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => $helpText.' '.TablaIpsConvenioColumns::helpText(),
            'config' => TablaIpsConvenioColumns::config((int) $detalle->id, $rowLabel),
            'orden' => $orden,
        ])->save();
    }
}
