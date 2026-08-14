<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Planificacion\PeiAccionReporte;
use App\Admin\Planificacion\Pei\PeiProfile;

class RecalculatePeiReportesCommand extends Command
{
    protected $signature = 'pei:recalcular-reportes';
    protected $description = 'Recalcula de forma precisa el semáforo y el % de avance de todos los reportes de acciones PEI';

    public function handle()
    {
        $count = 0;
        $reportes = PeiAccionReporte::all();
        foreach ($reportes as $rp) {
            $accion = PeiProfile::with('indicador')->find($rp->pei_profile_id);
            if ($accion && $accion->indicador) {
                $rp->calcularSemaforo($accion->indicador, true);
                $count++;
            }
        }
        $this->info("Recalculados exitosamente {$count} reportes de avance.");
        return 0;
    }
}
