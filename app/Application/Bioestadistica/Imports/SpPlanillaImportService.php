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
    public const IMPORTABLE = ['SP1', 'SP2', 'SP3', 'SP4', 'SP5', 'SP6', 'SP7', 'SP8', 'SP9', 'SP10', 'SP11'];

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
        if (! $defaultSheet || ! is_array($defaultSheet['detectado'] ?? null)) {
            throw ValidationException::withMessages([
                'archivo' => 'No se encontró ninguna hoja importable con datos en el archivo.',
            ]);
        }

        $parsed = $defaultSheet['detectado'];
        $detectedCodigo = $parsed['formulario_codigo'] ?? $defaultSheet['sp_codigo'] ?? 'SP1';
        $contexto = $workbook['contexto'] ?? [];
        $codigoPlanilla = $parsed['codigo_planilla'] ?? $contexto['codigo_planilla'] ?? null;

        $formulario = Formulario::where('codigo', $detectedCodigo)->where('estado', 'activo')->first()
            ?? Formulario::where('codigo', 'SP1')->where('estado', 'activo')->first();

        $establecimiento = $this->resolveEstablecimiento($codigoPlanilla);
        $this->assertUserCanCapture($user, $establecimiento?->id, $formulario);

        $preview = [
            'token' => Str::random(40),
            'archivo' => $file->getClientOriginalName(),
            'workbook' => $workbook,
            'hoja_activa' => $defaultSheet['titulo'],
            'detectado' => $parsed,
            'formulario_codigo_detectado' => $detectedCodigo,
            'formulario_id' => $formulario?->id,
            'establecimiento_id' => $establecimiento?->id,
            'periodo_anio' => $parsed['periodo_anio'] ?? $contexto['periodo_anio'] ?? null,
            'periodo_mes' => $parsed['periodo_mes'] ?? $contexto['periodo_mes'] ?? null,
            'estructura_servicio_id' => null,
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
        $preview['establecimiento'] = $establecimiento ? [
            'id' => $establecimiento->id,
            'codigo' => $establecimiento->codigo,
            'codigo_sih' => $establecimiento->codigo_sih,
            'nombre' => $establecimiento->nombre,
            'distrito_ok' => $establecimiento->distrito_id !== null,
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
        session()->forget(self::SESSION_KEY);
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

        if (! isset($matrixRows['camas_operativas'])) {
            $matrixRows['camas_operativas'] = ['total' => 0];
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
            $hojas[] = $hoja;
        }
        $workbook['hojas'] = $hojas;

        return $workbook;
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
        foreach ($preview['workbook']['hojas'] ?? [] as $hoja) {
            if (($hoja['titulo'] ?? '') === $sheetTitle) {
                return $hoja;
            }
        }

        return null;
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
