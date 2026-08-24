<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Admin\Globales\ActivityTaskActa;
use App\Models\User;
use App\Services\GamificationService;
use App\Models\Gamification\GamificationPoint;

class ComputarPuntosActasMecipCommand extends Command
{
    /**
     * El nombre y firma del comando artisan.
     *
     * @var string
     */
    protected $signature = 'siplan:computar-puntos-actas {--force : Computar incluso si el acta aún está en estado borrador}';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Computa retroactivamente +100 Puntos de Gamificación a los redactores de Actas MECIP registradas en el sistema.';

    /**
     * Ejecuta el comando.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Iniciando cómputo retroactivo de 100 PTS por Actas MECIP...");

        $query = ActivityTaskActa::with(['createdBy', 'participantes', 'task']);

        if (!$this->option('force')) {
            $query->where('estado', 'finalizada');
        }

        $actas = $query->get();

        if ($actas->isEmpty()) {
            $this->warn("No se encontraron actas elegibles para el cómputo (puedes usar --force para incluir borradores con firmas).");
            return 0;
        }

        $gamificationService = app(GamificationService::class);
        $countOtorgados = 0;

        foreach ($actas as $acta) {
            // Resolver redactor: created_by -> updated_by -> responsable de la tarea asignada
            $redactorId = $acta->created_by ?: ($acta->updated_by ?: $acta->task?->user_id);

            if (!$redactorId) {
                $this->error("Acta ID {$acta->id} (N° {$acta->numero_acta}) no tiene redactor asociado en la BD.");
                continue;
            }

            $redactor = User::find($redactorId);
            if (!$redactor) {
                $this->error("Usuario redactor ID {$redactorId} no fue encontrado en la tabla users.");
                continue;
            }

            $yaOtorgado = GamificationPoint::where('user_id', $redactor->id)
                ->where('action_type', 'acta_mecip_oficializada')
                ->where('reference_id', (string)$acta->id)
                ->exists();

            if ($yaOtorgado) {
                $this->line("Acta ID {$acta->id}: El redactor {$redactor->name} ya posee sus 100 PTS acreditados.");
                continue;
            }

            $gamificationService->awardPoints(
                $redactor,
                'acta_mecip_oficializada',
                '✍️ Redacción y Firma Completa de Acta MECIP N° ' . ($acta->numero_acta ?: $acta->id),
                100,
                $acta
            );

            $countOtorgados++;
            $this->info("✅ Se otorgaron +100 PTS a {$redactor->name} por el Acta N° " . ($acta->numero_acta ?: $acta->id));
        }

        $this->info("¡Proceso completado! Se acreditaron puntos a {$countOtorgados} redactor(es).");
        return 0;
    }
}
