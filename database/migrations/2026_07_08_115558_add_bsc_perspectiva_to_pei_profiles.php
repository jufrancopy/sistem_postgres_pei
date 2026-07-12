<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar columna bsc_perspectiva al perfil PEI
        DB::statement("
            ALTER TABLE planificacion.pei_profiles
            ADD COLUMN IF NOT EXISTS bsc_perspectiva VARCHAR(50) NULL
            CHECK (bsc_perspectiva IN (
                'financiera',
                'clientes',
                'procesos',
                'aprendizaje'
            ))
        ");

        // 2. Migrar vinculaciones existentes de BSC al nuevo campo
        $mapeo = [
            37 => 'financiera',
            38 => 'clientes',
            39 => 'procesos',
            40 => 'aprendizaje',
        ];

        foreach ($mapeo as $marcoId => $perspectiva) {
            $vinculados = DB::table('planificacion.pei_profile_marcos')
                ->where('marco_id', $marcoId)
                ->pluck('pei_profile_id');

            if ($vinculados->isNotEmpty()) {
                DB::table('planificacion.pei_profiles')
                    ->whereIn('id', $vinculados)
                    ->whereNull('bsc_perspectiva')
                    ->update(['bsc_perspectiva' => $perspectiva]);

                // Eliminar las vinculaciones BSC de la tabla de marcos
                DB::table('planificacion.pei_profile_marcos')
                    ->where('marco_id', $marcoId)
                    ->delete();
            }
        }

        // 3. Eliminar los 4 registros BSC de marcos_referenciales
        DB::table('planificacion.marcos_referenciales')
            ->whereIn('id', [37, 38, 39, 40])
            ->delete();
    }

    public function down(): void
    {
        // Restaurar los 4 marcos BSC
        $ahora = now();
        DB::table('planificacion.marcos_referenciales')->insert([
            ['id' => 37, 'nombre' => 'BSC - Perspectiva Financiera',                   'tipo' => 'bsc', 'activo' => true, 'created_at' => $ahora, 'updated_at' => $ahora],
            ['id' => 38, 'nombre' => 'BSC - Perspectiva del Cliente / Asegurado',      'tipo' => 'bsc', 'activo' => true, 'created_at' => $ahora, 'updated_at' => $ahora],
            ['id' => 39, 'nombre' => 'BSC - Perspectiva de Procesos Internos',         'tipo' => 'bsc', 'activo' => true, 'created_at' => $ahora, 'updated_at' => $ahora],
            ['id' => 40, 'nombre' => 'BSC - Perspectiva de Aprendizaje y Crecimiento','tipo' => 'bsc', 'activo' => true, 'created_at' => $ahora, 'updated_at' => $ahora],
        ]);

        DB::statement("ALTER TABLE planificacion.pei_profiles DROP COLUMN IF EXISTS bsc_perspectiva");
    }
};
