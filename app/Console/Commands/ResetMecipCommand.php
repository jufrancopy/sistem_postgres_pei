<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetMecipCommand extends Command
{
    /**
     * El nombre y firma del comando artisan.
     *
     * @var string
     */
    protected $signature = 'mecip:reset {--force : Ejecutar sin confirmación interactiva}';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Limpia a cero todas las tablas del módulo MECIP IPS (Casos, Componentes, Actividades, Tareas, Cambios y Justificaciones)';

    /**
     * Ejecuta el comando console.
     */
    public function handle()
    {
        $this->info('==========================================================');
        $this->info('   🧹 REINICIADOR A CERO DEL MÓDULO MECIP IPS');
        $this->info('==========================================================');

        if (!$this->option('force')) {
            if (!$this->confirm('⚠️ ¿Estás seguro que deseas ELIMINAR TODOS los expedientes, productos, insumos y auditorías de MECIP? Esta acción no se puede deshacer.')) {
                $this->comment('Operación cancelada por el usuario.');
                return Command::SUCCESS;
            }
        }

        $this->warn('🔥 Vaciando tablas del módulo MECIP en PostgreSQL...');

        try {
            $tables = [
                'mecip_justificaciones',
                'mecip_caso_cambios',
                'mecip_caso_tareas',
                'mecip_caso_actividades',
                'mecip_caso_componentes',
                'mecip_casos',
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::statement("TRUNCATE TABLE {$table} CASCADE;");
                    $this->line("  • Tabla <comment>{$table}</comment> truncada y reiniciada a 0.");
                }
            }

            $this->info('==========================================================');
            $this->info('✨ ¡ÉXITO! El módulo MECIP ha quedado totalmente limpio a cero.');
            $this->info('==========================================================');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Error al truncar tablas MECIP: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
