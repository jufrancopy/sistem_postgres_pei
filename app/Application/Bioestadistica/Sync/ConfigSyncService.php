<?php

namespace App\Application\Bioestadistica\Sync;

use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\EstablecimientoOrgano;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Organo;
use App\Models\Bioestadistica\OrganoTipo;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\Reporte;
use Database\Seeders\BioestadisticaDistritosSeeder;
use Database\Seeders\BioestadisticaEstablecimientosSeeder;
use Database\Seeders\BioestadisticaFormulariosSeeder;
use Database\Seeders\BioestadisticaFormulariosSpSeeder;
use Database\Seeders\BioestadisticaHospitalizacionSeeder;
use Database\Seeders\BioestadisticaIndicadoresSeeder;
use Database\Seeders\BioestadisticaOrganosSeeder;
use Database\Seeders\BioestadisticaReportesDashboardsSeeder;
use Database\Seeders\BioestadisticaRolesSeeder;
use Database\Seeders\BioestadisticaSp12Seeder;
use Database\Seeders\BioestadisticaSp13Seeder;
use Database\Seeders\BioestadisticaSp14Seeder;
use Database\Seeders\BioestadisticaSp1Seeder;
use Database\Seeders\BioestadisticaSp2Seeder;
use Database\Seeders\BioestadisticaSp3Seeder;
use Database\Seeders\BioestadisticaSp4Seeder;
use Database\Seeders\BioestadisticaSp5Seeder;
use Database\Seeders\BioestadisticaSp6Seeder;
use Database\Seeders\BioestadisticaSp7Seeder;
use Database\Seeders\BioestadisticaSp8Seeder;
use Database\Seeders\BioestadisticaSp9Seeder;
use Database\Seeders\BioestadisticaVariablesSeeder;
use Illuminate\Support\Facades\DB;

/**
 * Sincroniza catálogos de Configuraciones (sidebar) desde los seeders canónicos.
 *
 * - Igual en origen/destino → no cambia (upsert idempotente).
 * - Falta o difiere → crea/actualiza.
 * - En destino y no en origen → soft-delete si es seguro (sin vínculos operativos).
 */
class ConfigSyncService
{
    public const MODULES = [
        'roles',
        'geografia',
        'variables',
        'formularios',
        'indicadores',
        'dashboards',
        'organos',
    ];

    public function __construct(
        private CatalogSyncRegistry $registry
    ) {}

    /**
     * @param  list<string>  $modules
     * @return array{
     *     dry_run: bool,
     *     prune: bool,
     *     modules: list<string>,
     *     upserted: array<string, string>,
     *     pruned: array<string, list<string>>,
     *     skipped: array<string, list<string>>
     * }
     */
    public function sync(array $modules, bool $dryRun = false, bool $prune = true): array
    {
        $modules = array_values(array_intersect(self::MODULES, $modules !== [] ? $modules : self::MODULES));
        $this->registry->reset();

        $report = [
            'dry_run' => $dryRun,
            'prune' => $prune,
            'modules' => $modules,
            'upserted' => [],
            'pruned' => [],
            'skipped' => [],
        ];

        DB::connection('pgsql')->beginTransaction();
        try {
            foreach ($modules as $module) {
                $report['upserted'][$module] = $this->upsertModule($module);
            }

            if ($prune) {
                foreach ($modules as $module) {
                    [$pruned, $skipped] = $this->pruneModule($module);
                    if ($pruned !== []) {
                        $report['pruned'][$module] = $pruned;
                    }
                    if ($skipped !== []) {
                        $report['skipped'][$module] = $skipped;
                    }
                }
            }

            if ($dryRun) {
                DB::connection('pgsql')->rollBack();
            } else {
                DB::connection('pgsql')->commit();
            }
        } catch (\Throwable $e) {
            DB::connection('pgsql')->rollBack();
            throw $e;
        }

        return $report;
    }

