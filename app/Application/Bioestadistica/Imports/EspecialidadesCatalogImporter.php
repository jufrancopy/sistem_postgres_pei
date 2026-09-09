<?php

namespace App\Application\Bioestadistica\Imports;

use App\Models\Bioestadistica\EspecialidadMedica;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use RuntimeException;
use Throwable;

class EspecialidadesCatalogImporter
{
    /**
     * Alias planilla Especialidades.xlsx (texto corto) → nombre canónico en BD / variables salud.
     *
     * @var array<string, string>
     */
    private const CODIGO_NOMBRE_ALIASES = [
        'urgencias cir. reconst. y quemado' => 'URGENCIAS CIRUGIA RECONSTRUCTIVA Y QUEMADOS',
        'urgencias cir. vascular' => 'URGENCIAS CIRUGIA VASCULAR',
        'urgencias otorrrino' => 'URGENCIAS OTORRINOLARINGOLOGIA',
        'urgencias ginecologicas' => 'URGENCIAS GINECOLOGIA',
    ];

    /**
     * @return array{
     *     archivo: string,
     *     procesados: int,
     *     creados: int,
     *     actualizados: int,
     *     omitidos_obs: int,
     *     multi_contexto: array<int, string>,
     *     creados_detalle: array<int, string>,
     *     actualizados_detalle: array<int, string>,
     *     advertencias: array<int, string>
     * }
     */
    public function import(string $filePath, bool $dryRun = false): array
    {
        $spreadsheet = null;
        $summary = [
            'archivo' => basename($filePath),
            'procesados' => 0,
            'creados' => 0,
            'actualizados' => 0,
            'omitidos_obs' => 0,
            'multi_contexto' => [],
            'creados_detalle' => [],
            'actualizados_detalle' => [],
            'advertencias' => [],
        ];

        try {
            $spreadsheet = $this->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $this->assertHeaders($sheet);

            $existing = EspecialidadMedica::withTrashed()->get();
            $byNorm = $existing->groupBy(
                fn (EspecialidadMedica $e) => $this->normalize((string) ($e->nombre_normalizado ?: $e->nombre))
            );

            for ($row = 2; $row <= $sheet->getHighestDataRow(); $row++) {
                $codigo = $this->clean($sheet->getCell([1, $row])->getFormattedValue());
                $nombre = $this->clean($sheet->getCell([2, $row])->getFormattedValue());
                $obs = $this->clean($sheet->getCell([3, $row])->getFormattedValue());

                if ($nombre === null) {
                    continue;
                }

                if ($obs !== null) {
                    $summary['omitidos_obs']++;
                    continue;
                }

                if ($codigo === null) {
                    $summary['advertencias'][] = "Fila {$row}: especialidad «{$nombre}» sin código; omitida.";
                    continue;
                }

                $summary['procesados']++;
                $key = $this->normalize($nombre);
                /** @var Collection<int, EspecialidadMedica> $matches */
                $matches = $byNorm->get($key, collect());

                if ($matches->isEmpty()) {
                    if (! $dryRun) {
                        $created = $this->createAmbulatorio($codigo, $nombre, $row);
                        $byNorm[$key] = collect([$created]);
                    }
                    $summary['creados']++;
                    $summary['creados_detalle'][] = "{$codigo}|{$nombre}";
                    continue;
                }

                if ($matches->count() > 1) {
                    $summary['multi_contexto'][] = sprintf(
                        '%s (%s) → %s',
                        $nombre,
                        $codigo,
                        $matches->map(fn (EspecialidadMedica $m) => "#{$m->id}")->implode(', ')
                    );
                }

                foreach ($matches as $match) {
                    if (! $dryRun) {
                        $this->updateMatch($match, $codigo, $nombre);
                    }
                    $summary['actualizados']++;
                    $summary['actualizados_detalle'][] = "#{$match->id} ← {$codigo}|{$nombre}";
                }
            }

            return $summary;
        } catch (Throwable $exception) {
            throw new RuntimeException(
                "No se pudo importar especialidades desde '{$filePath}': {$exception->getMessage()}",
                0,
                $exception
            );
        } finally {
            if ($spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }

    /**
     * Solo asigna códigos a especialidades ya existentes (tras reload de variables salud).
     * No crea filas nuevas desde Especialidades.xlsx.
     *
     * @return array{
     *     archivo: string,
     *     procesados: int,
     *     actualizados: int,
     *     omitidos_obs: int,
     *     sin_match_planilla: array<int, string>,
     *     multi_contexto: array<int, string>,
     *     sin_codigo_en_bd: array<int, string>
     * }
     */
    public function applyCodigosOnly(string $filePath): array
    {
        $spreadsheet = null;
        $summary = [
            'archivo' => basename($filePath),
            'procesados' => 0,
            'actualizados' => 0,
            'omitidos_obs' => 0,
            'sin_match_planilla' => [],
            'multi_contexto' => [],
            'sin_codigo_en_bd' => [],
        ];

        try {
            $spreadsheet = $this->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $this->assertHeaders($sheet);

            $existing = EspecialidadMedica::query()->get();
            $byNorm = $existing->groupBy(
                fn (EspecialidadMedica $e) => $this->normalize((string) ($e->nombre_normalizado ?: $e->nombre))
            );

            for ($row = 2; $row <= $sheet->getHighestDataRow(); $row++) {
                $codigo = $this->clean($sheet->getCell([1, $row])->getFormattedValue());
                $nombre = $this->clean($sheet->getCell([2, $row])->getFormattedValue());
                $obs = $this->clean($sheet->getCell([3, $row])->getFormattedValue());

                if ($nombre === null) {
                    continue;
                }
                if ($obs !== null) {
                    $summary['omitidos_obs']++;
                    continue;
                }
                if ($codigo === null) {
                    continue;
                }

                $summary['procesados']++;
                $nombreCanonico = $this->canonicalNombre($nombre);
                $key = $this->normalize($nombreCanonico);
                /** @var Collection<int, EspecialidadMedica> $matches */
                $matches = $byNorm->get($key, collect());

                if ($matches->isEmpty()) {
                    // También buscar por el nombre crudo de planilla por si ya está normalizado igual.
                    $matches = $byNorm->get($this->normalize($nombre), collect());
                }

                if ($matches->isEmpty()) {
                    $summary['sin_match_planilla'][] = "{$codigo}|{$nombre}"
                        .($nombreCanonico !== $nombre ? " → {$nombreCanonico}" : '');
                    continue;
                }

                if ($matches->count() > 1) {
                    $summary['multi_contexto'][] = sprintf(
                        '%s (%s) → %s',
                        $nombreCanonico,
                        $codigo,
                        $matches->map(fn (EspecialidadMedica $m) => "#{$m->id}")->implode(', ')
                    );
                }

                foreach ($matches as $match) {
                    $this->updateMatch($match, $codigo, $nombreCanonico);
                    $summary['actualizados']++;
                }
            }

            $summary['sin_codigo_en_bd'] = EspecialidadMedica::query()
                ->whereNull('codigo')
                ->orderBy('nombre')
                ->get()
                ->map(fn (EspecialidadMedica $e) => "#{$e->id} {$e->nombre}")
                ->all();

            return $summary;
        } catch (Throwable $exception) {
            throw new RuntimeException(
                "No se pudieron aplicar códigos de especialidades desde '{$filePath}': {$exception->getMessage()}",
                0,
                $exception
            );
        } finally {
            if ($spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }

    private function createAmbulatorio(string $codigo, string $nombre, int $orden): EspecialidadMedica
    {
        $item = new EspecialidadMedica([
            'codigo' => $codigo,
            'nombre' => $nombre,
            'nombre_normalizado' => $this->normalize($nombre),
            'especialidad_base' => $nombre,
            'orden' => is_numeric($codigo) ? (int) $codigo : $orden,
            'activo' => true,
        ]);
        $item->save();

        return $item;
    }

    private function updateMatch(EspecialidadMedica $match, string $codigo, string $nombre): void
    {
        if ($match->trashed()) {
            $match->restore();
        }

        // Conservar el nombre canónico de BD; solo rellenar código/orden.
        $match->fill([
            'codigo' => $codigo,
            'nombre_normalizado' => $this->normalize($match->nombre ?: $nombre),
            'orden' => is_numeric($codigo) ? (int) $codigo : $match->orden,
            'activo' => true,
        ]);

        if ($match->especialidad_base === null || $match->especialidad_base === '') {
            $match->especialidad_base = $match->nombre;
        }

        $match->save();
    }

    /**
     * Resuelve abreviaturas de la planilla de códigos al nombre canónico de variables/BD.
     */
    private function canonicalNombre(string $nombrePlanilla): string
    {
        $key = $this->normalize($nombrePlanilla);

        return self::CODIGO_NOMBRE_ALIASES[$key] ?? $nombrePlanilla;
    }

    private function assertHeaders($sheet): void
    {
        $a = $this->normalize((string) $sheet->getCell([1, 1])->getFormattedValue());
        $b = $this->normalize((string) $sheet->getCell([2, 1])->getFormattedValue());
        $c = $this->normalize((string) $sheet->getCell([3, 1])->getFormattedValue());

        if ($a !== 'codigo' || ! str_contains($b, 'especialidad') || $c !== 'obs') {
            throw new RuntimeException('Encabezados esperados: codigo | especialidad | obs');
        }
    }

    private function load(string $path): Spreadsheet
    {
        return IOFactory::createReaderForFile($path)
            ->setReadDataOnly(true)
            ->load($path);
    }

    private function clean(mixed $value): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function normalize(string $value): string
    {
        return Str::lower(Str::ascii(trim($value)));
    }
}
