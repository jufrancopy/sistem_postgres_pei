<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\ComplejidadTipo;

class VincularComplejidadEstablecimientos extends Command
{
    protected $signature   = 'riiss:vincular-complejidad {--dry-run : Solo muestra lo que haría sin guardar}';
    protected $description = 'Vincula todos los establecimientos con complejidad_tipo_id según nombre_legacy y recalcula campos derivados';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $tipos  = ComplejidadTipo::all()->keyBy('nombre_legacy');

        $this->info('Tipos de complejidad cargados: ' . $tipos->count());
        $tipos->each(fn($t) => $this->line("  Grado {$t->grado}: \"{$t->nombre_legacy}\" → \"{$t->nombre}\""));

        $establecimientos = Establecimiento::whereNull('deleted_at')->get();
        $this->info("\nTotal establecimientos: {$establecimientos->count()}");

        $vinculados   = 0;
        $sinMatch     = 0;
        $yaVinculados = 0;
        $sinComplejidad = 0;

        foreach ($establecimientos as $e) {
            if (empty($e->complejidad)) {
                $sinComplejidad++;
                continue;
            }

            $tipo = $tipos->get(trim($e->complejidad));

            if (!$tipo) {
                $this->warn("  Sin match: [{$e->codigo}] \"{$e->complejidad}\"");
                $sinMatch++;
                continue;
            }

            if ($e->complejidad_tipo_id === $tipo->id) {
                $yaVinculados++;
                continue;
            }

            if (!$dryRun) {
                $e->complejidad_tipo_id = $tipo->id;
                $e->saveQuietly();
                $e->recalcularCamposDerivados();
            }

            $vinculados++;
        }

        $this->newLine();
        $this->table(
            ['Estado', 'Cantidad'],
            [
                ['Vinculados' . ($dryRun ? ' (simulado)' : ''), $vinculados],
                ['Ya vinculados (sin cambio)',                   $yaVinculados],
                ['Sin match en complejidad_tipos',               $sinMatch],
                ['Sin campo complejidad',                        $sinComplejidad],
            ]
        );

        if ($dryRun) {
            $this->warn('Modo --dry-run: ningún cambio fue guardado.');
        } else {
            $this->info('Proceso completado.');
        }

        return self::SUCCESS;
    }
}
