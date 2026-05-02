<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Estadistica\SiessExtracto;

/**
 * Job que aplica el Art. 8 de la Resolución 266/2022:
 * Si la dirección responsable no responde en 5 días hábiles,
 * el extracto se aprueba automáticamente por silencio administrativo.
 *
 * Programar en Kernel.php: ->dailyAt('07:00')
 */
class SiessAprobarPorSilencioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $vencidos = SiessExtracto::vencidos()->get();

        if ($vencidos->isEmpty()) {
            return;
        }

        Log::info("SIESS Silencio Administrativo: procesando {$vencidos->count()} extracto(s) vencidos.");

        foreach ($vencidos as $extracto) {
            try {
                $extracto->aprobarPorSilencio();
                Log::info("SIESS: Extracto #{$extracto->id} ({$extracto->indicador->codigo}) aprobado por silencio.");
            } catch (\Exception $e) {
                Log::error("SIESS: Error al aprobar extracto #{$extracto->id}: " . $e->getMessage());
            }
        }
    }
}
