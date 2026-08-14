<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\RecordCaptureService;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\UsuarioEstablecimiento;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function edit(Record $record): View
    {
        $this->ensureCanView($record);
        $record->load([
            'formulario.secciones.fields.catalogo.items',
            'establecimiento.distrito.departamento',
            'values.field',
        ]);

        return view('admin.bioestadistica.captura.edit', [
            'record' => $record,
            'valuesByField' => $record->values->keyBy('field_id'),
        ]);
    }

    public function update(Request $request, Record $record, RecordCaptureService $capture): RedirectResponse
    {
        $this->ensureCanEdit($record);
        $capture->save($record, $request->input('values', []));
        $record->update(['observacion' => $request->input('observacion')]);

        return back()->with('success', 'Borrador guardado.');
    }

    public function submit(Request $request, Record $record, RecordCaptureService $capture): RedirectResponse
    {
        $this->ensureCanEdit($record);
        $capture->save($record, $this->savedValues($record));
        $record->submit($request->user()->id);

        return redirect()->route('bioestadistica.captura.index')
            ->with('success', 'Registro enviado para aprobación.');
    }

    public function approve(Request $request, Record $record): RedirectResponse
    {
        abort_unless($request->user()->can('bio.record.approve'), 403);
        $this->ensureCanView($record);
        $record->approve($request->user()->id);

        return back()->with('success', 'Registro aprobado.');
    }

    public function reject(Request $request, Record $record): RedirectResponse
    {
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

    public function updateAssignments(Request $request, User $user): RedirectResponse
    {
        abort_unless($this->managesAssignments(), 403);
        abort_unless($user->hasRole('Digitador Bioestadística'), 422, 'El usuario debe tener el rol Digitador Bioestadística.');
        $data = $request->validate([
            'establecimiento_ids' => ['nullable', 'array'],
            'establecimiento_ids.*' => ['integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
        ]);

        UsuarioEstablecimiento::where('user_id', $user->id)->delete();
        foreach ($data['establecimiento_ids'] ?? [] as $establishmentId) {
            UsuarioEstablecimiento::create(['user_id' => $user->id, 'establecimiento_id' => $establishmentId]);
        }

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
        abort_unless($record->isAccessibleBy(request()->user()), 403, 'No tiene alcance sobre este registro.');
    }

    private function ensureCanCapture(): void
    {
        abort_unless(request()->user()->can('bio.record.create'), 403);
    }

    private function ensureCanEdit(Record $record): void
    {
        $this->ensureCanView($record);
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
