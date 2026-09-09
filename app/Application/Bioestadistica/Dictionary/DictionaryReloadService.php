<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Application\Bioestadistica\Imports\EspecialidadesCatalogImporter;
use App\Application\Bioestadistica\Imports\VariablesSaludImporter;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\RecordValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

/**
 * Recarga el diccionario desde variables salud + códigos de Especialidades.xlsx.
 * Truncate de especialidades_medicas; upsert del resto; limpia captura.
 */
class DictionaryReloadService
{
    public function __construct(
        private VariablesSaludImporter $variablesImporter = new VariablesSaludImporter,
        private EspecialidadesCatalogImporter $especialidadesImporter = new EspecialidadesCatalogImporter,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function reload(
        string $variablesSaludPath,
        ?string $especialidadesPath = null,
        bool $purgeCaptura = true
    ): array {
        if (! is_file($variablesSaludPath)) {
            throw new RuntimeException("No se encontró variables salud: {$variablesSaludPath}");
        }

        $summary = [
            'captura' => null,
            'especialidades_truncate' => false,
            'bridges_especialidad_eliminados' => 0,
            'variables_salud' => null,
            'codigos_especialidades' => null,
        ];

        DB::connection('pgsql')->transaction(function () use (
            $variablesSaludPath,
            $especialidadesPath,
            $purgeCaptura,
            &$summary
        ): void {
            if ($purgeCaptura) {
                $summary['captura'] = $this->purgeCaptura();
            }

            $summary['bridges_especialidad_eliminados'] = DB::connection('pgsql')
                ->table('bioestadistica.detalle_catalogo_items')
                ->where('catalogo_tipo', CatalogType::EspecialidadMedica->value)
                ->delete();

            $this->truncateEspecialidades();
            $summary['especialidades_truncate'] = true;

            // Evitar unique conflicts por soft-deletes previos en otros puentes.
            DB::connection('pgsql')
                ->table('bioestadistica.detalle_catalogo_items')
                ->whereNotNull('deleted_at')
                ->delete();

            $summary['variables_salud'] = $this->variablesImporter->import($variablesSaludPath);

            if ($especialidadesPath && is_file($especialidadesPath)) {
                $summary['codigos_especialidades'] = $this->especialidadesImporter->applyCodigosOnly($especialidadesPath);
            }
        });

        return $summary;
    }

    /**
     * @return array{records: int, record_values: int, hosp_episodios: int, indicador_cache: int}
     */
    private function purgeCaptura(): array
    {
        $hosp = 0;
        if (Schema::connection('pgsql')->hasTable('bioestadistica.hosp_episodios')) {
            $hosp = HospEpisodio::query()->count();
            DB::connection('pgsql')->table('bioestadistica.hosp_episodios')->delete();
        }

        $values = RecordValue::query()->count();
        DB::connection('pgsql')->table('bioestadistica.record_values')->delete();

        $records = Record::withTrashed()->count();
        // hard delete including soft-deleted
        DB::connection('pgsql')->table('bioestadistica.records')->delete();

        $cache = 0;
        if (Schema::connection('pgsql')->hasTable('bioestadistica.indicador_cache')) {
            $cache = (int) DB::connection('pgsql')->table('bioestadistica.indicador_cache')->count();
            DB::connection('pgsql')->table('bioestadistica.indicador_cache')->delete();
        }

        return [
            'records' => $records,
            'record_values' => $values,
            'hosp_episodios' => $hosp,
            'indicador_cache' => $cache,
        ];
    }

    private function truncateEspecialidades(): void
    {
        // Self-FK: TRUNCATE ... CASCADE solo afecta esta tabla (FK interna).
        DB::connection('pgsql')->statement(
            'TRUNCATE TABLE bioestadistica.especialidades_medicas RESTART IDENTITY CASCADE'
        );
    }
}
