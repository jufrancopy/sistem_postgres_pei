<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MecipBpmScraperService;

class SyncMecipBpmCommand extends Command
{
    /**
     * El nombre y firma del comando artisan.
     *
     * @var string
     */
    protected $signature = 'mecip:sync-bpm 
                            {--process= : ID del proceso activo m_process_id en BPM Servlets} 
                            {--case= : Número de caso asignado (ej. CASO-BPM-2026-001)} 
                            {--url= : URL base del servidor BPM de IPS (ej. http://bpm.ips.gov.py)} 
                            {--user= : Usuario de acceso institucional al BPM} 
                            {--password= : Contraseña de acceso institucional al BPM}';

    /**
     * Descripción del comando.
     *
     * @var string
     */
    protected $description = 'Ejecuta el scraping autenticado contra el sistema BPM Servlet del IPS para sincronizar Productos, Insumos y Tareas en la BD PostgreSQL.';

    /**
     * Ejecuta el comando console.
     */
    public function handle(): int
    {
        $this->info("==========================================================");
        $this->info("   🚀 SINCRONIZADOR AUTOMÁTICO MECIP BPM IPS (SCRAPING)   ");
        $this->info("==========================================================");

        $processId = $this->option('process') ?? '101';
        $numeroCaso = $this->option('case') ?? "CASO-BPM-{$processId}";
        $url       = $this->option('url');
        $user      = $this->option('user');
        $pass      = $this->option('password');

        $this->comment("Iniciando cliente HTTP autenticado hacia BPM Servlets...");
        $this->line("• Proceso ID: {$processId}");
        $this->line("• Número de Caso: {$numeroCaso}");

        $scraper = new MecipBpmScraperService($url, $user, $pass);

        $this->info("🔐 Autenticando y capturando cookie de sesión (JSESSIONID)...");
        $authSuccess = $scraper->authenticate();

        if ($authSuccess) {
            $this->info("✅ Autenticación exitosa en el servidor BPM.");
        } else {
            $this->warn("⚠️ No se pudo autenticar en el servidor BPM de IPS (Credenciales rechazadas o usuario no registrado en Gobernanza).");
            $this->comment("👉 Sugerencia: Verifica tu usuario/clave institucionales del BPM IPS, o bien utiliza la opción 'Pegar Formulario HTML Directo' desde la Web /admin/mecip/control.");
        }

        $this->info("🔍 Navegando a m_process_id={$processId} y parseando tablas HTML...");
        $caso = $scraper->scrapeProcess($processId, $numeroCaso);

        if ($caso) {
            $this->info("==========================================================");
            $this->info("🎉 ¡ÉXITO! Caso sincronizado correctamente en PostgreSQL:");
            $this->line("  ID Local: {$caso->id}");
            $this->line("  Número de Caso: {$caso->numero_caso}");
            $this->line("  Código Subproceso: {$caso->codigo_subproceso}");
            $this->line("  Macroproceso: {$caso->macroproceso}");
            $this->line("  Proceso: {$caso->proceso}");
            $this->line("  Subproceso: {$caso->subproceso}");
            $this->line("  Componentes Sincronizados: " . $caso->componentes()->count());
            $this->line("  Actividades Sincronizadas: " . $caso->actividades()->count());
            $this->info("==========================================================");
            return Command::SUCCESS;
        }

        $this->error("❌ Error: No se pudo extraer ni sincronizar el caso desde BPM Servlets.");
        return Command::FAILURE;
    }
}
