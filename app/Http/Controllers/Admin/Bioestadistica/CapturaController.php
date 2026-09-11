<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Capture\CaptureScopeService;
use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Application\Bioestadistica\Organigrama\OrganoCorteService;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Http\Controllers\Admin\Bioestadistica\Concerns\BuildsCaptureNavigator;
use App\Http\Controllers\Admin\Bioestadistica\Concerns\RespondsWithDataTables;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\UsuarioEstablecimiento;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CapturaController extends Controller
{
    use BuildsCaptureNavigator;
    use RespondsWithDataTables;

    public function __construct(
        private CaptureScopeService $captureScope,
        private OrganoCorteService $organoCortes
    ) {}

    public function index(Request $request): View
    {
        $closed = PeriodContext::lastClosed();
        $periodoAnio = $request->filled('periodo_anio')
            ? $request->integer('periodo_anio')
            : $closed['anio'];
        $periodoMes = $request->filled('periodo_mes')
            ? $request->integer('periodo_mes')
            : $closed['mes'];

        $records = Record::query()
            ->forUser($request->user())
            ->with([
                'formulario',
                'establecimiento.distrito.departamento',
                'organo.tipo',
                'organo.parent.tipo',
            ])
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($periodoAnio, fn ($query) => $query->where('periodo_anio', $periodoAnio))
            ->when($periodoMes, fn ($query) => $query->where('periodo_mes', $periodoMes))
            ->when($request->filled('formulario_id'), fn ($query) => $query->where('formulario_id', $request->integer('formulario_id')))
            ->when($request->filled('establecimiento_id'), fn ($query) => $query->where('establecimiento_id', $request->integer('establecimiento_id')))
            ->get()
            ->sortBy([
                fn (Record $record) => $record->formulario->codigo ?? '',
                fn (Record $record) => $record->corteLabel() ?? '',
            ])
            ->values();

        $groups = $records
            ->groupBy('establecimiento_id')
            ->map(function ($items) {
                $establecimiento = $items->first()->establecimiento;

                return [
                    'establecimiento' => $establecimiento,
                    'records' => $items->values(),
                    'count' => $items->count(),
                ];
            })
            ->sortBy(fn (array $group) => $group['establecimiento']->nombre ?? '')
            ->values();

        return view('admin.bioestadistica.captura.index', [
            'formularios' => $this->allowedFormularios(),
            'establecimientos' => $this->allowedEstablishments(),
            'months' => $this->months(),
            'groups' => $groups,
            'periodo_anio' => $periodoAnio,
            'periodo_mes' => $periodoMes,
            'openNuevaCargaModal' => $request->boolean('nueva'),
            'selectedEstablecimientoId' => $request->integer('establecimiento_id') ?: old('establecimiento_id'),
            'selectedFormularioId' => $request->integer('formulario_id') ?: old('formulario_id'),
            'selectedAnio' => $request->integer('periodo_anio') ?: old('periodo_anio', $periodoAnio),
            'selectedMes' => $request->integer('periodo_mes') ?: old('periodo_mes', $periodoMes),
            'selectedOrganoId' => $request->integer('organo_id') ?: old('organo_id'),
            'cortesUrl' => route('bioestadistica.captura.cortes'),
        ]);
    }

    public function cortes(Request $request): JsonResponse
    {
        $establecimientoId = $request->integer('establecimiento_id');
        abort_unless($establecimientoId > 0, 422, 'Establecimiento requerido.');
        $this->ensureAllowedEstablishment($establecimientoId);

        $opciones = $this->organoCortes->opcionesParaEstablecimiento($establecimientoId);

        return response()->json([
            'required' => $opciones->isNotEmpty(),
            'opciones' => $opciones->all(),
        ]);
    }

    public function datatable(Request $request): JsonResponse
    {
        $months = $this->months();
        $canView = $request->user()->can('bio.record.view');

        $base = Record::query()
            ->forUser($request->user())
            ->with(['formulario', 'establecimiento'])
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($request->filled('periodo_anio'), fn ($query) => $query->where('periodo_anio', $request->integer('periodo_anio')))
            ->when($request->filled('periodo_mes'), fn ($query) => $query->where('periodo_mes', $request->integer('periodo_mes')))
            ->when($request->filled('formulario_id'), fn ($query) => $query->where('formulario_id', $request->integer('formulario_id')));

        return $this->dataTablesJson(
            $request,
            $base,
            function ($query, string $search): void {
                $query->where(function ($inner) use ($search) {
                    $inner->whereHas('formulario', function ($forms) use ($search) {
                        $forms->where('codigo', 'ilike', "%{$search}%")
                            ->orWhere('nombre', 'ilike', "%{$search}%");
                    })->orWhereHas('establecimiento', function ($ests) use ($search) {
                        $ests->where('nombre', 'ilike', "%{$search}%")
                            ->orWhere('codigo', 'ilike', "%{$search}%");
                    })->orWhere('estado', 'ilike', "%{$search}%");
                });
            },
            [
                0 => null,
                1 => null,
                2 => 'periodo_anio',
                3 => 'estado',
                4 => 'updated_at',
                5 => null,
            ],
            function (Record $record) use ($months, $canView) {
                $periodo = ($months[$record->periodo_mes] ?? $record->periodo_mes).'/'.$record->periodo_anio;
                $badge = e(Record::estadoLabel($record->estado));
                $badgeClass = e(Record::estadoBadge($record->estado));
                $action = $canView
                    ? '<div class="bio-actions"><a class="btn btn-outline-primary btn-sm" href="'.e(route('bioestadistica.captura.edit', $record)).'">'
                        .($record->isEditable() ? 'Editar' : 'Ver').'</a></div>'
                    : '';

                return [
                    'formulario' => e(($record->formulario->codigo ?? '').' — '.($record->formulario->nombre ?? '')),
                    'establecimiento' => e($record->establecimiento->nombre ?? '—'),
                    'periodo' => e($periodo),
                    'origen' => $record->isImported()
                        ? '<span class="badge badge-info" title="'.e($record->importProcedenciaLabel() ?? '').'">Importación</span>'
                        : '<span class="badge badge-light text-dark border">Manual</span>',
                    'estado' => '<span class="badge '.$badgeClass.'">'.$badge.'</span>',
                    'actualizado' => e(optional($record->updated_at)->format('d/m/Y H:i') ?? '—'),
                    'acciones' => $action,
                ];
            },
            'updated_at',
            'desc'
        );
    }

    public function create(Request $request): RedirectResponse
    {
        $this->ensureCanCapture();

        return redirect()->route('bioestadistica.captura.index', array_filter([
            'nueva' => 1,
            'establecimiento_id' => $request->integer('establecimiento_id') ?: null,
            'formulario_id' => $request->integer('formulario_id') ?: null,
            'periodo_anio' => $request->integer('periodo_anio') ?: null,
            'periodo_mes' => $request->integer('periodo_mes') ?: null,
            'organo_id' => $request->integer('organo_id') ?: null,
        ], fn ($value) => $value !== null && $value !== ''));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $this->ensureCanCapture();
        $data = $this->validateContext($request);
        $this->ensureAllowedEstablishment((int) $data['establecimiento_id']);
        $this->captureScope->assertCanCapture($request->user(), (int) $data['formulario_id'], (int) $data['establecimiento_id']);
        $formulario = Formulario::findOrFail($data['formulario_id']);
        abort_unless(
            Establecimiento::whereKey($data['establecimiento_id'])->whereNotNull('distrito_id')->exists(),
            422,
            'El establecimiento debe tener distrito asignado para iniciar la captura.'
        );

        $lookup = $this->lookupForCapture($data, $formulario);
        $exists = Record::where($lookup)->first();
        if ($exists) {
            return $this->redirectToCapture(
                $exists,
                'warning',
                'Ya existe un registro para ese formulario, establecimiento y período.'
            );
        }

        try {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'origen_carga' => Record::ORIGEN_MANUAL,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
        } catch (UniqueConstraintViolationException) {
            $exists = Record::where($lookup)->firstOrFail();

            return $this->redirectToCapture(
                $exists,
                'warning',
                'Ya existe un registro para ese formulario, establecimiento y período.'
            );
        }

        return $this->redirectToCapture(
            $record,
            'success',
            $formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo'
                ? 'Período creado. Cargue los episodios de la planilla SP10.'
                : 'Período creado. Complete los datos y guarde el borrador.'
        );
    }

    public function pending(Request $request): View
    {
        $year = $request->integer('periodo_anio', now()->subMonth()->year);
        $month = $request->integer('periodo_mes', now()->subMonth()->month);
        $establishments = $this->allowedEstablishments()->filter(fn ($item) => $item->distrito_id);
        $existing = Record::query()
            ->forUser($request->user())
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->get();

        $rows = collect();
        foreach ($establishments as $establecimiento) {
            $forms = $this->captureScope->allowedFormularios($request->user(), (int) $establecimiento->id);
            if ($forms->isEmpty()) {
                continue;
            }

            $cortes = $this->organoCortes->opcionesRaizParaEstablecimiento((int) $establecimiento->id);
            $slices = $cortes->isEmpty()
                ? collect([['organo_id' => null, 'unidad' => null]])
                : $cortes->map(fn (array $corte) => [
                    'organo_id' => $corte['id'],
                    'unidad' => $corte['label'],
                ]);

            foreach ($slices as $slice) {
                $organoId = $slice['organo_id'];
                $missing = $forms->reject(function (Formulario $form) use ($existing, $establecimiento, $organoId) {
                    return $existing->contains(function (Record $record) use ($form, $establecimiento, $organoId) {
                        return (int) $record->establecimiento_id === (int) $establecimiento->id
                            && (int) $record->formulario_id === (int) $form->id
                            && (int) ($record->organo_id ?? 0) === (int) ($organoId ?? 0);
                    });
                });

                if ($missing->isEmpty()) {
                    continue;
                }

                $rows->push([
                    'establecimiento' => $establecimiento,
                    'unidad' => $slice['unidad'],
                    'organo_id' => $organoId,
                    'missing' => $missing,
                ]);
            }
        }

        $groups = $rows
            ->groupBy(fn (array $row) => $row['establecimiento']->id)
            ->map(function ($slices) {
                $first = $slices->first();

                return [
                    'establecimiento' => $first['establecimiento'],
                    'slices' => $slices->values(),
                    'pending_count' => $slices->sum(fn (array $slice) => $slice['missing']->count()),
                ];
            })
            ->values();

        return view('admin.bioestadistica.captura.pending', [
            'groups' => $groups,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'months' => $this->months(),
        ]);
    }

    public function edit(Record $record): View|RedirectResponse
    {
        $this->ensureCanView($record);
        $record->load([
            'formulario.secciones.fields.detalle.catalogoItems',
            'establecimiento.distrito.departamento',
            'organo.tipo',
            'organo.parent.tipo',
            'values.field',
        ]);
        if ($record->formulario->codigo === 'SP10' || $record->formulario->layout_type === 'nominativo') {
            return redirect()->route('bioestadistica.hospitalizacion.spreadsheet', $record->spreadsheetParams());
        }

        return view('admin.bioestadistica.captura.edit', [
            'record' => $record,
            'valuesByField' => $record->values->keyBy('field_id'),
            'months' => $this->months(),
            'siblingPlanillas' => $this->captureNavigator(
                (int) $record->establecimiento_id,
                (int) $record->periodo_anio,
                (int) $record->periodo_mes,
                (int) $record->formulario_id,
                $record->organo_id ? (int) $record->organo_id : null
            ),
            'establecimientoId' => (int) $record->establecimiento_id,
            'establecimientoNombre' => $record->establecimiento->nombre,
            'periodo_anio' => (int) $record->periodo_anio,
            'periodo_mes' => (int) $record->periodo_mes,
        ]);
    }

    public function update(Request $request, Record $record, RecordCaptureService $capture): RedirectResponse
    {
        $this->ensureCanEdit($record);
        if ($record->formulario->layout_type === 'nominativo') {
            abort(422, 'El consolidado SP10 no se edita en captura.');
        }
        $capture->save($record, $request->input('values', []), false, true, $request->user()->id);
        $record->update([
            'updated_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Borrador guardado.');
    }

    public function autosave(Request $request, Record $record, RecordCaptureService $capture): JsonResponse
    {
        $this->ensureCanEdit($record);
        $record->loadMissing('formulario');
        if ($record->formulario->layout_type === 'nominativo') {
            return response()->json([
                'ok' => false,
                'message' => 'El consolidado SP10 no se edita en captura.',
            ], 422);
        }

        $capture->saveDraft($record, $request->input('values', []), $request->user()->id);
        $record->update([
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'ok' => true,
            'saved_at' => now()->timezone(config('app.timezone'))->format('H:i:s'),
        ]);
    }

    public function updatePeriod(
        Request $request,
        Record $record,
        IndicatorCacheService $cache
    ): RedirectResponse {
        $this->ensureCanView($record);
        $this->authorize('update', $record);
        $record->loadMissing('formulario');

        $period = $request->validate([
            'periodo_anio' => PeriodContext::yearValidationRules(true),
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'organo_id' => ['nullable', 'integer'],
        ]);

        $organo = $this->organoCortes->assertSeleccion(
            (int) $record->establecimiento_id,
            $period['organo_id'] ?? null
        );
        $organoId = $organo?->id;

        $isNominative = $record->formulario->codigo === 'SP10'
            || $record->formulario->layout_type === 'nominativo';

        $duplicateQuery = Record::query()
            ->where('formulario_id', $record->formulario_id)
            ->where('establecimiento_id', $record->establecimiento_id)
            ->where('periodo_anio', $period['periodo_anio'])
            ->where('periodo_mes', $period['periodo_mes'])
            ->where('id', '<>', $record->id);
        if ($organoId) {
            $duplicateQuery->where('organo_id', $organoId);
        } else {
            $duplicateQuery->whereNull('organo_id');
        }
        if ($duplicateQuery->exists()) {
            return back()->withErrors([
                'periodo_mes' => 'Ya existe un registro de este formulario, establecimiento, dependencia y período.',
            ])->withInput();
        }

        $previousContext = $record->replicate();

        try {
            DB::transaction(function () use ($record, $period, $organoId, $isNominative, $request) {
                $locked = Record::query()->whereKey($record->id)->lockForUpdate()->firstOrFail();
                $locked->loadMissing('formulario');
                if ($isNominative) {
                    app(HospitalizationService::class)
                        ->reassignEpisodesToPeriod(
                            $locked,
                            (int) $period['periodo_anio'],
                            (int) $period['periodo_mes']
                        );
                } else {
                    $this->realignMatrices(
                        $locked,
                        (int) $period['periodo_anio'],
                        (int) $period['periodo_mes']
                    );
                }
                $locked->update([
                    'periodo_anio' => (int) $period['periodo_anio'],
                    'periodo_mes' => (int) $period['periodo_mes'],
                    'organo_id' => $organoId,
                    'updated_by' => $request->user()->id,
                ]);
                $record->refresh();
            });
        } catch (UniqueConstraintViolationException) {
            return back()->withErrors([
                'periodo_mes' => 'Ya existe un registro de este formulario, establecimiento, dependencia y período.',
            ])->withInput();
        }

        $cache->invalidateForRecord($previousContext);
        $cache->invalidateForRecord($record);

        if ($isNominative) {
            app(HospitalizationService::class)->consolidate(
                (int) $record->establecimiento_id,
                (int) $record->periodo_anio,
                (int) $record->periodo_mes,
                $request->user(),
                $record
            );

            return redirect()->route('bioestadistica.hospitalizacion.spreadsheet', $record->spreadsheetParams())
                ->with('success', 'Período y dependencia actualizados.');
        }

        return back()->with('success', 'Período y dependencia actualizados.');
    }

    public function submit(Request $request, Record $record, RecordCaptureService $capture): RedirectResponse
    {
        $this->authorize('submit', $record);
        $this->ensureCanEdit($record);
        if ($record->formulario->layout_type !== 'nominativo') {
            $capture->save($record, $this->savedValues($record), false, true, $request->user()->id);
        }
        $record->submit($request->user()->id);

        return $this->redirectToCapture(
            $record,
            'success',
            'Registro enviado para aprobación. Puede continuar con otro SP del mismo establecimiento.'
        );
    }

    public function approve(Request $request, Record $record): RedirectResponse
    {
        $this->authorize('approve', $record);
        abort_unless($request->user()->can('bio.record.approve'), 403);
        $this->ensureCanView($record);
        $record->approve($request->user()->id);

        return back()->with('success', 'Registro aprobado.');
    }

    public function reject(Request $request, Record $record): RedirectResponse
    {
        $this->authorize('approve', $record);
        abort_unless($request->user()->can('bio.record.approve'), 403);
        $this->ensureCanView($record);
        $data = $request->validate(['observacion' => ['required', 'string', 'max:2000']]);
        $record->reject($request->user()->id, $data['observacion']);

        return back()->with('success', 'Registro objetado y devuelto a edición.');
    }

    public function assignments(): RedirectResponse
    {
        abort_unless($this->managesAssignments(), 403);

        return redirect()->route('bioestadistica.asignaciones.index');
    }

    public function updateAssignments(Request $request, User $user, AuditService $audit): RedirectResponse
    {
        abort_unless($this->managesAssignments(), 403);
        abort_unless($user->hasRole('Digitador Bioestadística'), 422, 'El usuario debe tener el rol Digitador Bioestadística.');
        $data = $request->validate([
            'establecimiento_ids' => ['nullable', 'array'],
            'establecimiento_ids.*' => ['integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
        ]);

        $previous = UsuarioEstablecimiento::where('user_id', $user->id)->pluck('establecimiento_id')->map(fn ($id) => (int) $id)->all();
        $next = array_map('intval', $data['establecimiento_ids'] ?? []);
        $audit->withoutAuditing(function () use ($user, $next) {
            UsuarioEstablecimiento::where('user_id', $user->id)->delete();
            foreach ($next as $establishmentId) {
                UsuarioEstablecimiento::create(['user_id' => $user->id, 'establecimiento_id' => $establishmentId]);
            }
        });
        $audit->recordExplicit('update', UsuarioEstablecimiento::class, (int) $user->id, [
            'establecimiento_ids' => $previous,
        ], [
            'establecimiento_ids' => $next,
        ], ['phase' => 'assignment']);

        return back()->with('success', 'Asignaciones actualizadas.');
    }

    private function redirectToCapture(Record $record, string $level, string $message): RedirectResponse|JsonResponse
    {
        if ($this->requestExpectsJson()) {
            return response()->json([
                'ok' => true,
                'level' => $level,
                'message' => $message,
                'redirect' => $this->captureRedirectUrl($record),
            ], $level === 'success' ? 201 : 200);
        }

        return redirect()->to($this->captureRedirectUrl($record))->with($level, $message);
    }

    private function captureRedirectUrl(Record $record): string
    {
        $record->loadMissing('formulario');
        if ($record->formulario->codigo === 'SP10' || $record->formulario->layout_type === 'nominativo') {
            return route('bioestadistica.hospitalizacion.spreadsheet', $record->spreadsheetParams());
        }

        return route('bioestadistica.captura.edit', $record);
    }

    private function requestExpectsJson(): bool
    {
        return request()->expectsJson() || request()->ajax();
    }

    private function validateContext(Request $request): array
    {
        $data = $request->validate([
            'formulario_id' => [
                'required',
                Rule::exists(Formulario::class, 'id')->where('estado', 'activo')->withoutTrashed(),
            ],
            'establecimiento_id' => ['required', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'periodo_anio' => PeriodContext::yearValidationRules(true),
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'organo_id' => ['nullable', 'integer'],
        ]);

        $organo = $this->organoCortes->assertSeleccion(
            (int) $data['establecimiento_id'],
            $data['organo_id'] ?? null
        );
        $data['organo_id'] = $organo?->id;

        return $data;
    }

    private function lookupForCapture(array $data, Formulario $formulario): array
    {
        return [
            'formulario_id' => $data['formulario_id'],
            'establecimiento_id' => $data['establecimiento_id'],
            'periodo_anio' => $data['periodo_anio'],
            'periodo_mes' => $data['periodo_mes'],
            'organo_id' => $data['organo_id'] ?? null,
        ];
    }

    private function managesAssignments(): bool
    {
        return request()->user()->can('bio.assignment.manage');
    }

    private function allowedEstablishments()
    {
        return Establecimiento::query()
            ->with('distrito.departamento')
            ->when(
                ! $this->captureScope->userHasGlobalAccess(request()->user()),
                fn ($query) => $query->whereIn('id', $this->captureScope->assignedEstablishmentIds(request()->user()))
            )
            ->orderBy('nombre')
            ->get();
    }

    private function allowedFormularios(?int $establecimientoId = null)
    {
        return $this->captureScope->allowedFormularios(request()->user(), $establecimientoId);
    }

    private function ensureAllowedEstablishment(int $id): void
    {
        if ($this->captureScope->userHasGlobalAccess(request()->user())) {
            return;
        }
        if (! in_array($id, $this->captureScope->assignedEstablishmentIds(request()->user()), true)) {
            abort(403, 'No tiene asignado este establecimiento.');
        }
    }

    private function ensureCanView(Record $record): void
    {
        $this->authorize('view', $record);
        abort_unless($record->isAccessibleBy(request()->user()), 403, 'No tiene alcance sobre este registro.');
    }

    private function ensureCanCapture(): void
    {
        $this->authorize('create', Record::class);
        abort_unless(request()->user()->can('bio.record.create'), 403);
        abort_unless($this->captureScope->hasAnyCaptureScope(request()->user()), 403, 'No tiene establecimientos ni formularios asignados para capturar.');
    }

    private function ensureCanEdit(Record $record): void
    {
        $this->ensureCanView($record);
        $this->authorize('update', $record);
        abort_unless(request()->user()->can('bio.record.update'), 403);
        abort_unless($record->isEditable(), 422, 'El registro no está disponible para edición.');
    }

    private function months(): array
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
    }

    private function realignMatrices(Record $record, int $year, int $month): void
    {
        $record->loadMissing('values.field');
        $days = Sp11Matrix::daysInPeriod($year, $month);

        foreach ($record->values as $value) {
            $field = $value->field;
            if (! $field) {
                continue;
            }
            $isMatrix = $field->type === 'matriz'
                || ($field->config['cols'] ?? null) === 'dias_mes'
                || ($field->config['contract'] ?? null) === 'sp11_v1';
            if (! $isMatrix) {
                continue;
            }

            $payload = is_array($value->value_json) ? $value->value_json : [];
            $incoming = $payload['rows'] ?? $payload;
            if (! is_array($incoming)) {
                continue;
            }

            foreach ($incoming as $cells) {
                if (! is_array($cells)) {
                    continue;
                }
                foreach ($cells as $key => $cell) {
                    if ($key === 'total' || ! ctype_digit((string) $key)) {
                        continue;
                    }
                    $day = (int) $key;
                    if ($day <= $days || $cell === null || $cell === '' || (int) $cell === 0) {
                        continue;
                    }
                    throw ValidationException::withMessages([
                        'periodo_mes' => "La matriz «{$field->label}» tiene datos en el día {$day}, que no existe en {$month}/{$year}. Corrija esos valores antes de cambiar el período.",
                    ]);
                }
            }

            $value->update([
                'value_json' => Sp11Matrix::normalize($payload, $year, $month, false),
            ]);
        }
    }

    private function savedValues(Record $record): array
    {
        $record->loadMissing('values.field');

        return $record->values->mapWithKeys(function ($value) {
            $field = $value->field;
            $raw = match ($field->type) {
                'integer', 'decimal' => $value->value_num,
                'date' => $value->value_date?->format('Y-m-d'),
                'boolean' => $value->value_bool,
                'multiselect', 'tabla', 'subtabla', 'matriz' => $value->value_json,
                default => $value->value_text,
            };

            return [$field->code => $raw];
        })->all();
    }
}
