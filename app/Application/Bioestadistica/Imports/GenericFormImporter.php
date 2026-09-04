<?php

namespace App\Application\Bioestadistica\Imports;

use App\Application\Bioestadistica\Capture\CaptureScopeService;
use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericFormImporter
{
    /**
     * Creates or updates an ad-hoc form from the confirmed wizard mapping.
     *
     * $mapping = [
     *   sheet, header_row, form_code, form_name, periodicidad, columns => [
     *     ['source' => 'Original header', 'code' => 'safe_code', 'label' => '...', 'type' => 'integer']
     *   ]
     * ]
     */
    public function createForm(array $mapping, int $userId): array
    {
        $this->validateMapping($mapping);
        $columns = $this->columns($mapping);
        $establishmentColumn = $mapping['establecimiento_column'] ?? null;

        return DB::transaction(function () use ($mapping, $columns, $userId, $establishmentColumn) {
            $formulario = Formulario::withTrashed()->firstOrNew([
                'codigo' => $mapping['form_code'],
            ]);
            if ($formulario->trashed()) {
                $formulario->restore();
            }
            $formulario->fill([
                'nombre' => $mapping['form_name'],
                'descripcion' => 'Formulario generado desde una importación Excel confirmada.',
                'periodicidad' => $mapping['periodicidad'] ?? 'ad_hoc',
                'layout_type' => 'tabular',
                'estado' => 'borrador',
                'created_by' => $formulario->exists ? $formulario->created_by : $userId,
            ])->save();

            $section = $formulario->secciones()->withTrashed()->firstOrCreate(
                ['titulo' => 'Datos importados'],
                ['descripcion' => 'Columnas confirmadas en el wizard de importación.', 'orden' => 1]
            );
            if ($section->trashed()) {
                $section->restore();
            }

            $created = 0;
            foreach ($columns as $order => $column) {
                if ($establishmentColumn && $column['source'] === $establishmentColumn) {
                    continue;
                }
                $detalleId = $this->detalleFor($formulario, $column);
                $field = $section->fields()->withTrashed()->firstOrNew(['code' => $column['code']]);
                if ($field->trashed()) {
                    $field->restore();
                }
                $config = $column['config'] ?? null;
                if ($detalleId) {
                    $config = array_merge($config ?? [], [
                        'row_source' => 'detalle_catalogo',
                        'row_detalle_id' => $detalleId,
                    ]);
                }
                $field->fill([
                    'label' => $column['label'],
                    'type' => $column['type'],
                    'required' => (bool) ($column['required'] ?? false),
                    'detalle_id' => $detalleId,
                    'config' => $config,
                    'orden' => $order + 1,
                ])->save();
                $created++;
            }

            return [
                'formulario_id' => $formulario->id,
                'formulario_codigo' => $formulario->codigo,
                'campos_procesados' => $created,
            ];
        });
    }

    /**
     * Imports rows only after the form exists and an explicit data period was supplied.
     * Existing editable records may be overwritten only when overwrite is true.
     */
    public function importData(
        string $path,
        Formulario $formulario,
        array $mapping,
        array $period,
        User $user,
        bool $overwrite = false
    ): array {
        if ($formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo') {
            throw ValidationException::withMessages([
                'formulario_id' => 'SP10 nominativo se carga en F6 (hosp_episodios), no como registro EAV.',
            ]);
        }
        if (! $period['anio'] || ! $period['mes']) {
            throw ValidationException::withMessages([
                'periodo' => 'La segunda pasada exige año y mes del período estadístico.',
            ]);
        }
        $this->validateMapping($mapping);
        $establishmentColumn = $mapping['establecimiento_column'] ?? null;
        if (! $establishmentColumn) {
            throw ValidationException::withMessages([
                'establecimiento_column' => 'Seleccione la columna que identifica el establecimiento.',
            ]);
        }

        $book = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);
        try {
            $sheet = $this->sheet($book, $mapping['sheet']);
            $headerRow = (int) $mapping['header_row'];
            $headers = $this->headers($sheet, $headerRow);
            $sourceColumns = collect($mapping['columns'])->keyBy('source');
            $saved = 0;
            $skipped = [];

            for ($rowNumber = $headerRow + 1; $rowNumber <= $sheet->getHighestDataRow(); $rowNumber++) {
                $row = $this->row($sheet, $rowNumber, $headers);
                if ($this->isEmpty($row)) {
                    continue;
                }
                $establishmentKey = trim((string) ($row[$establishmentColumn] ?? ''));
                $establishment = Establecimiento::where('codigo', $establishmentKey)->first()
                    ?? Establecimiento::where('codigo_sih', $establishmentKey)->first();
                if (! $establishment) {
                    $skipped[] = "Fila {$rowNumber}: establecimiento «{$establishmentKey}» no encontrado.";
                    continue;
                }
                if (! Record::userHasGlobalAccess($user)) {
                    $scope = app(CaptureScopeService::class);
                    try {
                        $scope->assertCanUseEstablecimiento($user, $establishment->id);
                        $scope->validateCanCapture($user, (int) $formulario->id, (int) $establishment->id, 'establecimiento_id');
                    } catch (ValidationException) {
                        $skipped[] = "Fila {$rowNumber}: sin alcance para el establecimiento «{$establishmentKey}».";
                        continue;
                    }
                }

                $record = Record::withTrashed()->where([
                    'formulario_id' => $formulario->id,
                    'establecimiento_id' => $establishment->id,
                    'periodo_anio' => (int) $period['anio'],
                    'periodo_mes' => (int) $period['mes'],
                ])->whereNull('estructura_servicio_id')->first();
                if ($record?->trashed()) {
                    $record->restore();
                }
                if ($record && ! $record->isEditable()) {
                    $skipped[] = "Fila {$rowNumber}: el registro existente no está en borrador u objetado.";
                    continue;
                }
                if ($record && ! $overwrite) {
                    $skipped[] = "Fila {$rowNumber}: ya existe un registro; confirme sobrescribir el borrador.";
                    continue;
                }
                $record ??= Record::create([
                    'formulario_id' => $formulario->id,
                    'establecimiento_id' => $establishment->id,
                    'periodo_anio' => (int) $period['anio'],
                    'periodo_mes' => (int) $period['mes'],
                    'estado' => Record::ESTADO_BORRADOR,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                $values = [];
                foreach ($sourceColumns as $source => $column) {
                    if ($source === $establishmentColumn) {
                        continue;
                    }
                    $value = $row[$source] ?? null;
                    if ($value !== null && $value !== '') {
                        $values[$column['code']] = $value;
                    }
                }
                app(RecordCaptureService::class)->save($record->load('formulario'), $values, false, true, $user->id);
                $saved++;
            }

            return [
                'registros_guardados' => $saved,
                'filas_omitidas' => count($skipped),
                'advertencias' => array_slice($skipped, 0, 100),
            ];
        } finally {
            $book->disconnectWorksheets();
        }
    }

    private function detalleFor(Formulario $formulario, array $column): ?int
    {
        if ($column['type'] !== 'select' || empty($column['values']) || ! is_array($column['values'])) {
            return null;
        }
        $dictionary = new HealthVariableDictionary();
        $detalleId = null;
        foreach (array_values(array_unique($column['values'])) as $value) {
            $value = trim((string) $value);
            if ($value === '') {
                continue;
            }
            $detalleId = $dictionary->remember(
                'IMP',
                'IMPORTADO',
                $formulario->codigo.' — '.$column['label'],
                $value
            )['detalle']->id;
        }

        return $detalleId;
    }

    private function validateMapping(array $mapping): void
    {
        if (empty($mapping['sheet']) || empty($mapping['header_row']) || empty($mapping['form_code'])
            || empty($mapping['form_name']) || empty($mapping['columns']) || ! is_array($mapping['columns'])) {
            throw ValidationException::withMessages([
                'mapeo' => 'El mapeo debe indicar hoja, encabezado, código, nombre y al menos una columna.',
            ]);
        }
    }

    private function columns(array $mapping): array
    {
        $allowed = ['text', 'textarea', 'integer', 'decimal', 'date', 'time', 'boolean', 'select'];

        return collect($mapping['columns'])->filter(function ($column) use ($allowed) {
            return is_array($column) && ! empty($column['source']) && ! empty($column['code'])
                && in_array($column['type'] ?? null, $allowed, true);
        })->map(function ($column) {
            $column['code'] = Str::lower(Str::slug((string) $column['code'], '_'));
            $column['label'] = trim((string) ($column['label'] ?? $column['source']));
            return $column;
        })->values()->all();
    }

    private function sheet($book, string|int $sheet): Worksheet
    {
        $worksheet = is_numeric($sheet)
            ? $book->getSheet((int) $sheet)
            : $book->getSheetByName($sheet);
        if (! $worksheet) {
            throw ValidationException::withMessages(['mapeo' => 'La hoja seleccionada ya no existe en el archivo.']);
        }
        return $worksheet;
    }

    private function headers(Worksheet $sheet, int $headerRow): array
    {
        $headers = [];
        $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        for ($column = 1; $column <= $lastColumn; $column++) {
            $headers[$column] = trim((string) $sheet->getCell([$column, $headerRow])->getFormattedValue());
        }
        return $headers;
    }

    private function row(Worksheet $sheet, int $row, array $headers): array
    {
        $values = [];
        foreach ($headers as $column => $header) {
            if ($header !== '') {
                $values[$header] = $sheet->getCell([$column, $row])->getFormattedValue();
            }
        }
        return $values;
    }

    private function isEmpty(array $row): bool
    {
        return collect($row)->every(fn ($value) => trim((string) $value) === '');
    }
}