    private function upsertModule(string $module): string
    {
        return match ($module) {
            'roles' => $this->runSeeder(BioestadisticaRolesSeeder::class, 'Roles y permisos bio.*'),
            'geografia' => $this->runSeeders([
                BioestadisticaDistritosSeeder::class,
                BioestadisticaEstablecimientosSeeder::class,
            ], 'Distritos + establecimientos + clasificaciones (upsert, sin borrar)'),
            'variables' => $this->runSeeder(BioestadisticaVariablesSeeder::class, 'Diccionario de variables'),
            'formularios' => $this->upsertFormularios(),
            'indicadores' => $this->runSeeders([
                BioestadisticaIndicadoresSeeder::class,
                BioestadisticaHospitalizacionSeeder::class,
            ], 'Indicadores canónicos (+ hosp SP10/SP11)'),
            'dashboards' => $this->runSeeder(BioestadisticaReportesDashboardsSeeder::class, 'Reportes y dashboards institucionales'),
            'organos' => $this->runSeeder(BioestadisticaOrganosSeeder::class, 'Organigrama (tipos + nodos)'),
            default => 'módulo desconocido',
        };
    }

    private function upsertFormularios(): string
    {
        $this->runSeeder(BioestadisticaFormulariosSeeder::class, 'Shell SP');
        $this->runSeeders([
            BioestadisticaSp1Seeder::class,
            BioestadisticaSp2Seeder::class,
            BioestadisticaSp3Seeder::class,
            BioestadisticaSp4Seeder::class,
            BioestadisticaSp5Seeder::class,
            BioestadisticaSp6Seeder::class,
            BioestadisticaSp7Seeder::class,
            BioestadisticaSp8Seeder::class,
            BioestadisticaSp9Seeder::class,
            BioestadisticaHospitalizacionSeeder::class,
            BioestadisticaSp12Seeder::class,
            BioestadisticaSp13Seeder::class,
            BioestadisticaSp14Seeder::class,
            BioestadisticaFormulariosSpSeeder::class,
        ], 'Estructura SP1–SP14');

        // HospitalizacionSeeder también crea indicadores SP10/SP11: ya quedan en registry vía AnalyticsSupport.
        return 'Formularios SP1–SP14 (shell + estructura)';
    }

    /**
     * @param  class-string  $seeder
     */
    private function runSeeder(string $seeder, string $label): string
    {
        $instance = app($seeder);
        $instance->run();

        return $label;
    }

    /**
     * @param  list<class-string>  $seeders
     */
    private function runSeeders(array $seeders, string $label): string
    {
        foreach ($seeders as $seeder) {
            app($seeder)->run();
        }

        return $label;
    }

