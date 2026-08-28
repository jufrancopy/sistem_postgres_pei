<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Imports\SpPlanillaImportService;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SpPlanillaImportController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('create', Record::class);
        abort_unless($request->user()->can('bio.record.create'), 403);

        return view('admin.bioestadistica.captura.import-sp.index');
    }

    public function analyze(Request $request, SpPlanillaImportService $service): RedirectResponse
    {
        $this->authorize('create', Record::class);
        abort_unless($request->user()->can('bio.record.create'), 403);

        $request->validate([
            'archivo' => ['required', 'file', 'mimes:xls,xlsx', 'max:20480'],
        ]);

        try {
            $preview = $service->analyze($request->file('archivo'), $request->user());
        } catch (\Throwable $exception) {
            if ($exception instanceof ValidationException) {
                throw $exception;
            }
            throw ValidationException::withMessages([
                'archivo' => $exception->getMessage(),
            ]);
        }

        $service->storePreview($preview);

        return redirect()
            ->route('bioestadistica.captura.import.summary')
            ->with('success', 'Planilla analizada. Revise el resumen de hojas antes de importar.');
    }

    public function summary(Request $request, SpPlanillaImportService $service): View|RedirectResponse
    {
        $this->authorize('create', Record::class);
        $preview = session(SpPlanillaImportService::SESSION_KEY);
        if (! is_array($preview)) {
            return redirect()
                ->route('bioestadistica.captura.import.index')
                ->with('warning', 'No hay análisis pendiente. Suba la planilla nuevamente.');
        }

        if ($request->hasAny(['establecimiento_id', 'periodo_anio', 'periodo_mes', 'estructura_servicio_id'])) {
            $preview = $service->applyPreviewContext($preview, array_filter([
                'establecimiento_id' => $request->filled('establecimiento_id') ? $request->integer('establecimiento_id') : null,
                'periodo_anio' => $request->filled('periodo_anio') ? $request->integer('periodo_anio') : null,
                'periodo_mes' => $request->filled('periodo_mes') ? $request->integer('periodo_mes') : null,
                'estructura_servicio_id' => $request->filled('estructura_servicio_id') ? $request->integer('estructura_servicio_id') : null,
            ], fn ($v) => $v !== null));
            $service->storePreview($preview);
        }

        $preview = $service->enrichWorkbookForSummary($preview);
        $service->storePreview($preview);

        return view('admin.bioestadistica.captura.import-sp.summary', [
            'preview' => $preview,
            'workbook' => $preview['workbook'] ?? ['hojas' => [], 'contexto' => []],
            'establecimientos' => $this->allowedEstablishments($request),
            'months' => $this->months(),
        ]);
    }

    public function preview(Request $request, SpPlanillaImportService $service): View|RedirectResponse
    {
        $this->authorize('create', Record::class);
        $preview = session(SpPlanillaImportService::SESSION_KEY);
        if (! is_array($preview)) {
            return redirect()
                ->route('bioestadistica.captura.import.index')
                ->with('warning', 'No hay análisis pendiente. Suba la planilla nuevamente.');
        }

        if ($request->filled('hoja')) {
            try {
                $preview = $service->activateSheet($preview, $request->string('hoja')->toString());
            } catch (ValidationException $exception) {
                return redirect()
                    ->route('bioestadistica.captura.import.summary')
                    ->withErrors($exception->errors());
            }
            $service->storePreview($preview);
        }

        if ($request->hasAny(['formulario_id', 'establecimiento_id', 'periodo_anio', 'periodo_mes', 'estructura_servicio_id'])) {
            $preview = $service->applyPreviewContext($preview, array_filter([
                'formulario_id' => $request->filled('formulario_id') ? $request->integer('formulario_id') : null,
                'establecimiento_id' => $request->filled('establecimiento_id') ? $request->integer('establecimiento_id') : null,
                'periodo_anio' => $request->filled('periodo_anio') ? $request->integer('periodo_anio') : null,
                'periodo_mes' => $request->filled('periodo_mes') ? $request->integer('periodo_mes') : null,
                'estructura_servicio_id' => $request->filled('estructura_servicio_id') ? $request->integer('estructura_servicio_id') : null,
            ], fn ($v) => $v !== null));
            $service->storePreview($preview);
        }

        $formulario = Formulario::find($preview['formulario_id'] ?? 0);
        $prestaciones = collect();
        if ($formulario) {
            $field = $formulario->secciones()
                ->with(['fields' => fn ($q) => $q->where('type', 'tabla')->orderByDesc('required')->orderBy('orden')])
                ->get()
                ->flatMap->fields
                ->first();
            $prestaciones = $field?->rowItems() ?? collect();
        }

        $detectado = $preview['detectado'] ?? [];

        return view('admin.bioestadistica.captura.import-sp.preview', [
            'preview' => $preview,
            'detectado' => $detectado,
            'formularios' => $service->selectableFormularios(),
            'establecimientos' => $this->allowedEstablishments($request),
            'months' => $this->months(),
            'prestaciones' => $prestaciones,
        ]);
    }

    public function confirm(Request $request, SpPlanillaImportService $service): RedirectResponse
    {
        $this->authorize('create', Record::class);
        abort_unless($request->user()->can('bio.record.create'), 403);

        $data = $request->validate([
            'token' => ['required', 'string'],
            'formulario_id' => [
                'required',
                Rule::exists(Formulario::class, 'id')->where('estado', 'activo'),
            ],
            'establecimiento_id' => ['required', Rule::exists(Establecimiento::class, 'id')],
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'estructura_servicio_id' => ['nullable', 'integer'],
            'sobrescribir' => ['nullable', 'boolean'],
            'decisiones' => ['nullable', 'array'],
            'decisiones.*' => ['nullable', 'string'],
        ]);

        $preview = $service->pullPreview($data['token']);
        if (! $preview) {
            throw ValidationException::withMessages([
                'token' => 'La vista previa expiró. Vuelva a analizar la planilla.',
            ]);
        }

        $preview = $service->applyPreviewContext($preview, [
            'formulario_id' => (int) $data['formulario_id'],
            'establecimiento_id' => (int) $data['establecimiento_id'],
            'periodo_anio' => (int) $data['periodo_anio'],
            'periodo_mes' => (int) $data['periodo_mes'],
            'estructura_servicio_id' => $data['estructura_servicio_id'] ?? null,
        ]);

        try {
            $record = $service->confirm(
                $request->user(),
                $preview,
                $data,
                $data['decisiones'] ?? [],
                $request->boolean('sobrescribir')
            );
        } finally {
            $service->forgetPreview();
        }

        return redirect()
            ->route('bioestadistica.captura.edit', $record)
            ->with('success', 'Planilla importada en borrador. Revise los totales antes de enviar.');
    }

    public function confirmBatch(Request $request, SpPlanillaImportService $service): RedirectResponse
    {
        $this->authorize('create', Record::class);
        abort_unless($request->user()->can('bio.record.create'), 403);

        $data = $request->validate([
            'token' => ['required', 'string'],
            'establecimiento_id' => ['required', Rule::exists(Establecimiento::class, 'id')],
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'estructura_servicio_id' => ['nullable', 'integer'],
            'sobrescribir' => ['nullable', 'boolean'],
            'hojas' => ['required', 'array', 'min:1'],
            'hojas.*' => ['required', 'string'],
        ]);

        $preview = session(SpPlanillaImportService::SESSION_KEY);
        if (! is_array($preview) || ($preview['token'] ?? null) !== $data['token']) {
            throw ValidationException::withMessages([
                'token' => 'La vista previa expiró. Vuelva a analizar la planilla.',
            ]);
        }

        $preview = $service->applyPreviewContext($preview, [
            'establecimiento_id' => (int) $data['establecimiento_id'],
            'periodo_anio' => (int) $data['periodo_anio'],
            'periodo_mes' => (int) $data['periodo_mes'],
            'estructura_servicio_id' => $data['estructura_servicio_id'] ?? null,
        ]);

        $resultado = $service->confirmBatch(
            $request->user(),
            $preview,
            $data,
            $data['hojas'],
            $request->boolean('sobrescribir')
        );

        $service->forgetPreview();

        if ($resultado['ok'] === [] && $resultado['errores'] !== []) {
            return redirect()
                ->route('bioestadistica.captura.import.index')
                ->with('warning', 'No se importó ningún SP. '.collect($resultado['errores'])->pluck('mensaje')->first());
        }

        $mensajes = [];
        foreach ($resultado['ok'] as $item) {
            $texto = $item['sp'].' → borrador #'.$item['record_id'].' ('.$item['filas'].' filas)';
            if ($item['parcial'] ?? false) {
                $texto .= ' — importación parcial';
            }
            $mensajes[] = $texto;
        }

        $redirect = redirect()->route('bioestadistica.captura.index');
        if ($mensajes !== []) {
            $redirect->with('success', 'Importación en lote: '.implode(' · ', $mensajes));
        }
        if ($resultado['omitidos'] !== []) {
            $redirect->with('warning', 'Omitidos: '.collect($resultado['omitidos'])->map(
                fn (array $o) => ($o['sp'] ?? $o['hoja']).': '.$o['mensaje']
            )->implode(' | '));
        }
        if ($resultado['errores'] !== []) {
            $redirect->with('info', 'Errores: '.collect($resultado['errores'])->map(
                fn (array $e) => ($e['sp'] ?? $e['hoja']).': '.$e['mensaje']
            )->implode(' | '));
        }

        return $redirect;
    }

    public function discard(SpPlanillaImportService $service): RedirectResponse
    {
        $service->forgetPreview();

        return redirect()
            ->route('bioestadistica.captura.import.index')
            ->with('success', 'Análisis descartado. El archivo no quedó guardado en el servidor.');
    }

    private function allowedEstablishments(Request $request)
    {
        return Establecimiento::query()
            ->when(
                ! Record::userHasGlobalAccess($request->user()),
                fn ($query) => $query->whereIn('id', Record::assignedEstablishmentIds($request->user()))
            )
            ->orderBy('nombre')
            ->get();
    }

    private function months(): array
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
    }
}