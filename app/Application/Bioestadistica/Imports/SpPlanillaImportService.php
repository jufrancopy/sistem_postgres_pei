<?php

namespace App\Application\Bioestadistica\Imports;

use App\Application\Bioestadistica\Capture\CaptureScopeService;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoServicio;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SpPlanillaImportService
{
    public const SESSION_KEY = 'bio_sp_planilla_preview';

    /** Formularios con importación de datos implementada (parser disponible). */
    public const IMPORTABLE = [
        'SP1', 'SP2', 'SP3', 'SP4', 'SP5', 'SP6', 'SP7', 'SP8', 'SP9',
        'SP10', 'SP11', 'SP12', 'SP13', 'SP14',
    ];

    /**
     * SP con varias tablas y planilla plana: matching contra la unión de ítems
     * y confirm reparte cada fila al campo dueño de la prestación.
     */
    private const DISTRIBUTED_TABULAR = ['SP2', 'SP7', 'SP12', 'SP13'];

    /** Field codes SP1 usados en import (carga manual no cambia). */
    public const SP1_CONSULTA_FIELD = 'consultas_por_especialidad';

    public const SP1_CONVENIO_FIELD = 'var_1_convenio_consultas_medicas';

    /** Dominio del diccionario por SP (matching asistido). */
    private const DOMAINS = [
        'SP1' => '1', 'SP2' => '13', 'SP3' => '12', 'SP4' => '11', 'SP5' => '10',
        'SP6' => '14', 'SP7' => '14', 'SP8' => '16', 'SP9' => '4',
        'SP12' => '17', 'SP13' => '17', 'SP14' => 'x',
    ];

    public function __construct(
        private SpPlanillaParser $parser,
        private PrestacionMatcher $matcher,
        private RecordCaptureService $capture,
        private HospEpisodioImporter $hospImporter
    ) {
    }

    /**
     * @return array<int, Formulario>
     */
    public function selectableFormularios()
    {
        return Formulario::query()
            ->where('estado', 'activo')
            ->whereIn('layout_type', ['tabular', 'matriz'])
            ->whereNotIn('codigo', ['SP10'])
            ->orderBy('codigo')
            ->get();
    }

    public function isImportable(string $codigo): bool
    {
        return in_array($codigo, self::IMPORTABLE, true);
    }

    /**
     * @return array<string, mixed>
     */
    public function analyze(UploadedFile $file, User $user): array
    {
        $workbook = $this->parser->scanWorkbook($file->getRealPath());
        $workbook = $this->enrichWorkbookSummary($workbook);

        $defaultSheet = $this->defaultSheet($workbook);
        if (! $defaultSheet) {
            throw ValidationException::withMessages([
                'archivo' => 'No se encontró ninguna hoja en el archivo.',
            ]);
        }

        $token = Str::random(40);
        $tempPath = $this->persistUpload($file, $token);

        $parsed = is_array($defaultSheet['detectado'] ?? null) ? $defaultSheet['detectado'] : null;
        $detectedCodigo = $parsed['formulario_codigo']
            ?? $defaultSheet['sp_codigo']
            ?? 'SP1';
        $contexto = $workbook['contexto'] ?? [];
        $codigoPlanilla = $parsed['codigo_planilla'] ?? $contexto['codigo_planilla'] ?? $defaultSheet['codigo_planilla'] ?? null;

        $formulario = Formulario::where('codigo', $detectedCodigo)->where('estado', 'activo')->first()
            ?? Formulario::where('codigo', 'SP1')->where('estado', 'activo')->first();

        $establecimiento = $this->resolveEstablecimiento($codigoPlanilla);
        $this->assertUserCanCapture($user, $establecimiento?->id, $formulario);

        $preview = [
            'token' => $token,
            'archivo' => $file->getClientOriginalName(),
            'temp_path' => $tempPath,
            'workbook' => $workbook,
            'hoja_activa' => $defaultSheet['titulo'],
            'detectado' => $parsed ?? [
                'formulario_codigo' => $detectedCodigo,
                'hoja' => $defaultSheet['titulo'],
                'filas' => [],
                'filas_detectadas' => 0,
                'advertencias' => [$defaultSheet['error'] ?? 'Hoja sin datos parseados. Use Ajustar mapeo.'],
            ],
            'formulario_codigo_detectado' => $detectedCodigo,
            'formulario_id' => $formulario?->id,
            'establecimiento_id' => $establecimiento?->id,
            'periodo_anio' => $parsed['periodo_anio'] ?? $contexto['periodo_anio'] ?? $defaultSheet['periodo_anio'] ?? null,
            'periodo_mes' => $parsed['periodo_mes'] ?? $contexto['periodo_mes'] ?? $defaultSheet['periodo_mes'] ?? null,
            'estructura_servicio_id' => null,
            'mapeos' => [],
        ];

        return $this->applyPreviewContext($preview);
    }

    /**
     * @param  array<string, mixed>  $preview
     * @return array<string, mixed>
     */
    public function activateSheet(array $preview, string $sheetTitle): array
    {
        $hoja = $this->findSheet($preview, $sheetTitle);
        if (! $hoja || ! is_array($hoja['detectado'] ?? null)) {
            throw ValidationException::withMessages([
                'hoja' => 'La hoja seleccionada no tiene datos parseados disponibles.',
            ]);
        }

        $detectedCodigo = $hoja['detectado']['formulario_codigo'] ?? $hoja['sp_codigo'];
        $formulario = Formulario::where('codigo', $detectedCodigo)->where('estado', 'activo')->first();

        $preview['hoja_activa'] = $hoja['titulo'];
        $preview['detectado'] = $hoja['detectado'];
        $preview['formulario_codigo_detectado'] = $detectedCodigo;
        $preview['formulario_id'] = $formulario?->id;

        return $this->applyPreviewContext($preview);
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public function applyPreviewContext(array $preview, array $overrides = []): array
    {
        $formularioId = (int) ($overrides['formulario_id'] ?? $preview['formulario_id'] ?? 0);
        $establecimientoId = (int) ($overrides['establecimiento_id'] ?? $preview['establecimiento_id'] ?? 0);
        $contexto = $preview['workbook']['contexto'] ?? [];
        $detectado = $preview['detectado'] ?? [];
        $periodoAnio = (int) ($overrides['periodo_anio'] ?? $preview['periodo_anio'] ?? $detectado['periodo_anio'] ?? $contexto['periodo_anio'] ?? 0);
        $periodoMes = (int) ($overrides['periodo_mes'] ?? $preview['periodo_mes'] ?? $detectado['periodo_mes'] ?? $contexto['periodo_mes'] ?? 0);
        $servicioId = $overrides['estructura_servicio_id'] ?? $preview['estructura_servicio_id'] ?? null;

        $formulario = $formularioId ? Formulario::find($formularioId) : null;
        $establecimiento = $establecimientoId ? Establecimiento::find($establecimientoId) : null;

        $parsed = $preview['detectado'];
        $layout = $this->detectLayout($parsed);
        $field = null;
        $filas = [];
        $validIds = [];
        $domain = $formulario ? (self::DOMAINS[$formulario->codigo] ?? null) : null;

        if ($layout === 'tabular') {
            if ($formulario?->codigo === 'SP9') {
                $fieldsByCode = $this->tablaFieldsByCode($formulario);
                $field = $fieldsByCode->first();
                $filas = $this->rematchSp9Filas($parsed['filas'] ?? [], self::DOMAINS['SP9'], $fieldsByCode);
            } elseif ($formulario?->codigo === 'SP1') {
                $consulta = $this->fieldByCode($formulario, self::SP1_CONSULTA_FIELD) ?? $this->primaryTablaField($formulario);
                $convenio = $this->fieldByCode($formulario, self::SP1_CONVENIO_FIELD);
                $field = $consulta;
                $validIds = collect([$consulta, $convenio])
                    ->filter()
                    ->flatMap(fn (Field $f) => $f->rowItems()->pluck('id'))
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();
                $filas = $this->rematchFilas($parsed['filas'] ?? [], $domain, $validIds);
            } elseif ($formulario && $this->usesDistributedTabular($formulario->codigo)) {
                $fieldsByCode = $this->tablaFieldsByCode($formulario);
                $field = $fieldsByCode->first();
                $validIds = $fieldsByCode
                    ->flatMap(fn (Field $f) => $f->rowItems()->pluck('id'))
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();
                $filas = $this->rematchFilas($parsed['filas'] ?? [], $domain, $validIds);
            } else {
                $field = $formulario ? $this->primaryTablaField($formulario) : null;
                $validIds = $field ? $field->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all() : [];
                $filas = $this->rematchFilas($parsed['filas'] ?? [], $domain, $validIds);
            }
            $parsed['filas'] = $filas;
            $parsed['prestaciones_enlazadas'] = count(array_filter($filas, fn (array $r) => $r['prestacion_id'] !== null));
            $parsed['prestaciones_sin_match'] = count(array_filter($filas, fn (array $r) => $r['prestacion_id'] === null));
        } elseif ($layout === 'matriz') {
            $field = $formulario ? $this->primaryMatrizField($formulario) : null;
            $matrixRows = $parsed['matriz']['rows'] ?? [];
            $parsed['prestaciones_enlazadas'] = count($matrixRows);
            $parsed['prestaciones_sin_match'] = 0;
        } elseif ($layout === 'nominativo') {
            $episodios = $parsed['episodios'] ?? [];
            $parsed['prestaciones_enlazadas'] = count($episodios);
            $parsed['prestaciones_sin_match'] = 0;
        }
        $parsed['layout'] = $layout;

        $cortes = $establecimiento ? $this->cortesForEstablecimiento((int) $establecimiento->id) : [];

        $preview['detectado'] = $parsed;
        $preview['formulario_id'] = $formulario?->id;
        $preview['formulario_codigo'] = $formulario?->codigo;
        $preview['formulario_nombre'] = $formulario?->nombre;
        $preview['importable'] = $formulario ? $this->isImportable($formulario->codigo) : false;
        $preview['layout'] = $layout;
        $preview['field_code'] = $field?->code;
        $preview['establecimiento_id'] = $establecimiento?->id;
        if ($establecimiento) {
            $establecimiento->loadMissing('distrito.departamento');
        }
        $preview['establecimiento'] = $establecimiento ? [
            'id' => $establecimiento->id,
            'codigo' => $establecimiento->codigo,
            'codigo_sih' => $establecimiento->codigo_sih,
            'nombre' => $establecimiento->nombre,
            'distrito_ok' => $establecimiento->distrito_id !== null,
            'distrito' => $establecimiento->distrito?->nombre,
            'departamento' => $establecimiento->distrito?->departamento?->nombre,
        ] : null;
        $preview['periodo_anio'] = $periodoAnio ?: null;
        $preview['periodo_mes'] = $periodoMes ?: null;
        $preview['tiene_servicios'] = count($cortes) > 0;
        $preview['requiere_corte'] = count($cortes) > 0;
        $preview['cortes'] = $cortes;
        $preview['estructura_servicio_id'] = $servicioId;
        $preview['record_existente'] = null;

        if ($formulario && $establecimiento && $periodoAnio && $periodoMes) {
            $lookup = [
                'formulario_id' => $formulario->id,
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => $periodoAnio,
                'periodo_mes' => $periodoMes,
                'estructura_departamento_id' => null,
                'estructura_servicio_id' => null,
            ];
            if ($servicioId && $preview['requiere_corte']) {
                try {
                    $lookup = $this->applyCorte($lookup, (int) $establecimiento->id, $servicioId);
                } catch (ValidationException) {
                    // Vista previa: servicio aún no elegido.
                }
            }
            $record = Record::where($lookup)->first();
            if ($record) {
                $preview['record_existente'] = [
                    'id' => $record->id,
                    'estado' => $record->estado,
                    'editable' => $record->isEditable(),
                ];
            }
        }

        return $preview;
    }

    public function storePreview(array $preview): void
    {
        session([self::SESSION_KEY => $preview]);
    }

    public function pullPreview(?string $token): ?array
    {
        $preview = session(self::SESSION_KEY);
        if (! is_array($preview) || ($preview['token'] ?? null) !== $token) {
            return null;
        }

        return $preview;
    }

    public function forgetPreview(): void
    {
        $preview = session(self::SESSION_KEY);
        if (is_array($preview)) {
            $this->forgetTempFile($preview['temp_path'] ?? null);
        }
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Datos para la pantalla propia del asistente de mapeo.
     *
     * @param  array<string, mixed>  $preview
     * @return array<string, mixed>
     */
    public function mappingFormData(array $preview, string $sheetTitle, ?int $sheetIndex = null): array
    {
        $resolved = $this->resolveSheet($preview, $sheetTitle, $sheetIndex);
        if (! $resolved) {
            throw ValidationException::withMessages([
                'hoja' => 'No se encontró la hoja solicitada en el análisis.',
            ]);
        }
        [$hoja, $sheetTitle] = $resolved;

        $tempPath = $preview['temp_path'] ?? null;
        if (! is_string($tempPath) || ! is_file($tempPath)) {
            throw ValidationException::withMessages([
                'archivo' => 'El archivo temporal expiró. Vuelva a analizar la planilla.',
            ]);
        }

        $mapeoGuardado = $preview['mapeos'][$sheetTitle] ?? [];
        $spCodigo = $mapeoGuardado['formulario_codigo']
            ?? $hoja['sp_codigo']
            ?? $hoja['detectado']['formulario_codigo']
            ?? null;

        try {
            $grid = $this->parser->sheetGridFromFile($tempPath, $sheetTitle);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'hoja' => 'No se pudo leer la hoja «'.$sheetTitle.'»: '.$exception->getMessage(),
            ]);
        }
        $roles = $spCodigo ? $this->mappingRolesForSp($spCodigo) : $this->mappingRolesForSp('SP1');

        $defaults = [
            'formulario_codigo' => $spCodigo,
            'fila_encabezado' => $mapeoGuardado['fila_encabezado']
                ?? $hoja['detectado']['fila_encabezado']
                ?? null,
            'columnas' => $mapeoGuardado['columnas'] ?? [],
        ];

        return [
            'hoja' => $hoja,
            'sheet_title' => $sheetTitle,
            'sheet_index' => $sheetIndex ?? $this->sheetIndexOf($preview, $sheetTitle),
            'grid' => $grid,
            'roles' => $roles,
            'defaults' => $defaults,
            'mapeables' => $this->mappableSpCodes(),
            'calidad' => $hoja['calidad'] ?? null,
        ];
    }

    /**
     * Aplica mapeo manual, reparsea la hoja y actualiza el preview en sesión.
     *
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function applySheetMapping(array $preview, string $sheetTitle, array $input): array
    {
        $sheetIndex = isset($input['hoja_idx']) ? (int) $input['hoja_idx'] : null;
        $resolved = $this->resolveSheet($preview, $sheetTitle, $sheetIndex);
        if (! $resolved) {
            throw ValidationException::withMessages([
                'hoja' => 'No se encontró la hoja solicitada.',
            ]);
        }
        [$hoja, $sheetTitle] = $resolved;

        $tempPath = $preview['temp_path'] ?? null;
        if (! is_string($tempPath) || ! is_file($tempPath)) {
            throw ValidationException::withMessages([
                'archivo' => 'El archivo temporal expiró. Vuelva a analizar la planilla.',
            ]);
        }

        $spCodigo = strtoupper(trim((string) ($input['formulario_codigo'] ?? '')));
        if (! in_array($spCodigo, $this->mappableSpCodes(), true)) {
            throw ValidationException::withMessages([
                'formulario_codigo' => 'En esta versión el asistente admite SP1 a SP9 (excepto SP10 nominativo).',
            ]);
        }

        $overrides = [
            'fila_encabezado' => (int) ($input['fila_encabezado'] ?? 0) ?: null,
            'columnas' => array_filter(
                is_array($input['columnas'] ?? null) ? $input['columnas'] : [],
                fn ($v) => $v !== null && $v !== ''
            ),
        ];
        if (! $overrides['fila_encabezado']) {
            unset($overrides['fila_encabezado']);
        }

        try {
            $detectado = $this->parser->parseSheetFromFile($tempPath, $sheetTitle, $spCodigo, $overrides);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'mapeo' => $exception->getMessage(),
            ]);
        }

        $formulario = Formulario::where('codigo', $spCodigo)->where('estado', 'activo')->first();
        if (! $formulario) {
            throw ValidationException::withMessages([
                'formulario_codigo' => 'No existe un formulario activo '.$spCodigo.'.',
            ]);
        }

        $preview['mapeos'][$sheetTitle] = [
            'formulario_codigo' => $spCodigo,
            'fila_encabezado' => $detectado['fila_encabezado'] ?? ($overrides['fila_encabezado'] ?? null),
            'columnas' => $overrides['columnas'] ?? [],
        ];

        $hojas = [];
        foreach ($preview['workbook']['hojas'] ?? [] as $entry) {
            if ($this->normalizeSheetTitle((string) ($entry['titulo'] ?? '')) !== $this->normalizeSheetTitle($sheetTitle)) {
                $hojas[] = $entry;

                continue;
            }
            $entry['sp_codigo'] = $spCodigo;
            $entry['detectado'] = $detectado;
            $entry['filas_detectadas'] = (int) ($detectado['filas_detectadas'] ?? 0);
            $entry['parseado'] = true;
            $entry['error'] = null;
            $entry['parser_disponible'] = true;
            $entry['importable'] = $this->isImportable($spCodigo);
            $entry['advertencia_hoja'] = 'SP asignado manualmente vía asistente de mapeo.';
            $entry['calidad'] = $this->computeCalidad($entry, $preview);
            $hojas[] = $entry;
        }
        $preview['workbook']['hojas'] = $hojas;

        $context = array_filter([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $input['establecimiento_id'] ?? $preview['establecimiento_id'] ?? null,
            'periodo_anio' => $input['periodo_anio'] ?? $preview['periodo_anio'] ?? null,
            'periodo_mes' => $input['periodo_mes'] ?? $preview['periodo_mes'] ?? null,
            'estructura_servicio_id' => $input['estructura_servicio_id'] ?? $preview['estructura_servicio_id'] ?? null,
        ], fn ($v) => $v !== null && $v !== '');

        $preview = $this->activateSheet($preview, $sheetTitle);
        $preview = $this->applyPreviewContext($preview, $context);

        return $preview;
    }

    private function sheetIndexOf(array $preview, string $sheetTitle): ?int
    {
        foreach (array_values($preview['workbook']['hojas'] ?? []) as $index => $hoja) {
            if ($this->normalizeSheetTitle((string) ($hoja['titulo'] ?? '')) === $this->normalizeSheetTitle($sheetTitle)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    public function mappableSpCodes(): array
    {
        return [
            'SP1', 'SP2', 'SP3', 'SP4', 'SP5', 'SP6', 'SP7', 'SP8', 'SP9',
            'SP12', 'SP13', 'SP14',
        ];
    }

    /**
     * @return array<int, array{key: string, label: string, required: bool}>
     */
    public function mappingRolesForSp(string $codigo): array
    {
        return match ($codigo) {
            'SP1' => [
                ['key' => 'label', 'label' => 'Prestación / especialidad', 'required' => true],
                ['key' => 'total_consultas', 'label' => 'Total consultas (modo 1 columna)', 'required' => false],
                ['key' => 'ips', 'label' => 'IPS (modo 2 columnas)', 'required' => false],
                ['key' => 'convenio', 'label' => 'Convenio (modo 2 columnas)', 'required' => false],
                ['key' => 'cod', 'label' => 'Código (opcional)', 'required' => false],
            ],
            'SP2', 'SP5', 'SP6', 'SP12', 'SP13', 'SP14' => [
                ['key' => 'label', 'label' => 'Prestación / etiqueta', 'required' => true],
                ['key' => 'total', 'label' => 'Total', 'required' => true],
                ['key' => 'cod', 'label' => 'Código (opcional)', 'required' => false],
            ],
            'SP3', 'SP4', 'SP7' => [
                ['key' => 'label', 'label' => 'Prestación / etiqueta', 'required' => true],
                ['key' => 'pacientes', 'label' => 'Pacientes', 'required' => false],
                ['key' => 'estudios', 'label' => 'Estudios', 'required' => false],
                ['key' => 'prestaciones', 'label' => 'Prestaciones', 'required' => false],
                ['key' => 'determinaciones', 'label' => 'Determinaciones', 'required' => false],
            ],
            'SP8' => [
                ['key' => 'label', 'label' => 'Vacuna / etiqueta', 'required' => true],
                ['key' => 'cod', 'label' => 'Código (opcional)', 'required' => false],
            ],
            'SP9' => [
                ['key' => 'label', 'label' => 'Especialidad / urgencia', 'required' => true],
                ['key' => 'consultas', 'label' => 'Consultas', 'required' => false],
                ['key' => 'observacion', 'label' => 'Observación', 'required' => false],
                ['key' => 'procedimiento', 'label' => 'Procedimiento', 'required' => false],
                ['key' => 'total', 'label' => 'Total', 'required' => false],
            ],
            default => [
                ['key' => 'label', 'label' => 'Prestación / etiqueta', 'required' => true],
                ['key' => 'total', 'label' => 'Total', 'required' => true],
            ],
        };
    }

    public function usesDistributedTabular(string $codigo): bool
    {
        return in_array($codigo, self::DISTRIBUTED_TABULAR, true);
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $context
     * @param  array<string, string>  $decisiones
     */
    public function confirm(User $user, array $preview, array $context, array $decisiones, bool $sobrescribir): Record
    {
        $formulario = Formulario::whereKey($context['formulario_id'] ?? 0)
            ->where('estado', 'activo')
            ->firstOrFail();

        if (! $this->isImportable($formulario->codigo)) {
            throw ValidationException::withMessages([
                'formulario_id' => 'La importación de datos para '.$formulario->codigo.' aún no está disponible.',
            ]);
        }

        return match ($this->detectLayout($preview['detectado'] ?? [])) {
            'matriz' => $this->confirmMatrix($user, $preview, $context, $sobrescribir, $formulario),
            'nominativo' => $this->confirmNominativo($user, $preview, $context, $sobrescribir, $formulario),
            default => $this->confirmTabular($user, $preview, $context, $decisiones, $sobrescribir, $formulario),
        };
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $context
     * @param  array<string, string>  $decisiones
     */
    private function confirmTabular(
        User $user,
        array $preview,
        array $context,
        array $decisiones,
        bool $sobrescribir,
        Formulario $formulario
    ): Record {
        $establecimientoId = (int) $context['establecimiento_id'];
        $this->assertUserCanCapture($user, $establecimientoId, $formulario);
        $this->assertEstablecimientoConDistrito($establecimientoId);

        $lookup = $this->buildRecordLookup($formulario, $context, $establecimientoId);

        if ($formulario->codigo === 'SP9') {
            return $this->confirmSp9Tabular($user, $preview, $lookup, $decisiones, $sobrescribir, $formulario);
        }

        if ($formulario->codigo === 'SP1') {
            return $this->confirmSp1Tabular($user, $preview, $lookup, $decisiones, $sobrescribir, $formulario);
        }

        if ($this->usesDistributedTabular($formulario->codigo)) {
            return $this->confirmDistributedTabular($user, $preview, $lookup, $decisiones, $sobrescribir, $formulario);
        }

        $field = $this->primaryTablaField($formulario);
        if (! $field) {
            throw ValidationException::withMessages([
                'formulario_id' => 'El formulario seleccionado no tiene un campo tabular importable.',
            ]);
        }

        $validIds = $field->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $rows = $this->buildRows($preview['detectado']['filas'] ?? [], $decisiones, $validIds, $field);
        if ($rows === []) {
            throw ValidationException::withMessages([
                'importacion' => 'No hay filas válidas para importar. Revise el matching de prestaciones.',
            ]);
        }

        $values = [$field->code => ['rows' => $rows]];

        $record = $this->resolveEditableRecord($lookup, $sobrescribir);
        if (! $record) {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        $this->capture->save($record, $values, false, true, $user->id);
        $this->stampImportOrigin($record, $preview);

        return $record->fresh(['formulario', 'establecimiento']);
    }

    /**
     * Confirma SP1: modo total → bloque consulta; modo IPS/CONVENIO → consulta + convenio.
     *
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $lookup
     * @param  array<string, string>  $decisiones
     */
    private function confirmSp1Tabular(
        User $user,
        array $preview,
        array $lookup,
        array $decisiones,
        bool $sobrescribir,
        Formulario $formulario
    ): Record {
        $consultaField = $this->fieldByCode($formulario, self::SP1_CONSULTA_FIELD)
            ?? $this->primaryTablaField($formulario);
        $convenioField = $this->fieldByCode($formulario, self::SP1_CONVENIO_FIELD);

        if (! $consultaField) {
            throw ValidationException::withMessages([
                'formulario_id' => 'El formulario SP1 no tiene el bloque de consultas por especialidad.',
            ]);
        }

        $consultaValid = $consultaField->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $convenioValid = $convenioField
            ? $convenioField->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];
        $consultaFlip = array_flip($consultaValid);
        $convenioFlip = array_flip($convenioValid);
        $domain = self::DOMAINS['SP1'];

        $consultaRows = [];
        $convenioRows = [];

        foreach ($preview['detectado']['filas'] ?? [] as $fila) {
            $key = (string) ($fila['key'] ?? '');
            $decision = $decisiones[$key] ?? null;
            if ($decision === 'discard') {
                continue;
            }

            $label = (string) ($fila['prestacion_label'] ?? $fila['especialidad'] ?? '');
            $metricas = $fila['metricas'] ?? [];
            if ($metricas === [] && isset($fila['total_consultas'])) {
                $metricas = ['total_consultas' => (int) $fila['total_consultas']];
            }

            $hasSplit = isset($metricas['ips']) || isset($metricas['convenio']);

            if ($hasSplit) {
                $ips = (int) ($metricas['ips'] ?? 0);
                $convenio = (int) ($metricas['convenio'] ?? 0);

                if ($ips > 0) {
                    $id = $this->resolveSp1ItemId($decision, $fila, $label, $domain, $consultaFlip, $consultaValid);
                    if ($id) {
                        $consultaRows[(string) $id] = ['total_consultas' => $ips];
                    }
                }
                if ($convenio > 0 && $convenioField) {
                    // Convenio siempre se rematchea a su propio catálogo (puede diferir del de consulta).
                    $id = $this->resolveSp1ItemId(null, $fila, $label, $domain, $convenioFlip, $convenioValid);
                    if ($id) {
                        $convenioRows[(string) $id] = ['total_consultas' => $convenio];
                    }
                }
                continue;
            }

            $total = (int) ($metricas['total_consultas'] ?? $metricas['total'] ?? 0);
            if ($total <= 0) {
                continue;
            }
            $id = $this->resolveSp1ItemId($decision, $fila, $label, $domain, $consultaFlip, $consultaValid);
            if ($id) {
                $consultaRows[(string) $id] = ['total_consultas' => $total];
            }
        }

        $values = [];
        if ($consultaRows !== []) {
            $values[$consultaField->code] = ['rows' => $consultaRows];
        }
        if ($convenioRows !== [] && $convenioField) {
            $values[$convenioField->code] = ['rows' => $convenioRows];
        }

        if ($values === []) {
            throw ValidationException::withMessages([
                'importacion' => 'No hay filas válidas para importar en SP1. Revise el matching de especialidades.',
            ]);
        }

        $record = $this->resolveEditableRecord($lookup, $sobrescribir);
        if (! $record) {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        // Borrador no estricto: el bloque convenio puede quedar vacío en modo 1 columna.
        $this->capture->save($record, $values, false, false, $user->id);
        $this->stampImportOrigin($record, $preview);

        return $record->fresh(['formulario', 'establecimiento']);
    }

    /**
     * @param  array<string, mixed>  $fila
     * @param  array<int, int>  $validFlip
     * @param  array<int, int>  $validIds
     */
    private function resolveSp1ItemId(
        mixed $decision,
        array $fila,
        string $label,
        string $domain,
        array $validFlip,
        array $validIds
    ): ?int {
        if (is_numeric($decision)) {
            $id = (int) $decision;
            if (isset($validFlip[$id])) {
                return $id;
            }
        }
        if (! empty($fila['prestacion_id']) && isset($validFlip[(int) $fila['prestacion_id']])) {
            return (int) $fila['prestacion_id'];
        }
        if ($label === '' || $validIds === []) {
            return null;
        }
        $matched = $this->rematchFila([
            'prestacion_label' => $label,
            'especialidad' => $label,
            'key' => $fila['key'] ?? 'tmp',
        ], $domain, $validIds);

        return ! empty($matched['prestacion_id']) ? (int) $matched['prestacion_id'] : null;
    }

    private function fieldByCode(Formulario $formulario, string $code): ?Field
    {
        return $this->tablaFieldsByCode($formulario)->get($code);
    }

    /**
     * Confirma planillas planas (SP2/SP7/SP12/SP13) repartiendo filas entre tablas del formulario.
     *
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $lookup
     * @param  array<string, string>  $decisiones
     */
    private function confirmDistributedTabular(
        User $user,
        array $preview,
        array $lookup,
        array $decisiones,
        bool $sobrescribir,
        Formulario $formulario
    ): Record {
        $fieldsByCode = $this->tablaFieldsByCode($formulario);
        if ($fieldsByCode->isEmpty()) {
            throw ValidationException::withMessages([
                'formulario_id' => 'El formulario seleccionado no tiene un campo tabular importable.',
            ]);
        }

        $filas = $preview['detectado']['filas'] ?? [];
        $values = [];
        foreach ($fieldsByCode as $field) {
            $validIds = $field->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all();
            $rows = $this->buildRows($filas, $decisiones, $validIds, $field);
            if ($rows !== []) {
                $values[$field->code] = ['rows' => $rows];
            }
        }

        if ($values === []) {
            throw ValidationException::withMessages([
                'importacion' => 'No hay filas válidas para importar. Revise el matching de prestaciones.',
            ]);
        }

        $record = $this->resolveEditableRecord($lookup, $sobrescribir);
        if (! $record) {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        // Borrador no estricto: la planilla plana suele cubrir solo parte de las tablas del SP.
        $this->capture->save($record, $values, false, false, $user->id);
        $this->stampImportOrigin($record, $preview);

        return $record->fresh(['formulario', 'establecimiento']);
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $lookup
     * @param  array<string, string>  $decisiones
     */
    private function confirmSp9Tabular(
        User $user,
        array $preview,
        array $lookup,
        array $decisiones,
        bool $sobrescribir,
        Formulario $formulario
    ): Record {
        $fieldsByCode = $this->tablaFieldsByCode($formulario);
        $filas = $preview['detectado']['filas'] ?? [];
        $values = [];

        foreach ($fieldsByCode as $field) {
            $fieldFilas = array_values(array_filter(
                $filas,
                fn (array $fila) => ($fila['field_code'] ?? '') === $field->code
            ));
            if ($fieldFilas === []) {
                continue;
            }

            $validIds = $field->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all();
            $rows = $this->buildRows($fieldFilas, $decisiones, $validIds, $field);
            if ($rows !== []) {
                $values[$field->code] = ['rows' => $rows];
            }
        }

        if ($values === []) {
            throw ValidationException::withMessages([
                'importacion' => 'No hay filas válidas para importar en SP9. Revise el matching de prestaciones.',
            ]);
        }

        $record = $this->resolveEditableRecord($lookup, $sobrescribir);
        if (! $record) {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        $this->capture->save($record, $values, false, true, $user->id);
        $this->stampImportOrigin($record, $preview);

        return $record->fresh(['formulario', 'establecimiento']);
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $context
     */
    private function confirmMatrix(
        User $user,
        array $preview,
        array $context,
        bool $sobrescribir,
        Formulario $formulario
    ): Record {
        $establecimientoId = (int) $context['establecimiento_id'];
        $this->assertUserCanCapture($user, $establecimientoId, $formulario);
        $this->assertEstablecimientoConDistrito($establecimientoId);

        $lookup = $this->buildRecordLookup($formulario, $context, $establecimientoId);
        $field = $this->primaryMatrizField($formulario);
        if (! $field) {
            throw ValidationException::withMessages([
                'formulario_id' => 'El formulario seleccionado no tiene un campo matriz importable.',
            ]);
        }

        $matrixRows = $preview['detectado']['matriz']['rows'] ?? [];
        if ($matrixRows === []) {
            throw ValidationException::withMessages([
                'importacion' => 'No hay filas de matriz válidas para importar.',
            ]);
        }

        $periodoAnio = (int) $context['periodo_anio'];
        $periodoMes = (int) $context['periodo_mes'];
        $values = [
            $field->code => Sp11Matrix::normalize(['rows' => $matrixRows], $periodoAnio, $periodoMes, false),
        ];

        $record = $this->resolveEditableRecord($lookup, $sobrescribir);
        if (! $record) {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        $this->capture->save($record, $values, false, true, $user->id);
        $this->stampImportOrigin($record, $preview);

        return $record->fresh(['formulario', 'establecimiento']);
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $context
     */
    private function confirmNominativo(
        User $user,
        array $preview,
        array $context,
        bool $sobrescribir,
        Formulario $formulario
    ): Record {
        $establecimientoId = (int) $context['establecimiento_id'];
        $this->assertUserCanCapture($user, $establecimientoId, $formulario);
        $this->assertEstablecimientoConDistrito($establecimientoId);

        $episodios = $preview['detectado']['episodios'] ?? [];
        if ($episodios === []) {
            throw ValidationException::withMessages([
                'importacion' => 'No hay episodios hospitalarios válidos para importar.',
            ]);
        }

        $lookup = $this->buildRecordLookup($formulario, $context, $establecimientoId);
        $existing = Record::where($lookup)->first();
        if ($existing && ! $existing->isEditable()) {
            throw ValidationException::withMessages([
                'importacion' => 'Ya existe un registro enviado o aprobado para este SP, establecimiento y período.',
            ]);
        }
        if ($existing && ! $sobrescribir) {
            throw ValidationException::withMessages([
                'importacion' => 'Ya existe un borrador. Marque «Sobrescribir borrador» para reemplazar los valores.',
            ]);
        }

        $result = $this->hospImporter->importEpisodes(
            $episodios,
            $user,
            $establecimientoId,
            (int) $context['periodo_anio'],
            (int) $context['periodo_mes'],
            $sobrescribir
        );

        if (! $result['record']) {
            throw ValidationException::withMessages([
                'importacion' => 'No se pudo consolidar el registro SP10 desde los episodios importados.',
            ]);
        }

        $this->stampImportOrigin($result['record'], $preview);

        return $result['record']->fresh(['formulario', 'establecimiento']);
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private function buildRecordLookup(Formulario $formulario, array $context, int $establecimientoId): array
    {
        $lookup = [
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimientoId,
            'periodo_anio' => (int) $context['periodo_anio'],
            'periodo_mes' => (int) $context['periodo_mes'],
            'estructura_departamento_id' => null,
            'estructura_servicio_id' => null,
        ];

        return $this->applyCorte($lookup, $establecimientoId, $context['estructura_servicio_id'] ?? null);
    }

    /**
     * @param  array<string, mixed>  $lookup
     */
    private function resolveEditableRecord(array $lookup, bool $sobrescribir): ?Record
    {
        $record = Record::where($lookup)->first();
        if ($record && ! $record->isEditable()) {
            throw ValidationException::withMessages([
                'importacion' => 'Ya existe un registro enviado o aprobado para este SP, establecimiento y período.',
            ]);
        }
        if ($record && ! $sobrescribir) {
            throw ValidationException::withMessages([
                'importacion' => 'Ya existe un borrador. Marque «Sobrescribir borrador» para reemplazar los valores.',
            ]);
        }

        return $record;
    }

    private function assertEstablecimientoConDistrito(int $establecimientoId): void
    {
        abort_unless(
            Establecimiento::whereKey($establecimientoId)->whereNotNull('distrito_id')->exists(),
            422,
            'El establecimiento debe tener distrito asignado.'
        );
    }

    /**
     * @param  array<string, mixed>  $detectado
     */
    private function detectLayout(array $detectado): string
    {
        $layout = $detectado['layout'] ?? 'tabular';

        return in_array($layout, ['tabular', 'matriz', 'nominativo'], true) ? $layout : 'tabular';
    }

    private function primaryMatrizField(Formulario $formulario): ?Field
    {
        return Field::query()
            ->where('type', 'matriz')
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $formulario->id))
            ->orderByDesc('required')
            ->orderBy('orden')
            ->first();
    }

    /**
     * @param  array<string, mixed>  $preview
     * @param  array<string, mixed>  $context
     * @param  array<int, string>  $sheetTitles
     * @return array{ok: array<int, array<string, mixed>>, omitidos: array<int, array<string, string>>, errores: array<int, array<string, string>>}
     */
    public function confirmBatch(User $user, array $preview, array $context, array $sheetTitles, bool $sobrescribir): array
    {
        $resultado = ['ok' => [], 'omitidos' => [], 'errores' => []];
        $procesadosSp = [];

        foreach ($sheetTitles as $sheetTitle) {
            $sheetTitle = trim($sheetTitle);
            if ($sheetTitle === '') {
                continue;
            }

            $hoja = $this->findSheet($preview, $sheetTitle);
            if (! $hoja) {
                $resultado['errores'][] = [
                    'hoja' => $sheetTitle,
                    'mensaje' => 'Hoja no encontrada en el análisis.',
                ];

                continue;
            }

            $spCodigo = $hoja['sp_codigo'] ?? $hoja['detectado']['formulario_codigo'] ?? '?';
            if (isset($procesadosSp[$spCodigo])) {
                $resultado['omitidos'][] = [
                    'hoja' => $sheetTitle,
                    'sp' => $spCodigo,
                    'mensaje' => 'Ya se importó '.$spCodigo.' desde otra hoja seleccionada.',
                ];

                continue;
            }

            try {
                $sheetPreview = $this->applyPreviewContext(
                    $this->activateSheet($preview, $sheetTitle),
                    array_filter([
                        'establecimiento_id' => $context['establecimiento_id'] ?? null,
                        'periodo_anio' => $context['periodo_anio'] ?? null,
                        'periodo_mes' => $context['periodo_mes'] ?? null,
                        'estructura_servicio_id' => $context['estructura_servicio_id'] ?? null,
                    ], fn ($v) => $v !== null && $v !== '')
                );

                if (! ($sheetPreview['importable'] ?? false)) {
                    $resultado['omitidos'][] = [
                        'hoja' => $sheetTitle,
                        'sp' => $spCodigo,
                        'mensaje' => 'Importación no disponible para '.$spCodigo.'.',
                    ];

                    continue;
                }

                $layout = $this->detectLayout($sheetPreview['detectado'] ?? []);
                $decisiones = [];
                $filasImportadas = 0;
                $filasTotal = 0;

                if ($layout === 'matriz') {
                    $filasTotal = count($sheetPreview['detectado']['matriz']['rows'] ?? []);
                    if ($filasTotal === 0) {
                        $resultado['omitidos'][] = [
                            'hoja' => $sheetTitle,
                            'sp' => $spCodigo,
                            'mensaje' => 'Sin filas de matriz para importar.',
                        ];

                        continue;
                    }
                    $filasImportadas = $filasTotal;
                } elseif ($layout === 'nominativo') {
                    $filasTotal = count($sheetPreview['detectado']['episodios'] ?? []);
                    if ($filasTotal === 0) {
                        $resultado['omitidos'][] = [
                            'hoja' => $sheetTitle,
                            'sp' => $spCodigo,
                            'mensaje' => 'Sin episodios hospitalarios para importar.',
                        ];

                        continue;
                    }
                    $filasImportadas = $filasTotal;
                } else {
                    $decisiones = $this->autoDecisiones($sheetPreview['detectado']['filas'] ?? []);
                    $filasTotal = count($sheetPreview['detectado']['filas'] ?? []);
                    if ($decisiones === []) {
                        $resultado['omitidos'][] = [
                            'hoja' => $sheetTitle,
                            'sp' => $spCodigo,
                            'mensaje' => 'Sin filas con match automático. Revise el detalle manualmente.',
                        ];

                        continue;
                    }
                    $filasImportadas = count($decisiones);
                }

                $record = $this->confirm(
                    $user,
                    $sheetPreview,
                    [
                        'formulario_id' => (int) $sheetPreview['formulario_id'],
                        'establecimiento_id' => (int) $context['establecimiento_id'],
                        'periodo_anio' => (int) $context['periodo_anio'],
                        'periodo_mes' => (int) $context['periodo_mes'],
                        'estructura_servicio_id' => $context['estructura_servicio_id'] ?? null,
                    ],
                    $decisiones,
                    $sobrescribir
                );

                $procesadosSp[$spCodigo] = true;
                $resultado['ok'][] = [
                    'hoja' => $sheetTitle,
                    'sp' => $record->formulario?->codigo ?? $spCodigo,
                    'record_id' => $record->id,
                    'filas' => $filasImportadas,
                    'filas_total' => $filasTotal,
                    'parcial' => $layout === 'tabular' && $filasImportadas < $filasTotal,
                ];
            } catch (ValidationException $exception) {
                $resultado['errores'][] = [
                    'hoja' => $sheetTitle,
                    'sp' => $spCodigo,
                    'mensaje' => collect($exception->errors())->flatten()->first() ?? 'Error de validación.',
                ];
            } catch (\Throwable $exception) {
                $resultado['errores'][] = [
                    'hoja' => $sheetTitle,
                    'sp' => $spCodigo,
                    'mensaje' => $exception->getMessage(),
                ];
            }
        }

        return $resultado;
    }

    /**
     * Enriquece cada hoja importable con estadísticas de matching para el resumen.
     *
     * @param  array<string, mixed>  $preview
     * @return array<string, mixed>
     */
    public function enrichWorkbookForSummary(array $preview): array
    {
        $hojaActivaOriginal = $preview['hoja_activa'] ?? null;
        $context = array_filter([
            'establecimiento_id' => $preview['establecimiento_id'] ?? null,
            'periodo_anio' => $preview['periodo_anio'] ?? null,
            'periodo_mes' => $preview['periodo_mes'] ?? null,
            'estructura_servicio_id' => $preview['estructura_servicio_id'] ?? null,
        ], fn ($v) => $v !== null && $v !== '');

        $hojas = [];
        foreach ($preview['workbook']['hojas'] ?? [] as $hoja) {
            if (! ($hoja['importable'] ?? false) || ! ($hoja['parseado'] ?? false) || ($hoja['filas_detectadas'] ?? 0) <= 0) {
                $hoja['calidad'] = $this->computeCalidad($hoja, $preview);
                $hojas[] = $hoja;

                continue;
            }

            try {
                $sheetPreview = $this->applyPreviewContext(
                    $this->activateSheet($preview, (string) $hoja['titulo']),
                    $context
                );
                $layout = $this->detectLayout($sheetPreview['detectado'] ?? []);
                if ($layout === 'matriz') {
                    $count = count($sheetPreview['detectado']['matriz']['rows'] ?? []);
                    $hoja['prestaciones_enlazadas'] = $count;
                    $hoja['prestaciones_sin_match'] = 0;
                    $hoja['listo_lote'] = $count > 0
                        && ($sheetPreview['establecimiento_id'] ?? null)
                        && ($sheetPreview['periodo_anio'] ?? null)
                        && ($sheetPreview['periodo_mes'] ?? null);
                } elseif ($layout === 'nominativo') {
                    $count = count($sheetPreview['detectado']['episodios'] ?? []);
                    $hoja['prestaciones_enlazadas'] = $count;
                    $hoja['prestaciones_sin_match'] = 0;
                    $hoja['listo_lote'] = $count > 0
                        && ($sheetPreview['establecimiento_id'] ?? null)
                        && ($sheetPreview['periodo_anio'] ?? null)
                        && ($sheetPreview['periodo_mes'] ?? null);
                } else {
                    $filas = $sheetPreview['detectado']['filas'] ?? [];
                    $auto = count(array_filter(
                        $filas,
                        fn (array $f) => ! empty($f['prestacion_id']) && (int) ($f['nivel'] ?? 4) < 4
                    ));
                    $hoja['prestaciones_enlazadas'] = $auto;
                    $hoja['prestaciones_sin_match'] = max(0, count($filas) - $auto);
                    $hoja['listo_lote'] = $auto > 0
                        && ($sheetPreview['establecimiento_id'] ?? null)
                        && ($sheetPreview['periodo_anio'] ?? null)
                        && ($sheetPreview['periodo_mes'] ?? null);
                }
                $hoja['formulario_id'] = $sheetPreview['formulario_id'] ?? null;
                $hoja['record_existente'] = $sheetPreview['record_existente'] ?? null;
            } catch (\Throwable) {
                $hoja['listo_lote'] = false;
            }

            $hoja['calidad'] = $this->computeCalidad($hoja, $preview);
            $hojas[] = $hoja;
        }

        $preview['workbook']['hojas'] = $hojas;

        if ($hojaActivaOriginal) {
            try {
                $preview = $this->activateSheet($preview, $hojaActivaOriginal);
            } catch (\Throwable) {
                // Mantener hojas enriquecidas aunque falle reactivar la hoja original.
            }
        }

        return $preview;
    }

    /**
     * @param  array<int, array<string, mixed>>  $filas
     * @return array<string, string>
     */
    private function autoDecisiones(array $filas): array
    {
        $decisiones = [];
        foreach ($filas as $fila) {
            if (! empty($fila['prestacion_id']) && (int) ($fila['nivel'] ?? 4) < 4) {
                $decisiones[(string) $fila['key']] = (string) $fila['prestacion_id'];
            }
        }

        return $decisiones;
    }

    /**
     * @param  array<int, array<string, mixed>>  $filasRaw
     * @param  array<int, int>  $validPrestacionIds
     * @return array<int, array<string, mixed>>
     */
    private function rematchFilas(array $filasRaw, ?string $domain, array $validPrestacionIds): array
    {
        $filas = [];
        foreach ($filasRaw as $fila) {
            $filas[] = $this->rematchFila($fila, $domain, $validPrestacionIds);
        }

        return $filas;
    }

    /**
     * @param  \Illuminate\Support\Collection<string, Field>  $fieldsByCode
     * @return array<int, array<string, mixed>>
     */
    private function rematchSp9Filas(array $filasRaw, string $domain, $fieldsByCode): array
    {
        $filas = [];
        foreach ($filasRaw as $fila) {
            $fieldCode = (string) ($fila['field_code'] ?? 'var_4_atencion_de_urgencias_adultos');
            $field = $fieldsByCode->get($fieldCode);
            $validIds = $field
                ? $field->rowItems()->pluck('id')->map(fn ($id) => (int) $id)->all()
                : [];
            $filas[] = $this->rematchFila($fila, $domain, $validIds);
        }

        return $filas;
    }

    /**
     * @param  array<string, mixed>  $fila
     * @param  array<int, int>  $validPrestacionIds
     * @return array<string, mixed>
     */
    private function rematchFila(array $fila, ?string $domain, array $validPrestacionIds): array
    {
        $valid = array_flip($validPrestacionIds);
        $label = (string) ($fila['prestacion_label'] ?? $fila['especialidad'] ?? '');
        $match = $this->matcher->match($label, $domain);
        $prestacionId = $match['catalog_item_id'] ?? null;
        $nivel = (int) ($match['nivel'] ?? 4);
        if ($prestacionId && ! isset($valid[(int) $prestacionId])) {
            $prestacionId = null;
            $nivel = 4;
            $match['sugerencia'] = 'Fuera del diccionario del SP seleccionado';
        }

        return array_merge($fila, [
            'nivel' => $nivel,
            'prestacion_id' => $prestacionId,
            'sugerencia' => $match['sugerencia'] ?? null,
            'score' => $match['score'] ?? null,
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<string, Field>
     */
    private function tablaFieldsByCode(Formulario $formulario)
    {
        return $formulario->secciones()
            ->with('fields')
            ->get()
            ->flatMap(fn ($section) => $section->fields)
            ->where('type', 'tabla')
            ->keyBy('code');
    }

    private function primaryTablaField(Formulario $formulario): ?Field
    {
        return Field::query()
            ->where('type', 'tabla')
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $formulario->id))
            ->orderByDesc('required')
            ->orderBy('orden')
            ->first();
    }

    /**
     * @param  array<int, array<string, mixed>>  $filas
     * @param  array<string, string>  $decisiones
     * @param  array<int, int>  $validPrestacionIds
     * @return array<string, array<string, int>>
     */
    private function buildRows(array $filas, array $decisiones, array $validPrestacionIds, Field $field): array
    {
        $valid = array_flip($validPrestacionIds);
        $columnCodes = collect($field->config['columns'] ?? [])->pluck('code')->filter()->values()->all();
        if ($columnCodes === []) {
            $columnCodes = ['total_consultas', 'total'];
        }

        $rows = [];
        foreach ($filas as $fila) {
            $key = (string) ($fila['key'] ?? '');
            $decision = $decisiones[$key] ?? null;
            if ($decision === 'discard') {
                continue;
            }

            $prestacionId = null;
            if (is_numeric($decision)) {
                $prestacionId = (int) $decision;
            } elseif ($fila['prestacion_id'] ?? null) {
                $prestacionId = (int) $fila['prestacion_id'];
            }
            if (! $prestacionId || ! isset($valid[$prestacionId])) {
                continue;
            }

            $metricas = $fila['metricas'] ?? [];
            if ($metricas === [] && isset($fila['total_consultas'])) {
                $metricas = ['total_consultas' => (int) $fila['total_consultas']];
            }

            // Planilla MULTI_METRIC (SP7) usa clave "prestaciones"; el formulario publica columna "total".
            if (! isset($metricas['total']) && isset($metricas['prestaciones']) && in_array('total', $columnCodes, true)) {
                $metricas['total'] = (int) $metricas['prestaciones'];
            }
            if (! isset($metricas['total']) && isset($metricas['determinaciones']) && in_array('total', $columnCodes, true)) {
                $metricas['total'] = (int) $metricas['determinaciones'];
            }

            $rowValues = [];
            foreach ($columnCodes as $code) {
                if (! isset($metricas[$code])) {
                    continue;
                }
                $value = (int) $metricas[$code];
                if ($value > 0) {
                    $rowValues[$code] = $value;
                }
            }
            if ($rowValues === []) {
                continue;
            }

            $rows[(string) $prestacionId] = $rowValues;
        }

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $workbook
     * @return array<string, mixed>
     */
    private function enrichWorkbookSummary(array $workbook): array
    {
        $hojas = [];
        foreach ($workbook['hojas'] ?? [] as $hoja) {
            $spCode = $hoja['sp_codigo'] ?? null;
            $hoja['importable'] = $spCode && $this->isImportable($spCode) && ($hoja['parseado'] ?? false);
            $hoja['calidad'] = $this->computeCalidad($hoja, ['workbook' => $workbook]);
            $hojas[] = $hoja;
        }
        $workbook['hojas'] = $hojas;

        return $workbook;
    }

    /**
     * @param  array<string, mixed>  $hoja
     * @param  array<string, mixed>  $preview
     * @return array{score: int, nivel: string, motivos: array<int, string>, requiere_asistente: bool}
     */
    public function computeCalidad(array $hoja, array $preview = []): array
    {
        $motivos = [];
        $score = 0;
        $sp = $hoja['sp_codigo'] ?? null;
        $parseado = (bool) ($hoja['parseado'] ?? false);
        $filas = (int) ($hoja['filas_detectadas'] ?? 0);
        $importable = $sp && $this->isImportable((string) $sp) && $parseado;

        if ($importable) {
            $score += 30;
        } elseif (! $sp) {
            $motivos[] = 'Sin SP detectado → use Ajustar mapeo';
        } elseif (! $parseado) {
            $error = (string) ($hoja['error'] ?? 'SP detectado pero sin datos parseables');
            $motivos[] = str_contains(mb_strtolower($error), 'mapeo')
                ? $error
                : $error.' → use Ajustar mapeo';
        } else {
            $motivos[] = 'SP no habilitado para importación de datos';
        }

        $contexto = $preview['workbook']['contexto'] ?? [];
        $codigo = $hoja['codigo_planilla'] ?? $contexto['codigo_planilla'] ?? null;
        $estId = $preview['establecimiento_id'] ?? null;
        if ($estId || ($codigo && $this->resolveEstablecimiento((string) $codigo))) {
            $score += 20;
        } else {
            $motivos[] = 'Establecimiento no resuelto';
        }

        $mes = $hoja['periodo_mes'] ?? $contexto['periodo_mes'] ?? $preview['periodo_mes'] ?? null;
        $anio = $hoja['periodo_anio'] ?? $contexto['periodo_anio'] ?? $preview['periodo_anio'] ?? null;
        if ($mes && $anio) {
            $score += 15;
        } else {
            $motivos[] = 'Período incompleto';
        }

        if ($filas > 0) {
            $score += 15;
        } elseif ($parseado) {
            $motivos[] = '0 filas con datos';
        }

        $enlazadas = (int) ($hoja['prestaciones_enlazadas'] ?? 0);
        $sinMatch = (int) ($hoja['prestaciones_sin_match'] ?? 0);
        $totalMatch = $enlazadas + $sinMatch;
        if ($totalMatch > 0) {
            $pct = ($enlazadas / $totalMatch) * 100;
            if ($pct >= 90) {
                $score += 20;
            } elseif ($pct >= 70) {
                $score += 10;
                $motivos[] = 'Matching parcial ('.round($pct).'%)';
            } else {
                $motivos[] = 'Muchas filas sin match ('.round($pct).'% enlazadas)';
            }
        } elseif ($filas > 0 && $importable) {
            // matriz/nominativo sin matching de prestaciones
            $score += 20;
        }

        if ($sinMatch > 0 && $enlazadas === 0 && $filas > 0) {
            $motivos[] = 'Ninguna fila enlazada → revise matching en Detalle';
        }

        if (! $parseado || $filas === 0) {
            $nivel = 'fallido';
            $score = min($score, 40);
        } elseif ($score >= 80) {
            $nivel = 'alto';
        } elseif ($score >= 55) {
            $nivel = 'medio';
        } else {
            $nivel = 'bajo';
        }

        $requiere = in_array($nivel, ['bajo', 'fallido'], true)
            || ! $sp
            || ! $parseado
            || $filas === 0;

        return [
            'score' => $score,
            'nivel' => $nivel,
            'motivos' => array_values(array_unique($motivos)),
            'requiere_asistente' => $requiere,
        ];
    }

    private function persistUpload(UploadedFile $file, string $token): string
    {
        $dir = storage_path('app/bioestadistica/sp-import-tmp');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $ext = strtolower($file->getClientOriginalExtension() ?: 'xlsx');
        if (! in_array($ext, ['xls', 'xlsx'], true)) {
            $ext = 'xlsx';
        }
        $path = $dir.DIRECTORY_SEPARATOR.$token.'.'.$ext;
        if (! copy($file->getRealPath(), $path)) {
            throw ValidationException::withMessages([
                'archivo' => 'No se pudo guardar una copia temporal del archivo para el asistente de mapeo.',
            ]);
        }

        return $path;
    }

    private function forgetTempFile(?string $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }
        $root = realpath(storage_path('app/bioestadistica/sp-import-tmp'));
        $real = realpath($path);
        if ($root && $real && str_starts_with($real, $root) && is_file($real)) {
            @unlink($real);
        }
    }

    /**
     * @param  array<string, mixed>  $workbook
     * @return array<string, mixed>|null
     */
    private function defaultSheet(array $workbook): ?array
    {
        foreach ($workbook['hojas'] ?? [] as $hoja) {
            if (($hoja['importable'] ?? false) && ($hoja['filas_detectadas'] ?? 0) > 0) {
                return $hoja;
            }
        }

        foreach ($workbook['hojas'] ?? [] as $hoja) {
            if (($hoja['parseado'] ?? false) && ($hoja['filas_detectadas'] ?? 0) > 0) {
                return $hoja;
            }
        }

        return $workbook['hojas'][0] ?? null;
    }

    /**
     * @param  array<string, mixed>  $preview
     * @return array<string, mixed>|null
     */
    private function findSheet(array $preview, string $sheetTitle): ?array
    {
        $needle = $this->normalizeSheetTitle($sheetTitle);
        if ($needle === '') {
            return null;
        }

        foreach ($preview['workbook']['hojas'] ?? [] as $hoja) {
            if ($this->normalizeSheetTitle((string) ($hoja['titulo'] ?? '')) === $needle) {
                return $hoja;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $preview
     * @return array<string, mixed>|null
     */
    public function findSheetByIndex(array $preview, int $index): ?array
    {
        $hojas = array_values($preview['workbook']['hojas'] ?? []);

        return $hojas[$index] ?? null;
    }

    /**
     * Resuelve hoja por índice (preferido) o por título tolerante.
     *
     * @param  array<string, mixed>  $preview
     * @return array{0: array<string, mixed>, 1: string}|null  [hoja, titulo_real]
     */
    public function resolveSheet(array $preview, ?string $sheetTitle, ?int $sheetIndex = null): ?array
    {
        if ($sheetIndex !== null && $sheetIndex >= 0) {
            $hoja = $this->findSheetByIndex($preview, $sheetIndex);
            if ($hoja) {
                return [$hoja, (string) ($hoja['titulo'] ?? '')];
            }
        }

        if (is_string($sheetTitle) && $sheetTitle !== '') {
            $hoja = $this->findSheet($preview, $sheetTitle);
            if ($hoja) {
                return [$hoja, (string) ($hoja['titulo'] ?? $sheetTitle)];
            }
        }

        return null;
    }

    private function normalizeSheetTitle(string $title): string
    {
        $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title = str_replace("\u{00A0}", ' ', $title);
        $title = preg_replace('/\s+/u', ' ', trim($title)) ?? trim($title);

        return Str::upper(Str::ascii($title));
    }

    private function resolveEstablecimiento(?string $codigo): ?Establecimiento
    {
        if ($codigo === null || trim($codigo) === '') {
            return null;
        }
        $codigo = trim($codigo);

        return Establecimiento::query()
            ->where(function ($query) use ($codigo) {
                $query->where('codigo', $codigo)->orWhere('codigo_sih', $codigo);
            })
            ->first();
    }

    /**
     * @return array<int, array{id:int,label:string}>
     */
    public function cortesForEstablecimiento(int $establecimientoId): array
    {
        return EstablecimientoServicio::query()
            ->with(['departamento', 'servicio'])
            ->where('establecimiento_id', $establecimientoId)
            ->get()
            ->map(fn (EstablecimientoServicio $item) => [
                'id' => (int) $item->servicio_id,
                'label' => trim(($item->departamento?->nombre ?? '').' / '.($item->servicio?->nombre ?? ''), ' /'),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $lookup
     * @return array<string, mixed>
     */
    private function applyCorte(array $lookup, int $establecimientoId, mixed $servicioId): array
    {
        $unidades = EstablecimientoServicio::query()
            ->where('establecimiento_id', $establecimientoId)
            ->get();
        if ($unidades->isEmpty()) {
            return $lookup;
        }

        $match = $unidades->firstWhere('servicio_id', (int) $servicioId);
        if (! $match) {
            throw ValidationException::withMessages([
                'estructura_servicio_id' => 'Seleccione el departamento / servicio de la carga.',
            ]);
        }

        $lookup['estructura_departamento_id'] = $match->departamento_id;
        $lookup['estructura_servicio_id'] = $match->servicio_id;

        return $lookup;
    }

    private function assertUserCanCapture(User $user, ?int $establecimientoId, ?Formulario $formulario = null): void
    {
        $scope = app(CaptureScopeService::class);
        $scope->assertCanUseEstablecimiento($user, $establecimientoId);

        if ($establecimientoId !== null && $formulario !== null) {
            $scope->validateCanCapture($user, (int) $formulario->id, $establecimientoId);
        }
    }

    /**
     * @param  array<string, mixed>  $preview
     * @return array<string, string|null>
     */
    private function importOriginPayload(array $preview): array
    {
        $archivo = trim((string) ($preview['archivo'] ?? ''));
        $hoja = trim((string) ($preview['hoja_activa'] ?? ''));

        return [
            'origen_carga' => Record::ORIGEN_IMPORTACION_SP,
            'import_archivo' => $archivo !== '' ? Str::limit($archivo, 255, '') : null,
            'import_hoja' => $hoja !== '' ? Str::limit($hoja, 255, '') : null,
        ];
    }

    /**
     * @param  array<string, mixed>  $preview
     */
    private function stampImportOrigin(Record $record, array $preview): void
    {
        $record->update($this->importOriginPayload($preview));
    }
}