    /**
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneModule(string $module): array
    {
        return match ($module) {
            'organos' => $this->pruneOrganos(),
            'indicadores' => $this->pruneIndicadores(),
            'dashboards' => $this->pruneDashboardsAndReportes(),
            'formularios' => $this->pruneFormularios(),
            // geografia/variables/roles: no prune destructivo
            default => [[], []],
        };
    }

    /**
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneOrganos(): array
    {
        $pruned = [];
        $skipped = [];
        $keep = $this->registry->organos();
        $keepTipos = $this->registry->organoTipos();

        if ($keep === []) {
            return [[], ['organos: registry vacío — se omite poda']];
        }

        // Podar hojas primero (repetir hasta estabilizar).
        $guard = 0;
        do {
            $deletedThisPass = 0;
            $orphans = Organo::query()
                ->whereNotIn('id', $keep)
                ->whereDoesntHave('children')
                ->orderByDesc('id')
                ->get();

            foreach ($orphans as $organo) {
                $label = trim(($organo->codigo ? $organo->codigo.' · ' : '').$organo->nombre).' #'.$organo->id;
                if ($this->organoIsLinked($organo)) {
                    $skipped[] = "órgano vinculado: {$label}";
                    if ($organo->activo) {
                        $organo->update(['activo' => false]);
                        $skipped[array_key_last($skipped)] .= ' (desactivado)';
                    }
                    continue;
                }
                $organo->delete();
                $pruned[] = "órgano: {$label}";
                $deletedThisPass++;
            }
            $guard++;
        } while ($deletedThisPass > 0 && $guard < 50);

        // Padres huérfanos que aún tienen hijos fuera de keep (o vinculados): desactivar.
        foreach (Organo::query()->whereNotIn('id', $keep)->get() as $organo) {
            $label = trim(($organo->codigo ? $organo->codigo.' · ' : '').$organo->nombre).' #'.$organo->id;
            if ($organo->activo) {
                $organo->update(['activo' => false]);
                $skipped[] = "órgano no podable (tiene vínculos/hijos): {$label} (desactivado)";
            }
        }

        if ($keepTipos !== []) {
            $tipoOrphans = OrganoTipo::query()->whereNotIn('codigo', $keepTipos)->get();
            foreach ($tipoOrphans as $tipo) {
                if ($tipo->organos()->withTrashed()->exists()) {
                    $skipped[] = "tipo órgano en uso: {$tipo->codigo}";
                    continue;
                }
                $tipo->delete();
                $pruned[] = "tipo órgano: {$tipo->codigo}";
            }
        }

        return [$pruned, $skipped];
    }

    private function organoIsLinked(Organo $organo): bool
    {
        if (EstablecimientoOrgano::query()->where('organo_id', $organo->id)->exists()) {
            return true;
        }

        return Record::query()->where('organo_id', $organo->id)->exists();
    }

    /**
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneIndicadores(): array
    {
        $pruned = [];
        $skipped = [];
        $keep = $this->registry->indicadores();
        if ($keep === []) {
            return [[], ['indicadores: registry vacío — se omite poda']];
        }

        foreach (Indicador::query()->whereNotIn('codigo', $keep)->get() as $item) {
            $item->delete();
            $pruned[] = "indicador: {$item->codigo}";
        }

        return [$pruned, $skipped];
    }

    /**
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneDashboardsAndReportes(): array
    {
        $pruned = [];
        $skipped = [];

        $keepReports = $this->registry->reportes();
        if ($keepReports !== []) {
            foreach (Reporte::query()->whereNotIn('codigo', $keepReports)->get() as $item) {
                $item->delete();
                $pruned[] = "reporte: {$item->codigo}";
            }
        } else {
            $skipped[] = 'reportes: registry vacío — se omite poda';
        }

        $keepDash = $this->registry->dashboards();
        if ($keepDash !== []) {
            foreach (
                Dashboard::query()
                    ->whereNull('user_id')
                    ->whereNotIn('codigo', $keepDash)
                    ->get() as $item
            ) {
                $item->widgets()->delete();
                $item->delete();
                $pruned[] = "dashboard: {$item->codigo}";
            }
        } else {
            $skipped[] = 'dashboards: registry vacío — se omite poda';
        }

        return [$pruned, $skipped];
    }

    /**
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneFormularios(): array
    {
        $pruned = [];
        $skipped = [];
        $keep = $this->registry->formularios();
        if ($keep === []) {
            // fallback canónico SP1–SP14
            $keep = array_map(fn (int $n) => 'SP'.$n, range(1, 14));
        }

        foreach (Formulario::query()->whereNotIn('codigo', $keep)->get() as $form) {
            if (Record::query()->where('formulario_id', $form->id)->exists()) {
                $skipped[] = "formulario con cargas: {$form->codigo}";
                if ($form->estado !== 'inactivo' && $form->estado !== 'archivado') {
                    // no forzar estado desconocido; dejar
                }
                continue;
            }
            $form->delete();
            $pruned[] = "formulario: {$form->codigo}";
        }

        return [$pruned, $skipped];
    }
}
