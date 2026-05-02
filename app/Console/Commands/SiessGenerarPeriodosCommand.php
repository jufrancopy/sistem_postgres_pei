<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Estadistica\SiessPeriodo;
use Carbon\Carbon;

/**
 * Genera períodos mensuales y anuales para el SIESS.
 * Uso: php artisan siess:generar-periodos
 * Uso con rango: php artisan siess:generar-periodos --desde=2020 --hasta=2026
 */
class SiessGenerarPeriodosCommand extends Command
{
    protected $signature = 'siess:generar-periodos
                            {--desde=2020 : Año de inicio}
                            {--hasta= : Año de fin (default: año actual)}';

    protected $description = 'Genera períodos mensuales y anuales para el SIESS (Res. 266/2022)';

    public function handle(): int
    {
        $desde = (int) $this->option('desde');
        $hasta = (int) ($this->option('hasta') ?? now()->year);

        if ($desde > $hasta) {
            $this->error("El año de inicio ($desde) no puede ser mayor al de fin ($hasta).");
            return self::FAILURE;
        }

        $this->info("Generando períodos del $desde al $hasta...");
        $creados = 0;
        $existentes = 0;

        $meses = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre',
        ];

        for ($anio = $desde; $anio <= $hasta; $anio++) {

            // ── Período anual ──────────────────────────────────────────────
            $existe = SiessPeriodo::where('anio', $anio)
                ->where('tipo', 'anual')
                ->whereNull('mes')
                ->exists();

            if (!$existe) {
                SiessPeriodo::create([
                    'anio'        => $anio,
                    'mes'         => null,
                    'tipo'        => 'anual',
                    'fecha_inicio'=> Carbon::create($anio, 1, 1)->startOfDay(),
                    'fecha_fin'   => Carbon::create($anio, 12, 31)->endOfDay(),
                    'activo'      => true,
                ]);
                $creados++;
            } else {
                $existentes++;
            }

            // ── Períodos mensuales ─────────────────────────────────────────
            for ($mes = 1; $mes <= 12; $mes++) {
                // No generar meses futuros
                if ($anio == now()->year && $mes > now()->month) {
                    break;
                }

                $existe = SiessPeriodo::where('anio', $anio)
                    ->where('mes', $mes)
                    ->where('tipo', 'mensual')
                    ->exists();

                if (!$existe) {
                    $inicio = Carbon::create($anio, $mes, 1)->startOfDay();
                    SiessPeriodo::create([
                        'anio'        => $anio,
                        'mes'         => $mes,
                        'tipo'        => 'mensual',
                        'fecha_inicio'=> $inicio,
                        'fecha_fin'   => $inicio->copy()->endOfMonth(),
                        'activo'      => true,
                    ]);
                    $creados++;
                } else {
                    $existentes++;
                }
            }

            $this->line("  ✓ $anio — 12 meses + 1 anual procesados");
        }

        $this->newLine();
        $this->info("✅ Completado: $creados períodos creados, $existentes ya existían.");
        $this->table(
            ['Tipo', 'Total en BD'],
            [
                ['Mensual', SiessPeriodo::where('tipo', 'mensual')->count()],
                ['Anual',   SiessPeriodo::where('tipo', 'anual')->count()],
            ]
        );

        return self::SUCCESS;
    }
}
