<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Estadistica\SiessExtracto;
use App\Models\Estadistica\SiessNotificacion;

/**
 * Envía alertas de vencimiento próximo (1-2 días hábiles restantes).
 * Corre diariamente a las 8am.
 */
class SiessAlertarVencimientoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Extractos que vencen en los próximos 2 días hábiles
        $proximos = SiessExtracto::pendientes()
            ->with(['modulo', 'indicador', 'periodo'])
            ->get()
            ->filter(fn($e) => in_array($e->diasRestantes(), [1, 2]));

        foreach ($proximos as $extracto) {
            // Evitar duplicar alertas del mismo día
            $yaNotificado = SiessNotificacion::where('extracto_id', $extracto->id)
                ->where('tipo', 'vencimiento_proximo')
                ->whereDate('created_at', today())
                ->exists();

            if (!$yaNotificado) {
                SiessNotificacion::crearAlertaVencimiento($extracto);
                Log::info("SIESS Alerta: Extracto #{$extracto->id} vence en {$extracto->diasRestantes()} día(s).");
            }
        }
    }
}
