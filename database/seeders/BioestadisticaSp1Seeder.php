<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\Catalogo;
use App\Models\Bioestadistica\Formulario;
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

        $catalogo = Catalogo::where('codigo', 'VAR_1_CONSULTA_POR_ESPECIALIDAD')->first();
        if (! $catalogo) {
            $this->command?->warn('Catálogo de especialidades no encontrado; ejecute BioestadisticaVariablesSeeder primero.');

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
                'catalogo_id' => $catalogo->id,
                'help_text' => 'Cargue el total de consultas de cada especialidad. Las especialidades sin actividad pueden quedar vacías.',
                'config' => [
                    'row_source' => 'catalogo',
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

        $this->command?->info('SP1 configurado y publicado con ' . $catalogo->items()->count() . ' especialidades.');
    }
}
