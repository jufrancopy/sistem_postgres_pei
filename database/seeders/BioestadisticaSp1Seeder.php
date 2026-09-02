<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;

class BioestadisticaSp1Seeder extends Seeder
{
    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP1')->first();
        if (! $formulario) {
            $this->command?->warn('SP1 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalle = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '1')->where('activo', true))
            ->with('variable')
            ->get()
            ->first(fn (VariableDetalle $item) => DictionaryCodes::slug($item) === 'VAR_1_CONSULTA_POR_ESPECIALIDAD');

        if (! $detalle) {
            $this->command?->warn('El diccionario no tiene CONSULTA POR ESPECIALIDAD; ejecute BioestadisticaVariablesSeeder primero.');

            return;
        }

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Consultas por especialidad'],
            [
                'descripcion' => 'Total de consultas del mes por especialidad médica.',
                'orden' => 1,
            ]
        );

        $seccion->fields()->updateOrCreate(
            ['code' => 'consultas_por_especialidad'],
            [
                'label' => 'Consultas por especialidad',
                'type' => 'tabla',
                'required' => true,
                'detalle_id' => $detalle->id,
                'help_text' => 'Cargue el total de consultas de cada especialidad. Las especialidades sin actividad pueden quedar vacías.',
                'config' => [
                    'row_source' => 'detalle_catalogo',
                    'row_detalle_id' => $detalle->id,
                    'row_label' => 'Especialidad',
                    'totals' => true,
                    'columns' => [
                        [
                            'code' => 'total_consultas',
                            'label' => 'Total consultas',
                            'type' => 'integer',
                            'min' => 0,
                        ],
                    ],
                ],
                'orden' => 1,
            ]
        );

        $observaciones = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Observaciones'],
            [
                'descripcion' => 'Comentarios del establecimiento sobre el período informado.',
                'orden' => 2,
            ]
        );

        $observaciones->fields()->updateOrCreate(
            ['code' => 'observaciones'],
            [
                'label' => 'Observaciones de la planilla',
                'type' => 'textarea',
                'required' => false,
                'orden' => 1,
            ]
        );

        $formulario->update(['estado' => 'activo']);

        $this->command?->info('SP1 configurado y publicado con '.$detalle->catalogoItems()->where('activo', true)->count().' especialidades.');
    }
}
