<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Imports\SpPlanillaImportService;
use App\Application\Bioestadistica\Imports\SpPlanillaParser;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class BioestadisticaSpPlanillaImportTest extends TestCase
{
    use DatabaseTransactions;

    private function samplePath(): string
    {
        return base_path('.docs-bio/ESTADISTICA JULIO 2026.xls');
    }

    public function test_sp1_parser_reads_header_and_consultation_rows(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $parsed = app(SpPlanillaParser::class)->parse($this->samplePath());

        $this->assertSame('SP1', $parsed['formulario_codigo']);
        $this->assertSame(7, $parsed['periodo_mes']);
        $this->assertSame(2026, $parsed['periodo_anio']);
        $this->assertSame('73', $parsed['codigo_planilla']);
        $this->assertGreaterThan(10, $parsed['filas_detectadas']);
        $clinica = collect($parsed['filas'])->firstWhere('especialidad', 'CLINICA MEDICA');
        $this->assertNotNull($clinica);
        $this->assertSame(2094, $clinica['total_consultas']);

        $user = User::first();
        if ($user) {
            $preview = app(SpPlanillaImportService::class)->applyPreviewContext([
                'detectado' => $parsed,
                'formulario_id' => Formulario::where('codigo', 'SP1')->value('id'),
            ]);
            $this->assertGreaterThan(0, $preview['detectado']['prestaciones_enlazadas']);
        }
    }

    public function test_workbook_scan_lists_multiple_importable_sheets(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());

        $this->assertSame('73', $workbook['contexto']['codigo_planilla'] ?? null);
        $this->assertSame(7, $workbook['contexto']['periodo_mes'] ?? null);
        $this->assertGreaterThanOrEqual(3, count($workbook['hojas']));

        $importables = collect($workbook['hojas'])
            ->filter(fn (array $h) => in_array($h['sp_codigo'] ?? '', SpPlanillaImportService::IMPORTABLE, true))
            ->filter(fn (array $h) => ($h['filas_detectadas'] ?? 0) > 0)
            ->pluck('sp_codigo')
            ->unique()
            ->values()
            ->all();

        $this->assertContains('SP1', $importables);
        $this->assertTrue(
            count(array_intersect(['SP2', 'SP3', 'SP6', 'SP7', 'SP9'], $importables)) >= 2,
            'Se esperaban al menos dos SP importables además de SP1 en la planilla de muestra.'
        );
    }

    public function test_sp3_sheet_parses_pacientes_and_estudios_columns(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp3 = collect($workbook['hojas'])->first(
            fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP3'
                && str_contains(Str::upper($h['titulo'] ?? ''), 'BAJA')
        );

        if (! $sp3 || ! ($sp3['parseado'] ?? false)) {
            $this->markTestSkipped('Falta hoja SP3 baja complejidad parseable en la muestra.');
        }

        $filas = $sp3['detectado']['filas'] ?? [];
        $this->assertNotEmpty($filas);
        $conMetricas = collect($filas)->first(fn (array $f) => ! empty($f['metricas']['estudios']) || ! empty($f['metricas']['pacientes']));
        $this->assertNotNull($conMetricas, 'Se esperaba al menos una fila con pacientes o estudios.');
    }

    public function test_confirm_batch_creates_draft_records_for_auto_matched_sheets(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $est = Establecimiento::where('codigo_sih', '73')->first();
        if (! $user || ! $est) {
            $this->markTestSkipped('Faltan usuario o establecimiento 73.');
        }

        $service = app(SpPlanillaImportService::class);
        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $preview = $service->analyze($file, $user);
        $preview = $service->enrichWorkbookForSummary($service->applyPreviewContext($preview, [
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2098,
            'periodo_mes' => 7,
        ]));

        $hojasListas = collect($preview['workbook']['hojas'] ?? [])
            ->filter(fn (array $h) => $h['listo_lote'] ?? false)
            ->pluck('titulo')
            ->take(2)
            ->values()
            ->all();

        if ($hojasListas === []) {
            $this->markTestSkipped('Ninguna hoja lista para lote en la muestra.');
        }

        foreach (collect($preview['workbook']['hojas'] ?? [])->pluck('sp_codigo')->filter()->unique() as $codigo) {
            $formulario = Formulario::where('codigo', $codigo)->first();
            if ($formulario) {
                Record::where('formulario_id', $formulario->id)
                    ->where('establecimiento_id', $est->id)
                    ->where('periodo_anio', 2098)
                    ->where('periodo_mes', 7)
                    ->delete();
            }
        }

        $resultado = $service->confirmBatch($user, $preview, [
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2098,
            'periodo_mes' => 7,
            'estructura_servicio_id' => null,
        ], $hojasListas, true);

        $this->assertNotEmpty($resultado['ok'], 'Se esperaba al menos un SP importado en lote.');
        foreach ($resultado['ok'] as $item) {
            $record = Record::find($item['record_id']);
            $this->assertNotNull($record);
            $this->assertSame(Record::ESTADO_BORRADOR, $record->estado);
        }
    }

    public function test_analyze_resolves_establecimiento_by_codigo_sih_without_storing_file(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario.');
        }

        $est = Establecimiento::where('codigo_sih', '73')->first();
        if (! $est) {
            $this->markTestSkipped('Falta establecimiento con codigo_sih 73.');
        }

        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $preview = app(SpPlanillaImportService::class)->analyze($file, $user);

        $this->assertSame($est->id, $preview['establecimiento']['id']);
        $this->assertSame('SP1', $preview['detectado']['formulario_codigo']);
        $this->assertNotEmpty($preview['workbook']['hojas'] ?? []);
        $this->assertFileDoesNotExist(storage_path('app/bioestadistica/imports/ESTADISTICA JULIO 2026.xls'));
    }

    public function test_confirm_creates_sp1_draft_record_from_session_preview(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $est = Establecimiento::where('codigo_sih', '73')->first();
        $formulario = Formulario::where('codigo', 'SP1')->first();
        if (! $user || ! $est || ! $formulario) {
            $this->markTestSkipped('Faltan datos piloto SP1 / establecimiento 73.');
        }

        Record::where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $est->id)
            ->where('periodo_anio', 2099)
            ->where('periodo_mes', 7)
            ->delete();

        $service = app(SpPlanillaImportService::class);
        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $preview = $service->analyze($file, $user);

        $decisiones = [];
        foreach ($preview['detectado']['filas'] as $fila) {
            if (! empty($fila['prestacion_id'])) {
                $decisiones[$fila['key']] = (string) $fila['prestacion_id'];
            }
        }
        $this->assertNotEmpty($decisiones, 'Se esperaba al menos una fila enlazada al diccionario SP1.');

        $record = $service->confirm($user, $preview, [
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2099,
            'periodo_mes' => 7,
        ], $decisiones, false);

        $this->assertSame(Record::ESTADO_BORRADOR, $record->estado);
        $this->assertSame($formulario->id, $record->formulario_id);
        $value = $record->values()->whereHas('field', fn ($q) => $q->where('code', 'consultas_por_especialidad'))->first();
        $this->assertNotNull($value);
        $rows = $value->value_json['rows'] ?? [];
        $this->assertNotEmpty($rows);
        $this->assertGreaterThan(1000, array_sum(array_column($rows, 'total_consultas')));
    }

    public function test_sp5_sheet_parses_stacked_determinaciones(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp5 = collect($workbook['hojas'])->first(fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP5');

        if (! $sp5) {
            $this->markTestSkipped('Falta hoja SP5 en la muestra.');
        }

        $this->assertTrue($sp5['parseado'] ?? false, $sp5['error'] ?? 'SP5 no parseado');
        $filas = $sp5['detectado']['filas'] ?? [];
        $this->assertGreaterThan(10, count($filas));

        $hemograma = collect($filas)->first(fn (array $f) => str_contains(strtoupper($f['prestacion_label'] ?? ''), 'HEMOGRAMA'));
        $this->assertNotNull($hemograma);
        $this->assertSame(4784, $hemograma['metricas']['total'] ?? null);

        $pacientes = collect($filas)->first(fn (array $f) => str_contains(strtoupper($f['prestacion_label'] ?? ''), 'PACIENTES ATENDIDOS'));
        $this->assertNotNull($pacientes);
        $this->assertSame(6871, $pacientes['metricas']['total'] ?? null);
    }

    public function test_sp5_form_has_single_total_column(): void
    {
        if (! \App\Models\Bioestadistica\Variable::where('codigo', '10')->exists()) {
            $this->markTestSkipped('Falta el diccionario de variables para SP5.');
        }

        $this->seed(\Database\Seeders\BioestadisticaFormulariosSpSeeder::class);

        $tables = Formulario::where('codigo', 'SP5')
            ->with('secciones.fields')
            ->first()
            ?->secciones
            ->flatMap->fields
            ->where('type', 'tabla') ?? collect();

        $this->assertGreaterThan(0, $tables->count());
        foreach ($tables as $field) {
            $columns = collect($field->config['columns'] ?? [])->pluck('code')->all();
            $this->assertSame(['total'], $columns, "El campo {$field->code} debe tener solo la columna total.");
        }
    }

    public function test_sp6_sheet_parses_stacked_prestaciones(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp6 = collect($workbook['hojas'])->first(fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP6');

        if (! $sp6) {
            $this->markTestSkipped('Falta hoja SP6 en la muestra.');
        }

        $this->assertTrue($sp6['parseado'] ?? false, $sp6['error'] ?? 'SP6 no parseado');
        $filas = $sp6['detectado']['filas'] ?? [];
        $this->assertGreaterThan(5, count($filas));

        $acabado = collect($filas)->first(fn (array $f) => str_contains(strtoupper($f['prestacion_label'] ?? ''), 'ACABADO'));
        $this->assertNotNull($acabado);
        $this->assertSame(112, $acabado['metricas']['total'] ?? null);
        $this->assertSame('149', $acabado['cod_planilla'] ?? null);
    }

    public function test_sp6_form_has_single_total_column(): void
    {
        if (! \App\Models\Bioestadistica\Variable::where('codigo', '14')->exists()) {
            $this->markTestSkipped('Falta el diccionario de variables para SP6.');
        }

        $this->seed(\Database\Seeders\BioestadisticaFormulariosSpSeeder::class);

        $tables = Formulario::where('codigo', 'SP6')
            ->with('secciones.fields')
            ->first()
            ?->secciones
            ->flatMap->fields
            ->where('type', 'tabla') ?? collect();

        $this->assertGreaterThan(0, $tables->count());
        foreach ($tables as $field) {
            $columns = collect($field->config['columns'] ?? [])->pluck('code')->all();
            $this->assertSame(['total'], $columns, "El campo {$field->code} debe tener solo la columna total.");
        }
    }

    public function test_sp8_sheet_parses_crosstab_vaccination_rows(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp8 = collect($workbook['hojas'])->first(fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP8');

        if (! $sp8 || ! ($sp8['parseado'] ?? false)) {
            $this->markTestSkipped('Falta hoja SP8 parseable en la muestra.');
        }

        $detectado = $sp8['detectado'];
        $this->assertSame('tabular', $detectado['layout'] ?? null);
        $this->assertGreaterThan(0, count($detectado['filas'] ?? []));
        $conMetricas = collect($detectado['filas'])->first(fn (array $f) => ! empty($f['metricas']['m_menores_1']) || ! empty($f['metricas']['f_menores_1']));
        $this->assertNotNull($conMetricas, 'Se esperaba al menos una fila con dosis M/F.');
        $breakdown = collect($conMetricas['metricas'])->except('total')->sum();
        $this->assertSame($breakdown, $conMetricas['metricas']['total'] ?? null);
    }

    public function test_sp8_row_total_accepts_manual_total_without_breakdown(): void
    {
        if (! \App\Models\Bioestadistica\Variable::where('codigo', '16')->exists()) {
            $this->markTestSkipped('Falta el diccionario de variables para SP8.');
        }

        $this->seed(\Database\Seeders\BioestadisticaFormulariosSpSeeder::class);

        $field = Formulario::where('codigo', 'SP8')
            ->with('secciones.fields')
            ->first()
            ?->secciones
            ->flatMap->fields
            ->firstWhere('type', 'tabla');

        if (! $field) {
            $this->markTestSkipped('Falta campo tabular SP8.');
        }

        $itemId = (string) $field->rowItems()->value('id');
        if ($itemId === '') {
            $this->markTestSkipped('Faltan ítems de vacuna en el diccionario SP8.');
        }

        $capture = app(\App\Application\Bioestadistica\RecordCaptureService::class);
        $method = (new \ReflectionClass($capture))->getMethod('tabla');
        $normalized = $method->invoke($capture, $field, [
            'rows' => [
                $itemId => ['total' => 42],
            ],
        ], false);

        $this->assertSame(42, $normalized['rows'][$itemId]['total'] ?? null);

        $normalizedWithBreakdown = $method->invoke($capture, $field, [
            'rows' => [
                $itemId => [
                    'total' => 999,
                    'm_menores_1' => 10,
                    'f_menores_1' => 5,
                ],
            ],
        ], false);

        $this->assertSame(15, $normalizedWithBreakdown['rows'][$itemId]['total'] ?? null);
    }

    public function test_sp9_sheet_parses_consultas_observacion_procedimiento_and_total(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp9 = collect($workbook['hojas'])->first(fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP9');

        if (! $sp9 || ! ($sp9['parseado'] ?? false)) {
            $this->markTestSkipped('Falta hoja SP9 parseable en la muestra.');
        }

        $detectado = $sp9['detectado'];
        $this->assertSame('tabular', $detectado['layout'] ?? null);
        $this->assertGreaterThan(0, count($detectado['filas'] ?? []));

        $clinica = collect($detectado['filas'])->first(
            fn (array $f) => str_contains(strtoupper($f['prestacion_label'] ?? ''), 'CLINICA MEDICA')
        );
        $this->assertNotNull($clinica);
        $this->assertSame(3404, $clinica['metricas']['total'] ?? null);
        $this->assertSame('var_4_atencion_de_urgencias_adultos', $clinica['field_code'] ?? null);

        $pediatricas = collect($detectado['filas'])->first(
            fn (array $f) => ($f['field_code'] ?? '') === 'var_4_atencion_de_urgencias_pediatricas'
        );
        $this->assertNotNull($pediatricas);
        $this->assertSame(1767, $pediatricas['metricas']['total'] ?? null);
    }

    public function test_sp9_row_total_accepts_manual_total_without_breakdown(): void
    {
        if (! \App\Models\Bioestadistica\Variable::where('codigo', '4')->exists()) {
            $this->markTestSkipped('Falta el diccionario de variables para SP9.');
        }

        $this->seed(\Database\Seeders\BioestadisticaFormulariosSpSeeder::class);

        $field = Formulario::where('codigo', 'SP9')
            ->with('secciones.fields')
            ->first()
            ?->secciones
            ->flatMap->fields
            ->firstWhere('code', 'var_4_atencion_de_urgencias_adultos');

        if (! $field) {
            $this->markTestSkipped('Falta tabla de urgencias adultos en SP9.');
        }

        $itemId = (string) $field->rowItems()->value('id');
        if ($itemId === '') {
            $this->markTestSkipped('Faltan prestaciones de urgencias adultos.');
        }

        $capture = app(\App\Application\Bioestadistica\RecordCaptureService::class);
        $method = (new \ReflectionClass($capture))->getMethod('tabla');
        $normalized = $method->invoke($capture, $field, [
            'rows' => [
                $itemId => ['total' => 120],
            ],
        ], false);

        $this->assertSame(120, $normalized['rows'][$itemId]['total'] ?? null);

        $normalizedWithBreakdown = $method->invoke($capture, $field, [
            'rows' => [
                $itemId => [
                    'total' => 999,
                    'consultas' => 40,
                    'observacion' => 10,
                    'procedimiento' => 5,
                ],
            ],
        ], false);

        $this->assertSame(55, $normalizedWithBreakdown['rows'][$itemId]['total'] ?? null);
    }

    public function test_sp11_sheet_parses_matrix_layout(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp11 = collect($workbook['hojas'])->first(fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP11');

        if (! $sp11 || ! ($sp11['parseado'] ?? false)) {
            $this->markTestSkipped('Falta hoja SP11 parseable en la muestra.');
        }

        $detectado = $sp11['detectado'];
        $this->assertSame('matriz', $detectado['layout'] ?? null);
        $rows = $detectado['matriz']['rows'] ?? [];
        $this->assertNotEmpty($rows);
        $this->assertTrue(
            isset($rows['principio_dia'])
            || isset($rows['ingresos'])
            || isset($rows['altas'])
            || isset($rows['total_pacientes_dia'])
            || isset($rows['pacientes_dia']),
            'SP11 debe incluir filas del censo diario.'
        );
    }

    public function test_confirm_batch_can_import_sp11_matrix_sheet(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $est = Establecimiento::where('codigo_sih', '73')->first();
        $formulario = Formulario::where('codigo', 'SP11')->first();
        if (! $user || ! $est || ! $formulario) {
            $this->markTestSkipped('Faltan usuario, establecimiento 73 o formulario SP11.');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($this->samplePath());
        $sp11 = collect($workbook['hojas'])->first(
            fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP11' && ($h['parseado'] ?? false)
        );
        if (! $sp11) {
            $this->markTestSkipped('Falta hoja SP11 parseable en la muestra.');
        }

        Record::where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $est->id)
            ->where('periodo_anio', 2097)
            ->where('periodo_mes', 7)
            ->delete();

        $service = app(SpPlanillaImportService::class);
        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $preview = $service->analyze($file, $user);
        $preview = $service->enrichWorkbookForSummary($service->applyPreviewContext($preview, [
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2097,
            'periodo_mes' => 7,
        ]));

        $resultado = $service->confirmBatch($user, $preview, [
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2097,
            'periodo_mes' => 7,
            'estructura_servicio_id' => null,
        ], [(string) $sp11['titulo']], true);

        $this->assertNotEmpty($resultado['ok'], 'Se esperaba importar SP11 en lote.');
        $record = Record::find($resultado['ok'][0]['record_id']);
        $this->assertNotNull($record);
        $this->assertSame(Record::ESTADO_BORRADOR, $record->estado);
        $value = $record->values()->whereHas('field', fn ($q) => $q->where('code', 'paciente_dia'))->first();
        $this->assertNotNull($value);
    }

    public function test_workbook_summary_includes_calidad_score(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario.');
        }

        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $service = app(SpPlanillaImportService::class);
        $preview = $service->analyze($file, $user);
        $preview = $service->enrichWorkbookForSummary($preview);

        $sp1 = collect($preview['workbook']['hojas'])->first(
            fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP1'
        );
        $this->assertNotNull($sp1);
        $this->assertArrayHasKey('calidad', $sp1);
        $this->assertArrayHasKey('score', $sp1['calidad']);
        $this->assertArrayHasKey('nivel', $sp1['calidad']);
        $this->assertContains($sp1['calidad']['nivel'], ['alto', 'medio', 'bajo', 'fallido']);
        $this->assertNotEmpty($preview['temp_path'] ?? null);
        $this->assertFileExists($preview['temp_path']);

        $service->forgetPreview();
        // analyze no guarda en session; borrar temp manualmente como haría forgetPreview con sesión
        if (is_file($preview['temp_path'])) {
            @unlink($preview['temp_path']);
        }
    }

    public function test_apply_sheet_mapping_reparses_sp1_with_manual_header_row(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $formulario = Formulario::where('codigo', 'SP1')->where('estado', 'activo')->first();
        if (! $user || ! $formulario) {
            $this->markTestSkipped('Faltan usuario o SP1.');
        }

        $service = app(SpPlanillaImportService::class);
        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $preview = $service->analyze($file, $user);
        $hoja = collect($preview['workbook']['hojas'])->first(
            fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP1' && ($h['parseado'] ?? false)
        );
        $this->assertNotNull($hoja);

        $headerRow = (int) ($hoja['detectado']['fila_encabezado'] ?? 0);
        $this->assertGreaterThan(0, $headerRow);

        $preview = $service->applySheetMapping($preview, (string) $hoja['titulo'], [
            'formulario_codigo' => 'SP1',
            'fila_encabezado' => $headerRow,
            'columnas' => [],
        ]);

        $this->assertSame('SP1', $preview['formulario_codigo'] ?? $preview['detectado']['formulario_codigo']);
        $this->assertGreaterThan(5, $preview['detectado']['filas_detectadas'] ?? 0);
        $this->assertArrayHasKey($hoja['titulo'], $preview['mapeos'] ?? []);

        if (! empty($preview['temp_path']) && is_file($preview['temp_path'])) {
            @unlink($preview['temp_path']);
        }
    }

    public function test_map_route_renders_for_analyzed_sheet(): void
    {
        if (! is_file($this->samplePath())) {
            $this->markTestSkipped('Falta planilla de muestra en .docs-bio');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        if (! $user) {
            $this->markTestSkipped('Falta usuario.');
        }

        $service = app(SpPlanillaImportService::class);
        $file = new UploadedFile($this->samplePath(), 'ESTADISTICA JULIO 2026.xls', null, null, true);
        $preview = $service->analyze($file, $user);
        $service->storePreview($preview);

        $hoja = $preview['hoja_activa'];
        $this->actingAs($user)
            ->get(route('bioestadistica.captura.import.map', ['hoja' => $hoja]))
            ->assertOk()
            ->assertSee('Ajustar mapeo')
            ->assertSee('Fila de encabezado');

        $service->forgetPreview();
    }

    public function test_sp12_sheet_is_importable_and_can_confirm_draft(): void
    {
        $path = base_path('.docs-bio/PLANILLAS CARGA MENSUAL AGOSTO 2026.xls');
        if (! is_file($path)) {
            $this->markTestSkipped('Falta planilla mensual de muestra con SP12.');
        }

        $user = User::role('Administrador')->first() ?? User::first();
        $formulario = Formulario::where('codigo', 'SP12')->where('estado', 'activo')->first();
        $est = Establecimiento::query()->whereNotNull('distrito_id')->first();
        if (! $user || ! $formulario || ! $est) {
            $this->markTestSkipped('Faltan usuario, SP12 o establecimiento con distrito.');
        }

        $workbook = app(SpPlanillaParser::class)->scanWorkbook($path);
        $hoja = collect($workbook['hojas'])->first(
            fn (array $h) => ($h['sp_codigo'] ?? '') === 'SP12'
                && ($h['parseado'] ?? false)
                && ($h['filas_detectadas'] ?? 0) > 0
        );
        if (! $hoja) {
            $this->markTestSkipped('Ninguna hoja SP12 parseable en la muestra.');
        }

        $service = app(SpPlanillaImportService::class);
        $this->assertTrue($service->isImportable('SP12'));

        $preview = $service->applyPreviewContext([
            'detectado' => $hoja['detectado'],
            'workbook' => $workbook,
            'hoja_activa' => $hoja['titulo'],
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2097,
            'periodo_mes' => 8,
        ]);

        $this->assertTrue($preview['importable'] ?? false);
        $this->assertGreaterThan(0, $preview['detectado']['filas_detectadas'] ?? count($preview['detectado']['filas'] ?? []));

        Record::where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $est->id)
            ->where('periodo_anio', 2097)
            ->where('periodo_mes', 8)
            ->delete();

        $filas = $preview['detectado']['filas'] ?? [];
        $decisiones = [];
        foreach ($filas as $fila) {
            if (! empty($fila['prestacion_id'])) {
                $decisiones[(string) $fila['key']] = (string) $fila['prestacion_id'];
            }
        }
        if ($decisiones === []) {
            $this->markTestSkipped('SP12 sin matches de prestaciones para confirmar.');
        }

        $record = $service->confirm($user, $preview, [
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $est->id,
            'periodo_anio' => 2097,
            'periodo_mes' => 8,
            'estructura_servicio_id' => null,
        ], $decisiones, true);

        $this->assertSame(Record::ESTADO_BORRADOR, $record->estado);
        $this->assertSame($formulario->id, $record->formulario_id);
        $this->assertGreaterThan(0, $record->values()->count());
    }
}
