<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\MarcoReferencial;
use App\Models\Planificacion\MarcoTipo;

class VincularMarcosMecipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Asegurar que el Tipo 'mecip' existe
        MarcoTipo::firstOrCreate(
            ['clave' => 'mecip'],
            ['label' => 'MECIP 2015', 'color' => '#ffc107', 'activo' => true]
        );

        // 2. Definir los 5 Marcos Referenciales del MECIP
        $marcosMecip = [
            'MECIP - Ambiente de Control'          => 'Normas y principios para el entorno organizacional de control.',
            'MECIP - Administración de Riesgos'    => 'Identificación, evaluación y mitigación de riesgos institucionales.',
            'MECIP - Actividades de Control'       => 'Políticas, procedimientos y controles operativos.',
            'MECIP - Información y Comunicación'   => 'Sistemas transparentes de reporte, datos e información oportuna.',
            'MECIP - Seguimiento y Monitoreo'      => 'Evaluación continua del desempeño y cumplimiento de objetivos.'
        ];

        $marcoIds = [];
        foreach ($marcosMecip as $nombre => $descripcion) {
            $marco = MarcoReferencial::firstOrCreate(
                ['nombre' => $nombre, 'tipo' => 'mecip'],
                ['descripcion' => $descripcion, 'activo' => true]
            );
            $marcoIds[] = $marco->id;
        }

        $this->command->info("✓ 5 Marcos Referenciales MECIP asegurados (IDs: " . implode(', ', $marcoIds) . ")");

        // 3. Obtener los perfiles PEI principales y sus Ejes / Objetivos (Nivel 1)
        $perfilesPei = PeiProfile::whereNull('parent_id')
            ->orWhere('type', 'root')
            ->orWhere('id', 'ce99f883-fdd0-4723-8f75-cf689aa8f0fa')
            ->get();

        $ejesPei = PeiProfile::where('type', 'axis')->get();

        $todosLosNodos = $perfilesPei->concat($ejesPei)->unique('id');

        $vinculadosCount = 0;
        foreach ($todosLosNodos as $nodo) {
            $nodo->marcos()->syncWithoutDetaching($marcoIds);
            $vinculadosCount++;
        }

        $this->command->info("✓ Marcos MECIP vinculados exitosamente a {$vinculadosCount} perfiles/ejes del PEI.");
    }
}
