<?php

namespace App\Application\Bioestadistica\Sync;

use App\Application\Bioestadistica\Dictionary\Sp12ProgramasDictionarySync;
use App\Application\Bioestadistica\Dictionary\Sp13ProgramasDictionarySync;
use App\Application\Bioestadistica\Dictionary\Sp2EnfermeriaPlanillaDictionarySync;
use App\Application\Bioestadistica\Dictionary\Sp7ProcedimientosDictionarySync;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\EstablecimientoOrgano;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Organo;
use App\Models\Bioestadistica\OrganoTipo;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\Reporte;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Database\Seeders\BioestadisticaDistritosSeeder;
use Database\Seeders\BioestadisticaEspecialidadesCatalogSeeder;
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
use Database\Seeders\BioestadisticaSp8VacunacionInteriorDictionarySeeder;
use Database\Seeders\BioestadisticaSp9Seeder;
use Database\Seeders\BioestadisticaVariablesSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Sincroniza catálogos de Configuraciones (sidebar) desde los seeders canónicos.
 *
 * - Igual en origen/destino → no cambia (upsert idempotente).
 * - Falta o difiere → crea/actualiza.
 * - En destino y no en origen → soft-delete si es seguro (sin tocar record_values / cargas SP).
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

    /**
     * Variables dominio «x» conservadas (alineado a desarrollo / SP14).
     * El Excel no importa dominio x; estas se mantienen si ya existen.
     *
     * @var list<string>
     */
    public const KEEP_X_VARIABLE_NAMES = [
        'ENFERMERIA',
        'GESTION HOSPITALARIA',
        'MEDICAMENTOS',
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
        app()->instance(CatalogSyncRegistry::class, $this->registry);

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
            'variables' => $this->upsertVariables(),
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

    private function upsertVariables(): string
    {
        $this->runSeeder(BioestadisticaVariablesSeeder::class, 'Excel variables salud');
        $this->runSeeder(BioestadisticaEspecialidadesCatalogSeeder::class, 'Especialidades.xlsx');

        $sp2 = app(Sp2EnfermeriaPlanillaDictionarySync::class);
        $sp2->apply(forceSnapshot: ! $sp2->snapshotExists());

        $sp13 = app(Sp13ProgramasDictionarySync::class);
        $sp13->apply(forceSnapshot: ! $sp13->snapshotExists());

        $sp12 = app(Sp12ProgramasDictionarySync::class);
        $sp12->apply(forceSnapshot: ! $sp12->snapshotExists());

        $sp7 = app(Sp7ProcedimientosDictionarySync::class);
        $sp7->apply(forceSnapshot: ! $sp7->snapshotExists());

        // Tipos de hospitalización (también en Formularios / SP10), sin depender de --only=formularios.
        $this->seedHospitalizacionDictionary();

        $this->preserveKeepXVariables();

        return 'Excel + Especialidades + Formularios SP (Sp2/13/12/7) + hosp (sin tocar cargas)';
    }

    private function seedHospitalizacionDictionary(): void
    {
        $dictionary = app(\App\Application\Bioestadistica\Dictionary\HealthVariableDictionary::class);
        foreach (\App\Models\Bioestadistica\HospEpisodio::SERVICIOS as $label) {
            $dictionary->remember('2', 'HOSPITALIZACIÓN', 'SERVICIOS HOSPITALARIOS', $label);
        }
        foreach (['Masculino', 'Femenino'] as $label) {
            $dictionary->remember('2', 'HOSPITALIZACIÓN', 'SEXO', $label);
        }
    }

    private function preserveKeepXVariables(): void
    {
        foreach (self::KEEP_X_VARIABLE_NAMES as $nombre) {
            $variable = Variable::query()
                ->where('codigo', 'x')
                ->whereRaw('upper(trim(nombre)) = ?', [Str::upper(trim($nombre))])
                ->first();
            if (! $variable) {
                continue;
            }
            if (! $variable->activo) {
                $variable->update(['activo' => true]);
            }
            $this->registry->rememberVariable((int) $variable->id);
            foreach ($variable->detalles()->pluck('id') as $detalleId) {
                $this->registry->rememberDetalle((int) $detalleId);
            }
        }
    }

    private function upsertFormularios(): string
    {
        $this->runSeeder(BioestadisticaFormulariosSeeder::class, 'Shell SP');

        $snapshotPath = \App\Application\Bioestadistica\Sync\FormSnapshotService::seedDataPath();
        if (! is_file($snapshotPath)) {
            $snapshotPath = \App\Application\Bioestadistica\Sync\FormSnapshotService::defaultPath();
        }

        if (is_file($snapshotPath)) {
            $report = app(\App\Application\Bioestadistica\Sync\FormSnapshotService::class)
                ->importFromFile($snapshotPath, pruneExtraFields: true);

            return 'Formularios desde snapshot ('
                .$report['formularios'].' forms / '
                .$report['fields'].' fields'
                .($report['skipped'] !== [] ? '; avisos: '.count($report['skipped']) : '')
                .')';
        }

        $this->runSeeders([
            BioestadisticaSp1Seeder::class,
            BioestadisticaSp2Seeder::class,
            BioestadisticaSp3Seeder::class,
            BioestadisticaSp4Seeder::class,
            BioestadisticaSp5Seeder::class,
            BioestadisticaSp6Seeder::class,
            BioestadisticaSp7Seeder::class,
            BioestadisticaSp8VacunacionInteriorDictionarySeeder::class,
            BioestadisticaSp9Seeder::class,
            BioestadisticaHospitalizacionSeeder::class,
            BioestadisticaSp12Seeder::class,
            BioestadisticaSp13Seeder::class,
            BioestadisticaSp14Seeder::class,
            BioestadisticaFormulariosSpSeeder::class,
        ], 'Estructura SP1–SP14 (sin snapshot)');

        return 'Formularios SP1–SP14 (shell + seeders; sin forms-snapshot.json)';
    }

    /**
     * @param  class-string  $seeder
     */
    private function runSeeder(string $seeder, string $label): string
    {
        app($seeder)->run();

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
            'variables' => $this->pruneVariables(),
            default => [[], []],
        };
    }

    /**
     * Soft-delete de variables dominio «x» fuera de la lista canónica.
     * No poda tipologías de dominios 1–18 (pueden quedar en diccionario aunque un SP no las use).
     * Nunca borra record_values; si un detalle está enlazado a un field, solo desactiva.
     *
     * @return array{0: list<string>, 1: list<string>}
     */
    private function pruneVariables(): array
    {
        $pruned = [];
        $skipped = [];

        $keepNames = array_map(
            fn (string $n) => Str::upper(trim($n)),
            self::KEEP_X_VARIABLE_NAMES
        );

        $orphansX = Variable::query()
            ->where('codigo', 'x')
            ->get()
            ->filter(function (Variable $variable) use ($keepNames) {
                return ! in_array(Str::upper(trim((string) $variable->nombre)), $keepNames, true);
            });

        foreach ($orphansX as $variable) {
            $label = $variable->codigo.' — '.$variable->nombre.' #'.$variable->id;

            foreach ($variable->detalles as $detalle) {
                $detalleLabel = $label.' / '.$detalle->nombre.' #'.$detalle->id;
                if (Field::withTrashed()->where('detalle_id', $detalle->id)->exists()) {
                    if ($detalle->activo) {
                        $detalle->update(['activo' => false]);
                    }
                    $skipped[] = "detalle x en uso por formulario (desactivado): {$detalleLabel}";
                    continue;
                }

                DetalleCatalogoItem::query()
                    ->where('variable_detalle_id', $detalle->id)
                    ->get()
                    ->each->delete();
                $detalle->delete();
                $pruned[] = "detalle x: {$detalleLabel}";
            }

            $variable->refresh();
            if ($variable->detalles()->count() > 0) {
                if ($variable->activo) {
                    $variable->update(['activo' => false]);
                }
                $skipped[] = "variable x con detalles retenidos (desactivada): {$label}";
                continue;
            }

            $variable->delete();
            $pruned[] = "variable x: {$label}";
        }

        return [$pruned, $skipped];
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

        foreach (Organo::query()->whereNotIn('id', $keep)->get() as $organo) {
            $label = trim(($organo->codigo ? $organo->codigo.' · ' : '').$organo->nombre).' #'.$organo->id;
            if ($organo->activo) {
                $organo->update(['activo' => false]);
                $skipped[] = "órgano no podable (tiene vínculos/hijos): {$label} (desactivado)";
            }
        }

        if ($keepTipos !== []) {
            foreach (OrganoTipo::query()->whereNotIn('codigo', $keepTipos)->get() as $tipo) {
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
        $keep = $this->registry->indicadores();
        if ($keep === []) {
            return [[], ['indicadores: registry vacío — se omite poda']];
        }

        foreach (Indicador::query()->whereNotIn('codigo', $keep)->get() as $item) {
            $item->delete();
            $pruned[] = "indicador: {$item->codigo}";
        }

        return [$pruned, []];
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
            $keep = array_map(fn (int $n) => 'SP'.$n, range(1, 14));
        }

        foreach (Formulario::query()->whereNotIn('codigo', $keep)->get() as $form) {
            if (Record::query()->where('formulario_id', $form->id)->exists()) {
                $skipped[] = "formulario con cargas: {$form->codigo}";
                continue;
            }
            $form->delete();
            $pruned[] = "formulario: {$form->codigo}";
        }

        return [$pruned, $skipped];
    }
}
