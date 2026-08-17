<?php

namespace App\Application\Bioestadistica\Imports;

use App\Models\Bioestadistica\CatalogItem;
use App\Models\Bioestadistica\Catalogo;
use App\Models\Bioestadistica\Formulario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class FormulariosSpImporter
{
    private const FORMS = [
        'SP1' => ['nombre' => 'Consultas Médicas', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '1'],
        'SP2' => ['nombre' => 'Enfermería', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '13'],
        'SP3' => ['nombre' => 'Estudios Baja Complejidad', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '12'],
        'SP4' => ['nombre' => 'Estudios Alta Complejidad', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '11'],
        'SP5' => ['nombre' => 'Laboratorio', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '10'],
        'SP6' => ['nombre' => 'Odontología', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '14'],
        'SP7' => ['nombre' => 'Procedimientos', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '14'],
        'SP8' => ['nombre' => 'Vacunación', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '16'],
        'SP9' => ['nombre' => 'Urgencias', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '4'],
        'SP10' => ['nombre' => 'Hospitalización', 'layout' => 'nominativo', 'periodicidad' => 'mensual', 'dominio' => '2'],
        'SP11' => ['nombre' => 'Paciente Día', 'layout' => 'matriz', 'periodicidad' => 'diaria', 'dominio' => '2'],
        'SP12' => ['nombre' => 'VIH y Tuberculosis', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '17'],
        'SP13' => ['nombre' => 'Programas de Salud', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => '17'],
        'SP14' => ['nombre' => 'Medicamentos e Insumos', 'layout' => 'tabular', 'periodicidad' => 'mensual', 'dominio' => 'x'],
    ];

    private const CONTEXT = [
        'departamento', 'establecimiento', 'codigo', 'mes', 'ano', 'anio', 'planilla',
        'distrito', 'codigo del establecimiento', 'planilla estadistica',
    ];

    public function __construct(private PrestacionMatcher $matcher)
    {
    }

    /**
     * @param  array<string, string>  $decisions  label => create|discard|{catalog_item_id}
     * @return array<string, mixed>
     */
    public function preview(string $filePath, array $decisions = []): array
    {
        $spreadsheet = $this->load($filePath);
        try {
            $forms = [];
            $warnings = [];
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $parsed = $this->parseSheet($sheet);
                if (! $parsed) {
                    continue;
                }
                if (! empty($parsed['advertencia'])) {
                    $warnings[] = $parsed['advertencia'];
                }
                $parsed['prestaciones'] = $this->matchRows($parsed, $decisions);
                $forms[] = $parsed;
            }

            if ($forms === []) {
                throw new RuntimeException('No se detectaron hojas de formularios SP.');
            }

            return [
                'tipo' => 'formularios_sp',
                'hojas_procesadas' => count($forms),
                'formularios' => $forms,
                'prestaciones_enlazadas' => collect($forms)->sum(
                    fn (array $form) => collect($form['prestaciones'])->whereNotNull('catalog_item_id')->count()
                ),
                'prestaciones_sin_match' => collect($forms)->sum(
                    fn (array $form) => collect($form['prestaciones'])->whereNull('catalog_item_id')->count()
                ),
                'advertencias' => $warnings,
            ];
        } finally {
            $spreadsheet->disconnectWorksheets();
        }
    }

    /**
     * @param  array<string, string>  $decisions
     * @return array<string, mixed>
     */
    public function import(string $filePath, array $decisions = []): array
    {
        $preview = $this->preview($filePath, $decisions);
        $createdForms = 0;
        $createdFields = 0;
        $linked = 0;
        $unmatched = 0;
        $ignored = 0;

        DB::transaction(function () use ($preview, $decisions, &$createdForms, &$createdFields, &$linked, &$unmatched, &$ignored) {
            foreach ($preview['formularios'] as $form) {
                $meta = self::FORMS[$form['codigo']] ?? [
                    'nombre' => $form['nombre'],
                    'layout' => $form['codigo'] === 'SP10' ? 'nominativo' : ($form['codigo'] === 'SP11' ? 'matriz' : 'tabular'),
                    'periodicidad' => $form['codigo'] === 'SP11' ? 'diaria' : 'mensual',
                ];
                $formulario = Formulario::withTrashed()->firstOrNew(['codigo' => $form['codigo']]);
                $created = ! $formulario->exists;
                if ($formulario->trashed()) {
                    $formulario->restore();
                }
                $formulario->fill([
                    'nombre' => $meta['nombre'],
                    'descripcion' => 'Estructura importada desde Formularios SP.xls.',
                    'periodicidad' => $meta['periodicidad'],
                    'layout_type' => $meta['layout'],
                    'estado' => $formulario->estado === 'activo' ? 'activo' : 'borrador',
                ])->save();
                $createdForms += (int) $created;

                $context = $formulario->secciones()->withTrashed()->firstOrCreate(
                    ['titulo' => 'Contexto'],
                    ['descripcion' => 'Establecimiento y período estadístico.', 'orden' => 0]
                );
                if ($context->trashed()) {
                    $context->restore();
                }

                if ($form['codigo'] === 'SP10') {
                    $createdFields += $this->syncNominativo($formulario, $form);
                    continue;
                }
                if ($form['codigo'] === 'SP11') {
                    $createdFields += $this->syncMatriz($formulario, $form);
                    continue;
                }

                $result = $this->syncTabular($formulario, $form, $decisions);
                $createdFields += $result['campos'];
                $linked += $result['enlazadas'];
                $unmatched += $result['sin_match'];
                $ignored += $result['ignoradas'];
            }
        });

        return [
            'tipo' => 'formularios_sp',
            'hojas_procesadas' => $preview['hojas_procesadas'],
            'formularios_creados' => $createdForms,
            'campos_creados' => $createdFields,
            'prestaciones_enlazadas' => $linked,
            'prestaciones_sin_match' => $unmatched,
            'filas_ignoradas' => $ignored,
            'advertencias' => $preview['advertencias'],
        ];
    }

    /**
     * @param  array<string, string>  $decisions
     * @param  array<string, mixed>  $form
     * @return array<int, array<string, mixed>>
     */
    private function matchRows(array $form, array $decisions): array
    {
        $domain = self::FORMS[$form['codigo']]['dominio'] ?? null;
        $rows = [];
        foreach ($form['filas'] as $label) {
            $decision = $decisions[$label] ?? $decisions[$this->matcher->normalize($label)] ?? null;
            $match = $this->matcher->match($label, $domain);
            if ($decision === 'discard') {
                $rows[] = array_merge($match, ['label' => $label, 'catalog_item_id' => null, 'accion' => 'discard']);
                continue;
            }
            if (is_numeric($decision)) {
                $item = CatalogItem::find((int) $decision);
                $rows[] = [
                    'label' => $label,
                    'nivel' => 4,
                    'catalog_item_id' => $item?->id,
                    'catalogo_id' => $item?->catalogo_id,
                    'sugerencia' => $item?->label,
                    'score' => 1,
                    'accion' => 'manual',
                ];
                continue;
            }
            if ($decision === 'create' || ($match['catalog_item_id'] === null && $decision === null)) {
                $rows[] = array_merge($match, [
                    'label' => $label,
                    'accion' => $decision === 'create' ? 'create' : 'pending',
                ]);
                continue;
            }
            $rows[] = array_merge($match, ['label' => $label, 'accion' => 'link']);
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function parseSheet(Worksheet $sheet): ?array
    {
        $code = $this->detectCode($sheet);
        if (! $code) {
            return null;
        }
        $warning = null;
        if ($code === 'SP2' && ! str_contains(Str::upper($sheet->getTitle()), 'SP2')) {
            $warning = 'SP2: hoja nombrada '.$sheet->getTitle().', normalizada a SP2';
        }

        $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $lastRow = $sheet->getHighestDataRow();
        [$headerRow, $headers] = $this->findTableHeader($sheet, $lastRow, $lastColumn);
        $metricColumns = $this->metricColumns($headers);
        $dayColumns = $this->dayColumns($headers);
        $labels = [];
        if ($headerRow) {
            for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
                $label = $this->cleanLabel((string) $sheet->getCell([1, $row])->getFormattedValue());
                if ($label === '' || $this->isContext($label) || $this->isTotal($label)) {
                    continue;
                }
                $labels[] = $label;
            }
        }

        return [
            'codigo' => $code,
            'nombre' => self::FORMS[$code]['nombre'] ?? $sheet->getTitle(),
            'hoja' => $sheet->getTitle(),
            'advertencia' => $warning,
            'columnas' => array_values($headers),
            'metricas' => $metricColumns,
            'dias' => $dayColumns,
            'filas' => array_values(array_unique($labels)),
        ];
    }

    private function detectCode(Worksheet $sheet): ?string
    {
        $title = Str::upper(Str::ascii($sheet->getTitle()));
        $scan = $title;
        $lastColumn = min(8, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));
        for ($row = 1; $row <= min(12, $sheet->getHighestDataRow()); $row++) {
            for ($column = 1; $column <= $lastColumn; $column++) {
                $scan .= ' '.$sheet->getCell([$column, $row])->getFormattedValue();
            }
        }
        $scan = Str::upper(Str::ascii($scan));
        if (str_contains($scan, 'TABLA SP 2') || str_contains($scan, 'TABLA SP2')) {
            return 'SP2';
        }
        if (preg_match('/\bSP\s*([1-9]|1[0-4])\b/', $scan, $match)) {
            return 'SP'.((int) $match[1]);
        }
        if (preg_match('/^SP\s*([1-9]|1[0-4])\b/', $title, $match)) {
            return 'SP'.((int) $match[1]);
        }

        return null;
    }

    /**
     * @return array{0: int|null, 1: array<int, string>}
     */
    private function findTableHeader(Worksheet $sheet, int $lastRow, int $lastColumn): array
    {
        $bestRow = null;
        $bestHeaders = [];
        $bestScore = -1;
        $scanRows = min(40, $lastRow);
        for ($row = 1; $row <= $scanRows; $row++) {
            $headers = [];
            $nonEmpty = 0;
            $contextHits = 0;
            for ($column = 1; $column <= $lastColumn; $column++) {
                $value = $this->cleanLabel((string) $sheet->getCell([$column, $row])->getFormattedValue());
                $headers[$column] = $value;
                if ($value === '') {
                    continue;
                }
                $nonEmpty++;
                if ($this->isContext($value)) {
                    $contextHits++;
                }
            }
            if ($nonEmpty < 2 || $contextHits > 0) {
                continue;
            }
            $score = $nonEmpty - $contextHits;
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $row;
                $bestHeaders = $headers;
            }
        }

        return [$bestRow, $bestHeaders];
    }

    /**
     * @param  array<int, string>  $headers
     * @return array<int, array{code: string, label: string, type: string}>
     */
    private function metricColumns(array $headers): array
    {
        $metrics = [];
        $first = true;
        foreach ($headers as $label) {
            if ($first) {
                $first = false;
                continue;
            }
            if ($label === '' || $this->isDayLabel($label)) {
                continue;
            }
            $metrics[] = [
                'code' => Str::limit(Str::slug($label, '_'), 80, '') ?: 'metrica',
                'label' => $label,
                'type' => 'integer',
                'min' => 0,
            ];
        }

        return $metrics;
    }

    /**
     * @param  array<int, string>  $headers
     * @return array<int, string>
     */
    private function dayColumns(array $headers): array
    {
        return array_values(array_filter($headers, fn ($label) => $this->isDayLabel($label)));
    }

    /**
     * @param  array<string, mixed>  $form
     * @param  array<string, string>  $decisions
     * @return array{campos:int,enlazadas:int,sin_match:int,ignoradas:int}
     */
    private function syncTabular(Formulario $formulario, array $form, array $decisions): array
    {
        $section = $formulario->secciones()->withTrashed()->firstOrCreate(
            ['titulo' => $form['nombre']],
            ['descripcion' => 'Bloque importado desde '.$form['hoja'].'.', 'orden' => 1]
        );
        if ($section->trashed()) {
            $section->restore();
        }

        $groups = [];
        $ignored = 0;
        $unmatched = 0;
        $linked = 0;
        foreach ($form['prestaciones'] as $row) {
            if (($row['accion'] ?? null) === 'discard') {
                $ignored++;
                continue;
            }
            $itemId = $row['catalog_item_id'] ?? null;
            $catalogId = $row['catalogo_id'] ?? null;
            if (! $itemId && ($row['accion'] ?? null) === 'create') {
                [$catalogId, $itemId] = $this->createPrestacion($formulario, $row['label']);
            }
            if (! $itemId || ! $catalogId) {
                $unmatched++;
                continue;
            }
            $groups[$catalogId][] = $itemId;
            $linked++;
        }

        $metrics = $form['metricas'] ?: [[
            'code' => 'total',
            'label' => 'Total',
            'type' => 'integer',
            'min' => 0,
        ]];
        $fields = 0;
        $index = 0;
        foreach ($groups as $catalogId => $itemIds) {
            $catalog = Catalogo::find($catalogId);
            $code = $formulario->codigo === 'SP1' && $index === 0
                ? 'consultas_por_especialidad'
                : Str::lower(Str::slug(($catalog?->codigo ?: 'datos').'_'.$index, '_'));
            $field = $section->fields()->withTrashed()->firstOrNew(['code' => $code]);
            if ($field->trashed()) {
                $field->restore();
            }
            $field->fill([
                'label' => $catalog?->nombre ?: $form['nombre'],
                'type' => 'tabla',
                'required' => $index === 0,
                'catalogo_id' => $catalogId,
                'config' => [
                    'row_source' => 'catalogo',
                    'row_catalog_id' => $catalogId,
                    'row_label' => 'Prestación',
                    'totals' => true,
                    'columns' => $metrics,
                ],
                'orden' => $index + 1,
            ])->save();
            $fields++;
            $index++;
        }

        return [
            'campos' => $fields,
            'enlazadas' => $linked,
            'sin_match' => $unmatched,
            'ignoradas' => $ignored,
        ];
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function syncNominativo(Formulario $formulario, array $form): int
    {
        $section = $formulario->secciones()->withTrashed()->firstOrCreate(
            ['titulo' => 'Episodio'],
            ['descripcion' => 'Metadata nominativa. La carga de pacientes se habilita en F6.', 'orden' => 1]
        );
        if ($section->trashed()) {
            $section->restore();
        }
        $created = 0;
        foreach (array_values(array_filter($form['columnas'])) as $order => $label) {
            if ($this->isContext($label)) {
                continue;
            }
            $code = Str::slug($label, '_') ?: 'campo_'.$order;
            $field = $section->fields()->withTrashed()->firstOrNew(['code' => $code]);
            if ($field->trashed()) {
                $field->restore();
            }
            $field->fill([
                'label' => $label,
                'type' => 'text',
                'required' => false,
                'orden' => $order + 1,
            ])->save();
            $created++;
        }

        return $created;
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function syncMatriz(Formulario $formulario, array $form): int
    {
        $section = $formulario->secciones()->withTrashed()->firstOrCreate(
            ['titulo' => 'Paciente día'],
            ['descripcion' => 'Matriz calendario de 31 días.', 'orden' => 1]
        );
        if ($section->trashed()) {
            $section->restore();
        }
        $rows = $form['filas'] ?: ['ingresos', 'egresos', 'obitos', 'pacientes_dia', 'camas_disponibles', 'camas_operativas'];
        $field = $section->fields()->withTrashed()->firstOrNew(['code' => 'paciente_dia']);
        if ($field->trashed()) {
            $field->restore();
        }
        $field->fill([
            'label' => 'Paciente día',
            'type' => 'matriz',
            'required' => true,
            'config' => \App\Application\Bioestadistica\Sp11Matrix::defaultConfig(),
            'orden' => 1,
        ])->save();

        return 1;
    }

    /**
     * @return array{0:int,1:int}
     */
    private function createPrestacion(Formulario $formulario, string $label): array
    {
        $code = Str::upper(Str::limit('SPIMP_'.$formulario->codigo, 80, ''));
        $catalog = Catalogo::firstOrCreate(
            ['codigo' => $code],
            [
                'nombre' => "Prestaciones importadas {$formulario->codigo}",
                'descripcion' => 'Creadas desde matching asistido de Formularios SP.',
                'activo' => true,
            ]
        );
        $item = CatalogItem::firstOrCreate(
            ['catalogo_id' => $catalog->id, 'codigo' => Str::limit(Str::upper(Str::slug($label, '_')), 65, '').'_'.substr(sha1($label), 0, 8)],
            [
                'label' => $label,
                'prestacion' => $label,
                'domain_code' => self::FORMS[$formulario->codigo]['dominio'] ?? null,
                'activo' => true,
                'orden' => 0,
            ]
        );

        return [(int) $catalog->id, (int) $item->id];
    }

    private function cleanLabel(string $value): string
    {
        $value = preg_replace('/\(\s*\d+\s*\)/', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? $value;

        return $value;
    }

    private function isContext(string $value): bool
    {
        $key = Str::lower(Str::ascii($value));

        foreach (self::CONTEXT as $token) {
            if (str_starts_with($key, $token)) {
                return true;
            }
        }

        return false;
    }

    private function isTotal(string $value): bool
    {
        $key = Str::upper(Str::ascii($value));

        return str_starts_with($key, 'TOTAL') || str_starts_with($key, 'SUBTOTAL');
    }

    private function isDayLabel(string $value): bool
    {
        return preg_match('/^(?:dia\s*)?(?:[1-9]|[12]\d|3[01])$/', Str::lower(trim($value))) === 1;
    }

    private function load(string $filePath): Spreadsheet
    {
        try {
            return IOFactory::createReaderForFile($filePath)
                ->setReadDataOnly(true)
                ->load($filePath);
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'El archivo de formularios SP no pudo abrirse: '.$exception->getMessage(),
                0,
                $exception
            );
        }
    }
}
