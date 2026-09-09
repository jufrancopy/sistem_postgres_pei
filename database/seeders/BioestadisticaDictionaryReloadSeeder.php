<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryReloadService;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\DeterminacionEstudio;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Procedimiento;
use App\Models\Bioestadistica\Vacuna;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;

class BioestadisticaDictionaryReloadSeeder extends Seeder
{
    public function run(): void
    {
        $varsPath = base_path('.docs-bio/variables salud.xlsx');
        if (! is_file($varsPath)) {
            $varsPath = base_path('.docs-bio/variables salud.xls');
        }
        $espPath = base_path('.docs-bio/Especialidades.xlsx');

        if (! is_file($varsPath)) {
            $this->command?->error('No se encontró variables salud.xlsx/.xls en .docs-bio');

            return;
        }

        $summary = (new DictionaryReloadService)->reload(
            $varsPath,
            is_file($espPath) ? $espPath : null,
            true
        );

        $cap = $summary['captura'] ?? [];
        $this->command?->info(sprintf(
            'Captura borrada: records=%d values=%d hosp=%d cache=%d',
            $cap['records'] ?? 0,
            $cap['record_values'] ?? 0,
            $cap['hosp_episodios'] ?? 0,
            $cap['indicador_cache'] ?? 0
        ));
        $this->command?->info('especialidades_medicas: TRUNCATE OK; bridges especialidad eliminados='.($summary['bridges_especialidad_eliminados'] ?? 0));

        $vs = $summary['variables_salud'] ?? [];
        $this->command?->info(sprintf(
            'Variables salud: procesados=%d (vars+%d detalles+%d items+%d). Omitidos x=%d amarillas=%d dup=%d',
            $vs['procesados'] ?? 0,
            $vs['creados']['variables'] ?? 0,
            $vs['creados']['variable_detalles'] ?? 0,
            $vs['creados']['prestaciones'] ?? 0,
            $vs['omitidos']['dominio_x'] ?? 0,
            $vs['omitidos']['filas_amarillas'] ?? 0,
            $vs['omitidos']['duplicados'] ?? 0
        ));

        $cod = $summary['codigos_especialidades'] ?? null;
        if (is_array($cod)) {
            $this->command?->info(sprintf(
                'Códigos Especialidades.xlsx: procesados=%d actualizados=%d omitidos_obs=%d sin_match_planilla=%d sin_codigo_bd=%d multi_ctx=%d',
                $cod['procesados'] ?? 0,
                $cod['actualizados'] ?? 0,
                $cod['omitidos_obs'] ?? 0,
                count($cod['sin_match_planilla'] ?? []),
                count($cod['sin_codigo_en_bd'] ?? []),
                count($cod['multi_contexto'] ?? [])
            ));
            foreach (array_slice($cod['sin_match_planilla'] ?? [], 0, 20) as $line) {
                $this->command?->warn('  planilla sin match BD: '.$line);
            }
            foreach (array_slice($cod['sin_codigo_en_bd'] ?? [], 0, 30) as $line) {
                $this->command?->warn('  BD sin codigo: '.$line);
            }
        }

        $this->command?->info(sprintf(
            'Estado final: variables=%d detalles=%d bridges=%d especialidades=%d (con_codigo=%d) det=%d proc=%d vac=%d prest=%d',
            Variable::count(),
            VariableDetalle::count(),
            DetalleCatalogoItem::count(),
            EspecialidadMedica::count(),
            EspecialidadMedica::whereNotNull('codigo')->count(),
            DeterminacionEstudio::count(),
            Procedimiento::count(),
            Vacuna::count(),
            Prestacion::count()
        ));
    }
}
