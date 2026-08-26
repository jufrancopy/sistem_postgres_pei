<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Http\Controllers\Admin\Bioestadistica\Concerns\BuildsCaptureNavigator;
use App\Http\Controllers\Admin\Bioestadistica\Concerns\RespondsWithDataTables;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoServicio;
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
                'estructuraDepartamento',
                'estructuraServicio',
            ])
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($periodoAnio, fn ($query) => $query->where('periodo_anio', $periodoAnio))
            ->when($periodoMes, fn ($query) => $query->where('periodo_mes', $periodoMes))
            ->when($request->filled('formulario_id'), fn ($query) => $query->where('formulario_id', $request->integer('formulario_id')))
            ->when($request->filled('establecimiento_id'), fn ($query) => $query->where('establecimiento_id', $request->integer('establecimiento_id')))
            ->get()
            ->sortBy([
                fn (Record $record) => $record->formulario->codigo ?? '',
                fn (Record $record) => $record->estructuraDepartamento?->nombre ?? '',
                fn (Record $record) => $record->estructuraServicio?->nombre ?? '',
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
            'formularios' => Formulario::where('estado', 'activo')->ordenSp()->get(),
            'establecimientos' => $this->allowedEstablishments(),
            'months' => $this->months(),
            'groups' => $groups,
            'periodo_anio' => $periodoAnio,
            'periodo_mes' => $periodoMes,
        ]);
    }

    public function datatable(Request $request): JsonResponse
    {
        $months = $this->months();
        $canView = $request->user()->can('bio.record.view');

        $base = Record::query()
            ->forUser($request->user())
            ->with(['formulario', 'establecimiento', 'estructuraDepartamento', 'estructuraServicio'])
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
                    })->orWhereHas('estructuraDepartamento', fn ($q) => $q->where('nombre', 'ilike', "%{$search}%"))
                        ->orWhereHas('estructuraServicio', fn ($q) => $q->where('nombre', 'ilike', "%{$search}%"))
                        ->orWhere('estado', 'ilike', "%{$search}%");
                });
            },
            [
                0 => null,
                1 => null,
                2 => null,
                3 => null,
                4 => 'periodo_anio',
                5 => 'estado',
                6 => 'updated_at',
                7 => null,
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
                    'departamento' => e($record->estructuraDepartamento?->nombre ?? '—'),
                    'servicio' => e($record->estructuraServicio?->nombre ?? '—'),
                    'periodo' => e($periodo),
                    'estado' => '<span class="badge '.$badgeClass.'">'.$badge.'</span>',
                    'actualizado' => e(optional($record->updated_at)->format('d/m/Y H:i') ?? '—'),
                    'acciones' => $action,
                ];
            },
            'updated_at',
            'desc'
        );
    }

    public function create(Request $request): View
    {
        $this->ensureCanCapture();

        return view('admin.bioestadistica.captura.create', [
            'formularios' => Formulario::where('estado', 'activo')->ordenSp()->get(),
            'establecimientos' => $this->allowedEstablishments(),
            'months' => $this->months(),
            'selectedEstablecimientoId' => $request->integer('establecimiento_id') ?: old('establecimiento_id'),
            'selectedFormularioId' => $request->integer('formulario_id') ?: old('formulario_id'),
            'selectedAnio' => $request->integer('periodo_anio') ?: old('periodo_anio', now()->year),
            'selectedMes' => $request->integer('periodo_mes') ?: old('periodo_mes', now()->subMonth()->month),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureCanCapture();
        $data = $this->validateContext($request);
        $this->ensureAllowedEstablishment((int) $data['establecimiento_id']);
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
                'Ya existe un registro para ese formulario, establecimiento, período y servicio.'
            );
        }

        try {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
        } catch (UniqueConstraintViolationException) {
            $exists = Record::where($lookup)->firstOrFail();

            return $this->redirectToCapture(
                $exists,
                'warning',
                'Ya existe un registro para ese formulario, establecimiento, período y servicio.'
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
        $forms = Formulario::where('estado', 'activo')->ordenSp()->get();
        $establishments = $this->allowedEstablishments()->filter(fn ($item) => $item->distrito_id);
        $establishments->load(['unidades.departamento', 'unidades.servicio']);
        $existing = Record::query()
            ->forUser($request->user())
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->get();

        $rows = collect();
        foreach ($establishments as $establecimiento) {
            $unidades = $establecimiento->unidades;
            $slices = $unidades->isEmpty() ? collect([null]) : $unidades;

            foreach ($slices as $unidad) {
                $missing = $forms->reject(function (Formulario $form) use ($existing, $establecimiento, $unidad) {
                    return $existing->contains(function (Record $record) use ($form, $establecimiento, $unidad) {
                        if ((int) $record->establecimiento_id !== (int) $establecimiento->id
                            || (int) $record->formulario_id !== (int) $form->id) {
                            return false;
                        }
                        if ($unidad === null) {
                            return $record->estructura_servicio_id === null;
                        }

                        return (int) $record->estructura_servicio_id === (int) $unidad->servicio_id;
                    });
                });

                if ($missing->isEmpty()) {
                    continue;
                }

                $rows->push([
                    'establecimiento' => $establecimiento,
                    'unidad' => $unidad,
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
            'formulario.secciones.fields.detalle.prestaciones',
            'establecimiento.distrito.departamento',
            'establecimiento.unidades.departamento',
            'establecimiento.unidades.servicio',
            'estructuraDepartamento',
            'estructuraServicio',
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
                $record->estructura_departamento_id ? (int) $record->estructura_departamento_id : null,
                $record->estructura_servicio_id ? (int) $record->estructura_servicio_id : null
            ),
            'establecimientoId' => (int) $record->establecimiento_id,
            'establecimientoNombre' => $record->establecimiento->nombre,
            'periodo_anio' => (int) $record->periodo_anio,
            'periodo_mes' => (int) $record->periodo_mes,
            'corteDepartamentoId' => $record->estructura_departamento_id,
            'corteServicioId' => $record->estructura_servicio_id,
            'corteEtiqueta' => $record->corteLabel(),
            'unidades' => $record->establecimiento->unidades,
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
            'observacion' => $request->input('observacion'),
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
            'observacion' => $request->input('observacion'),
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
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'estructura_servicio_id' => ['nullable', 'integer'],
        ]);

        $isNominative = $record->formulario->codigo === 'SP10'
            || $record->formulario->layout_type === 'nominativo';

        $corteUpdate = [];
        if ($request->exists('estructura_servicio_id')) {
            $corteUpdate = $this->corteUpdateForRecord(
                $record,
                $request->input('estructura_servicio_id')
            );
        }

        $departamentoId = $corteUpdate['estructura_departamento_id'] ?? $record->estructura_departamento_id;
        $servicioId = $corteUpdate['estructura_servicio_id'] ?? $record->estructura_servicio_id;

        $duplicate = Record::query()
            ->where('formulario_id', $record->formulario_id)
            ->where('establecimiento_id', $record->establecimiento_id)
            ->where('periodo_anio', $period['periodo_anio'])
            ->where('periodo_mes', $period['periodo_mes'])
            ->where('estructura_departamento_id', $departamentoId)
            ->where('estructura_servicio_id', $servicioId)
            ->where('id', '<>', $record->id)
            ->exists();
        if ($duplicate) {
            return back()->withErrors([
                'periodo_mes' => 'Ya existe un registro de este formulario, establecimiento, período y servicio.',
            ])->withInput();
        }

        $previousContext = $record->replicate();

        try {
            DB::transaction(function () use ($record, $period, $isNominative, $corteUpdate, $request) {
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
                    'updated_by' => $request->user()->id,
                ] + $corteUpdate);
                $record->refresh();
            });
        } catch (UniqueConstraintViolationException) {
            return back()->withErrors([
                'periodo_mes' => 'Ya existe un registro de este formulario, establecimiento, período y servicio.',
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
                ->with('success', 'Período y servicio actualizados.');
        }

        return back()->with('success', 'Período y servicio actualizados.');
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

    public function assignments(): View
    {
        abort_unless($this->managesAssignments(), 403);

        return view('admin.bioestadistica.captura.assignments', [
            'users' => User::role('Digitador Bioestadística')->orderBy('name')->get(),
            'establecimientos' => Establecimiento::with('distrito.departamento')->orderBy('nombre')->get(),
            'assignments' => UsuarioEstablecimiento::query()
                ->get()
                ->groupBy('user_id')
                ->map(fn ($rows) => $rows->pluck('establecimiento_id')->all()),
        ]);
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

    private function redirectToCapture(Record $record, string $level, string $message): RedirectResponse
    {
        $record->loadMissing('formulario');
        if ($record->formulario->codigo === 'SP10' || $record->formulario->layout_type === 'nominativo') {
            return redirect()->route('bioestadistica.hospitalizacion.spreadsheet', $record->spreadsheetParams())
                ->with($level, $message);
        }

        return redirect()->route('bioestadistica.captura.edit', $record)->with($level, $message);
    }

    private function validateContext(Request $request): array
    {
        return $request->validate([
            'formulario_id' => [
                'required',
                Rule::exists(Formulario::class, 'id')->where('estado', 'activo')->withoutTrashed(),
            ],
            'establecimiento_id' => ['required', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'estructura_servicio_id' => ['nullable', 'integer'],
        ]);
    }

    private function lookupForCapture(array $data, Formulario $formulario): array
    {
        $lookup = [
            'formulario_id' => $data['formulario_id'],
            'establecimiento_id' => $data['establecimiento_id'],
            'periodo_anio' => $data['periodo_anio'],
            'periodo_mes' => $data['periodo_mes'],
            'estructura_departamento_id' => null,
            'estructura_servicio_id' => null,
        ];

        $unidades = EstablecimientoServicio::query()
            ->where('establecimiento_id', $data['establecimiento_id'])
            ->get();
        if ($unidades->isEmpty()) {
            return $lookup;
        }

        $match = $unidades->firstWhere('servicio_id', (int) ($data['estructura_servicio_id'] ?? 0));
        if (! $match) {
            throw ValidationException::withMessages([
                'estructura_servicio_id' => 'Seleccione el departamento y servicio donde se carga la variable.',
            ]);
        }

        $lookup['estructura_departamento_id'] = $match->departamento_id;
        $lookup['estructura_servicio_id'] = $match->servicio_id;

        return $lookup;
    }

    /**
     * @return array{estructura_departamento_id: int, estructura_servicio_id: int}|array{}
     */
    private function corteUpdateForRecord(Record $record, mixed $servicioId): array
    {
        $unidades = EstablecimientoServicio::query()
            ->where('establecimiento_id', $record->establecimiento_id)
            ->get();
        if ($unidades->isEmpty()) {
            return [];
        }

        $selected = (int) $servicioId;
        $match = $unidades->firstWhere('servicio_id', $selected);
        if (! $match) {
            throw ValidationException::withMessages([
                'estructura_servicio_id' => 'Seleccione el departamento y servicio donde se carga la variable.',
            ]);
        }

        return [
            'estructura_departamento_id' => $match->departamento_id,
            'estructura_servicio_id' => $match->servicio_id,
        ];
    }

    private function managesAssignments(): bool
    {
        return request()->user()->hasAnyRole(['Administrador', 'Analista de Bioestadística']);
    }

    private function allowedEstablishments()
    {
        return Establecimiento::query()
            ->with('distrito.departamento')
            ->when(
                ! Record::userHasGlobalAccess(request()->user()),
                fn ($query) => $query->whereIn('id', Record::assignedEstablishmentIds(request()->user()))
            )
            ->orderBy('nombre')
            ->get();
    }

    private function ensureAllowedEstablishment(int $id): void
    {
        if (Record::userHasGlobalAccess(request()->user())) {
            return;
        }
        if (! in_array($id, Record::assignedEstablishmentIds(request()->user()), true)) {
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
