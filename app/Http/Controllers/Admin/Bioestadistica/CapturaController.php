<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\UsuarioEstablecimiento;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CapturaController extends Controller
{
    public function index(Request $request): View
    {
        $records = Record::query()
            ->forUser($request->user())
            ->with(['formulario', 'establecimiento.distrito.departamento'])
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->string('estado')))
            ->when($request->filled('periodo_anio'), fn ($query) => $query->where('periodo_anio', $request->integer('periodo_anio')))
            ->when($request->filled('periodo_mes'), fn ($query) => $query->where('periodo_mes', $request->integer('periodo_mes')))
            ->when($request->filled('formulario_id'), fn ($query) => $query->where('formulario_id', $request->integer('formulario_id')))
            ->latest('updated_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.bioestadistica.captura.index', [
            'records' => $records,
            'formularios' => Formulario::where('estado', 'activo')->orderBy('codigo')->get(),
            'establecimientos' => $this->allowedEstablishments(),
            'months' => $this->months(),
        ]);
    }

    public function create(): View
    {
        $this->ensureCanCapture();

        return view('admin.bioestadistica.captura.create', [
            'formularios' => Formulario::where('estado', 'activo')->orderBy('codigo')->get(),
            'establecimientos' => $this->allowedEstablishments(),
            'months' => $this->months(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureCanCapture();
        $data = $this->validateContext($request);
        $this->ensureAllowedEstablishment((int) $data['establecimiento_id']);
        $formulario = Formulario::findOrFail($data['formulario_id']);
        if ($formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo') {
            return redirect()->route('bioestadistica.hospitalizacion.create', [
                'establecimiento_id' => $data['establecimiento_id'],
            ])->with('success', 'SP10 se captura como episodios nominativos en Hospitalización.');
        }
        abort_unless(
            Establecimiento::whereKey($data['establecimiento_id'])->whereNotNull('distrito_id')->exists(),
            422,
            'El establecimiento debe tener distrito asignado para iniciar la captura.'
        );

        $lookup = [
            'formulario_id' => $data['formulario_id'],
            'establecimiento_id' => $data['establecimiento_id'],
            'periodo_anio' => $data['periodo_anio'],
            'periodo_mes' => $data['periodo_mes'],
        ];
        $exists = Record::where($lookup)->first();
        if ($exists) {
            return redirect()->route('bioestadistica.captura.edit', $exists)
                ->with('warning', 'Ya existe un registro para ese formulario, establecimiento y período.');
        }

        try {
            $record = Record::create($lookup + [
                'estado' => Record::ESTADO_BORRADOR,
                'created_by' => $request->user()->id,
            ]);
        } catch (UniqueConstraintViolationException) {
            $exists = Record::where($lookup)->firstOrFail();

            return redirect()->route('bioestadistica.captura.edit', $exists)
                ->with('warning', 'Ya existe un registro para ese formulario, establecimiento y período.');
        }

        return redirect()->route('bioestadistica.captura.edit', $record)
            ->with('success', 'Período creado. Complete los datos y guarde el borrador.');
    }

    public function pending(Request $request): View
    {
        $year = $request->integer('periodo_anio', now()->subMonth()->year);
        $month = $request->integer('periodo_mes', now()->subMonth()->month);
        $forms = Formulario::where('estado', 'activo')->orderBy('codigo')->get();
        $establishments = $this->allowedEstablishments()->filter(fn ($item) => $item->distrito_id);
        $existing = Record::query()
            ->forUser($request->user())
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->get()
            ->groupBy('establecimiento_id')
            ->map(fn ($records) => $records->pluck('formulario_id'));

        $rows = $establishments->map(function ($establecimiento) use ($forms, $existing) {
            $loaded = $existing->get($establecimiento->id, collect());
            $missing = $forms->reject(fn ($form) => $loaded->contains($form->id));

            return $missing->isEmpty() ? null : [
                'establecimiento' => $establecimiento,
                'missing' => $missing,
            ];
        })->filter()->values();

        return view('admin.bioestadistica.captura.pending', [
            'rows' => $rows,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'months' => $this->months(),
        ]);
    }

    public function edit(Record $record): View|RedirectResponse
    {
        $this->ensureCanView($record);
        $record->load([
            'formulario.secciones.fields.catalogo.items',
            'establecimiento.distrito.departamento',
            'values.field',
        ]);
        if ($record->formulario->codigo === 'SP10' || $record->formulario->layout_type === 'nominativo') {
            return redirect()->route('bioestadistica.hospitalizacion.index', [
                'establecimiento_id' => $record->establecimiento_id,
                'periodo_anio' => $record->periodo_anio,
                'periodo_mes' => $record->periodo_mes,
            ])->with('success', 'El consolidado SP10 es de solo lectura. El detalle nominativo está en Hospitalización.');
        }

        return view('admin.bioestadistica.captura.edit', [
            'record' => $record,
            'valuesByField' => $record->values->keyBy('field_id'),
            'months' => $this->months(),
        ]);
    }

    public function update(Request $request, Record $record, RecordCaptureService $capture): RedirectResponse
    {
        $this->ensureCanEdit($record);
        if ($record->formulario->layout_type === 'nominativo') {
            abort(422, 'El consolidado SP10 no se edita en captura.');
        }
        $capture->save($record, $request->input('values', []));
        $record->update(['observacion' => $request->input('observacion')]);

        return back()->with('success', 'Borrador guardado.');
    }

    public function updatePeriod(
        Request $request,
        Record $record,
        IndicatorCacheService $cache
    ): RedirectResponse {
        $this->ensureCanView($record);
        $this->authorize('update', $record);
        if ($record->formulario->layout_type === 'nominativo') {
            abort(422, 'El período de SP10 se edita desde la planilla de Hospitalización.');
        }

        $period = $request->validate([
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
        ]);
        $duplicate = Record::query()
            ->where('formulario_id', $record->formulario_id)
            ->where('establecimiento_id', $record->establecimiento_id)
            ->where('periodo_anio', $period['periodo_anio'])
            ->where('periodo_mes', $period['periodo_mes'])
            ->where('id', '<>', $record->id)
            ->exists();
        if ($duplicate) {
            return back()->withErrors([
                'periodo_mes' => 'Ya existe un registro de este formulario y establecimiento para el período seleccionado.',
            ])->withInput();
        }

        $previousContext = $record->replicate();

        try {
            DB::transaction(function () use ($record, $period) {
                $locked = Record::query()->whereKey($record->id)->lockForUpdate()->firstOrFail();
                $this->realignMatrices(
                    $locked,
                    (int) $period['periodo_anio'],
                    (int) $period['periodo_mes']
                );
                $locked->update([
                    'periodo_anio' => (int) $period['periodo_anio'],
                    'periodo_mes' => (int) $period['periodo_mes'],
                ]);
                $record->refresh();
            });
        } catch (UniqueConstraintViolationException) {
            return back()->withErrors([
                'periodo_mes' => 'Ya existe un registro de este formulario y establecimiento para el período seleccionado.',
            ])->withInput();
        }

        $cache->invalidateForRecord($previousContext);
        $cache->invalidateForRecord($record);

        return back()->with('success', 'Período estadístico actualizado.');
    }

    public function submit(Request $request, Record $record, RecordCaptureService $capture): RedirectResponse
    {
        $this->authorize('submit', $record);
        $this->ensureCanEdit($record);
        $capture->save($record, $this->savedValues($record));
        $record->submit($request->user()->id);

        return redirect()->route('bioestadistica.captura.index')
            ->with('success', 'Registro enviado para aprobación.');
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
        ]);
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
