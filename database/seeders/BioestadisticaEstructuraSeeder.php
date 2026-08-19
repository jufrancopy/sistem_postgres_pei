<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoServicio;
use App\Models\Bioestadistica\EstructuraDepartamento;
use App\Models\Bioestadistica\EstructuraServicio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BioestadisticaEstructuraSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('.docs-bio/Salud consolidado - 2026.xlsx');
        if (! is_file($path)) {
            $this->command?->warn("No se encontró {$path}; se omite el maestro de departamentos y servicios.");
            return;
        }

        $spreadsheet = IOFactory::createReaderForFile($path)
            ->setReadDataOnly(true)
            ->load($path);
        $sheet = $spreadsheet->getSheetByName('SALUD 2026') ?? $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $rows = $sheet->rangeToArray('A1:M'.$highestRow, null, true, false);
        $spreadsheet->disconnectWorksheets();

        $header = array_map(fn ($value) => $this->key($value), $rows[0] ?? []);
        $deptCol = array_search('DEPARTAMENTO', $header, true);
        $servCol = array_search('SERVICIO', $header, true);
        $estCol = array_search('ESTABLECIMIENTO', $header, true);

        if ($deptCol === false || $servCol === false) {
            $this->command?->warn('No se encontraron columnas DEPARTAMENTO y SERVICIO en el consolidado.');
            return;
        }

        app(AuditService::class)->withoutAuditing(function () use ($rows, $deptCol, $servCol, $estCol): void {
            $seenPairs = [];
            $seenTriples = [];
            $departamentos = 0;
            $servicios = 0;
            $asignaciones = 0;
            $cache = [];
            $establecimientos = Establecimiento::query()
                ->get(['id', 'nombre'])
                ->keyBy(fn (Establecimiento $item) => Str::upper($item->nombre));

            foreach (array_slice($rows, 1) as $row) {
                $departamentoNombre = $this->clean($row[$deptCol] ?? null);
                $servicioNombre = $this->clean($row[$servCol] ?? null);
                if (! $departamentoNombre || ! $servicioNombre) {
                    continue;
                }

                $pairKey = Str::upper("{$departamentoNombre}|{$servicioNombre}");
                if (! isset($seenPairs[$pairKey])) {
                    $seenPairs[$pairKey] = true;

                    if (! isset($cache[$departamentoNombre])) {
                        $departamento = EstructuraDepartamento::withTrashed()->firstOrNew(['nombre' => $departamentoNombre]);
                        if ($departamento->exists && $departamento->trashed()) {
                            $departamento->restore();
                        }
                        if (! $departamento->exists) {
                            $departamentos++;
                        }
                        $departamento->fill(['activo' => true])->save();
                        $cache[$departamentoNombre] = $departamento;
                    }

                    $departamento = $cache[$departamentoNombre];
                    $servicio = EstructuraServicio::withTrashed()->firstOrNew([
                        'departamento_id' => $departamento->id,
                        'nombre' => $servicioNombre,
                    ]);
                    if ($servicio->exists && $servicio->trashed()) {
                        $servicio->restore();
                    }
                    if (! $servicio->exists) {
                        $servicios++;
                    }
                    $servicio->fill(['activo' => true])->save();
                    $cache[$pairKey] = $servicio;
                }

                if ($estCol === false) {
                    continue;
                }

                $establecimientoNombre = $this->clean($row[$estCol] ?? null);
                if (! $establecimientoNombre) {
                    continue;
                }

                $establecimiento = $establecimientos->get(Str::upper($establecimientoNombre));
                if (! $establecimiento) {
                    continue;
                }

                $servicio = $cache[$pairKey] ?? null;
                if (! $servicio) {
                    continue;
                }

                $tripleKey = "{$establecimiento->id}|{$servicio->departamento_id}|{$servicio->id}";
                if (isset($seenTriples[$tripleKey])) {
                    continue;
                }
                $seenTriples[$tripleKey] = true;

                $unidad = EstablecimientoServicio::withTrashed()->firstOrNew([
                    'establecimiento_id' => $establecimiento->id,
                    'departamento_id' => $servicio->departamento_id,
                    'servicio_id' => $servicio->id,
                ]);
                if ($unidad->exists && $unidad->trashed()) {
                    $unidad->restore();
                }
                if (! $unidad->exists) {
                    $asignaciones++;
                }
                $unidad->save();
            }

            $this->command?->info(
                "Estructura IPS: {$departamentos} departamentos, {$servicios} servicios y {$asignaciones} asociaciones establecimiento-servicio nuevas."
            );
        });
    }

    private function clean(mixed $value): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function key(mixed $value): string
    {
        return trim(
            preg_replace('/[^A-Z0-9]+/', '_', Str::upper(Str::ascii((string) $value))),
            '_'
        );
    }
}
