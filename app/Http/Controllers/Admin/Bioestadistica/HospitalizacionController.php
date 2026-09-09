<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Application\Bioestadistica\Imports\HospEpisodioImporter;
use App\Http\Controllers\Admin\Bioestadistica\Concerns\BuildsCaptureNavigator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bioestadistica\HospEpisodioBatchRequest;
use App\Http\Requests\Bioestadistica\HospEpisodioRequest;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoServicio;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\ImportJob;
use App\Models\Bioestadistica\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HospitalizacionController extends Controller
{
    use BuildsCaptureNavigator;

    public function index(Request $request): View
    {
        abort_unless($request->user()->can('bio.hosp.view'), 403);
        $episodes = HospEpisodio::query()
            ->forUser($request->user())
            ->with(['establecimiento.distrito.departamento'])
            ->when($request->filled('establecimiento_id'), fn ($query) => $query->where('establecimiento_id', $request->integer('establecimiento_id')))
            ->when($request->filled('periodo_anio'), fn ($query) => $query->where('periodo_anio', $request->integer('periodo_anio')))
            ->when($request->filled('periodo_mes'), fn ($query) => $query->where('periodo_mes', $request->integer('periodo_mes')))
            ->when($request->filled('servicio'), fn ($query) => $query->where('servicio', $request->string('servicio')))
            ->when($request->filled('tipo_alta'), fn ($query) => $query->where('tipo_alta', $request->string('tipo_alta')))
            ->when($request->filled('cie10'), fn ($query) => $query->where('cie10', strtoupper((string) $request->string('cie10'))))
            ->latest('fecha_ingreso')
            ->paginate(30)
            ->withQueryString();

        $episodes->getCollection()->transform(function (HospEpisodio $episodio) use ($request) {
            $episodio->setAttribute('cedula_visible', $episodio->visibleCedula($request->user()));

            return $episodio;
        });

        return view('admin.bioestadistica.hospitalizacion.index', [
            'episodios' => $episodes,
            'establecimientos' => $this->allowedEstablishments(),
            'servicios' => HospEpisodio::SERVICIOS,
            'tiposAlta' => HospEpisodio::TIPOS_ALTA,
            'months' => $this->months(),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->can('bio.hosp.manage'), 403);
        $defaultPeriod = now()->subMonth();

        return view('admin.bioestadistica.hospitalizacion.form', [
            'episodio' => new HospEpisodio([
                'establecimiento_id' => $request->integer('establecimiento_id') ?: null,
                'periodo_anio' => $request->integer('periodo_anio', (int) $defaultPeriod->year),
                'periodo_mes' => $request->integer('periodo_mes', (int) $defaultPeriod->month),
                'fecha_ingreso' => now()->toDateString(),
            ]),
            'establecimientos' => $this->allowedEstablishments(),
            'servicios' => HospEpisodio::SERVICIOS,
            'tiposAlta' => HospEpisodio::TIPOS_ALTA,
            'tiposCirugia' => HospEpisodio::TIPOS_CIRUGIA,
            'months' => $this->months(),
        ]);
    }

    public function store(HospEpisodioRequest $request, HospitalizationService $service): RedirectResponse
    {
        $this->authorize('create', HospEpisodio::class);
        $episodio = $service->save($request->validated(), $request->user());

        return redirect()->route('bioestadistica.hospitalizacion.edit', $episodio)
            ->with('success', 'Episodio guardado. El consolidado SP10 se actualizó para el período seleccionado.');
    }

    public function edit(Request $request, HospEpisodio $episodio, AuditService $audit): View
    {
        $this->authorize('view', $episodio);
        abort_unless($request->user()->can('bio.hosp.view'), 403);
        abort_unless($episodio->isAccessibleBy($request->user()), 403);
        $episodio->setAttribute('cedula_visible', $episodio->visibleCedula($request->user()));
        $audit->recordExplicit('view', HospEpisodio::class, (int) $episodio->id, null, [
            'pii' => $request->user()->can('bio.hosp.view_pii'),
        ], ['phase' => 'nominative_read']);

        return view('admin.bioestadistica.hospitalizacion.form', [
            'episodio' => $episodio,
            'establecimientos' => $this->allowedEstablishments(),
            'servicios' => HospEpisodio::SERVICIOS,
            'tiposAlta' => HospEpisodio::TIPOS_ALTA,
            'tiposCirugia' => HospEpisodio::TIPOS_CIRUGIA,
            'months' => $this->months(),
        ]);
    }

    public function spreadsheet(Request $request): View|RedirectResponse
    {
        abort_unless($request->user()->can('bio.hosp.manage'), 403);
        $establishmentId = $request->integer('establecimiento_id');
        $year = $request->integer('periodo_anio');
        $month = $request->integer('periodo_mes');
        if ($establishmentId < 1 || $year < 1990 || $year > 2100 || $month < 1 || $month > 12) {
            return redirect()->route('bioestadistica.captura.index', ['nueva' => 1])
                ->with('warning', 'Para cargar SP10 seleccione establecimiento y período estadístico, igual que en los demás formularios.');
        }

        $establishments = $this->allowedEstablishments();
        abort_unless($establishments->contains('id', $establishmentId), 403);
        $establecimiento = $establishments->firstWhere('id', $establishmentId);
        $establecimiento->load(['unidades.departamento', 'unidades.servicio']);
        $formulario = Formulario::where('codigo', 'SP10')->firstOrFail();
        [$departamentoId, $servicioId] = $this->optionalCorte(
            $establishmentId,
            $request->input('estructura_servicio_id')
        );
        $recordQuery = Record::query()
            ->where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $establishmentId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month);
        if ($servicioId) {
            $record = $recordQuery->where('estructura_servicio_id', $servicioId)->first();
        } else {
            $record = $recordQuery->first();
        }
        $record ??= Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establishmentId,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'estructura_departamento_id' => $departamentoId,
            'estructura_servicio_id' => $servicioId,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);
        $record->load(['formulario', 'establecimiento', 'estructuraDepartamento', 'estructuraServicio']);

        $episodes = HospEpisodio::query()
            ->where('establecimiento_id', $establishmentId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->where(function ($query) use ($record) {
                $query->where('record_id', $record->id)
                    ->orWhereNull('record_id');
            })
            ->orderBy('fecha_ingreso')
            ->orderBy('id')
            ->limit(200)
            ->get();
        $episodes->each(function (HospEpisodio $episode) use ($request) {
            $episode->setAttribute('cedula_visible', $episode->visibleCedula($request->user()));
        });

        return view('admin.bioestadistica.hospitalizacion.spreadsheet', [
            'record' => $record,
            'episodios' => $episodes,
            'establecimiento' => $establecimiento,
            'establecimientoId' => $establishmentId,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'months' => $this->months(),
            'servicios' => HospEpisodio::SERVICIOS,
            'tiposAlta' => HospEpisodio::TIPOS_ALTA,
            'tiposCirugia' => HospEpisodio::TIPOS_CIRUGIA,
            'siblingPlanillas' => $this->captureNavigator(
                $establishmentId,
                $year,
                $month,
                (int) $formulario->id,
                $record->estructura_departamento_id ? (int) $record->estructura_departamento_id : null,
                $record->estructura_servicio_id ? (int) $record->estructura_servicio_id : null
            ),
            'establecimientoNombre' => $establecimiento->nombre,
            'unidades' => $establecimiento->unidades,
            'corteServicioId' => $record->estructura_servicio_id,
            'corteEtiqueta' => $record->corteLabel(),
        ]);
    }

    public function saveSpreadsheet(
        HospEpisodioBatchRequest $request,
        HospitalizationService $service,
        AuditService $audit
    ): RedirectResponse {
        $data = $request->validated();
        $formulario = Formulario::where('codigo', 'SP10')->firstOrFail();
        [$departamentoId, $servicioId] = $this->optionalCorte(
            (int) $data['establecimiento_id'],
            $data['estructura_servicio_id'] ?? null
        );
        $record = Record::firstOrCreate([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $data['establecimiento_id'],
            'periodo_anio' => $data['periodo_anio'],
            'periodo_mes' => $data['periodo_mes'],
            'estructura_departamento_id' => $departamentoId,
            'estructura_servicio_id' => $servicioId,
        ], [
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);
        abort_unless($record->isEditable(), 422, 'El registro SP10 no está disponible para edición.');

        $summary = $service->saveBatch(
            (int) $data['establecimiento_id'],
            (int) $data['periodo_anio'],
            (int) $data['periodo_mes'],
            $data['rows'],
            $request->user(),
            $record
        );

        $audit->recordBatch('import', HospEpisodio::class, null, $summary, [
            'establecimiento_id' => (int) $data['establecimiento_id'],
            'periodo_anio' => (int) $data['periodo_anio'],
            'periodo_mes' => (int) $data['periodo_mes'],
            'estructura_servicio_id' => $servicioId,
        ], ['phase' => 'spreadsheet']);

        return redirect()->route('bioestadistica.hospitalizacion.spreadsheet', $record->spreadsheetParams())->with(
            'success',
            "Planilla guardada: {$summary['creados']} creados, "
            ."{$summary['actualizados']} actualizados y {$summary['eliminados']} eliminados."
        );
    }

    public function autosaveSpreadsheet(
        HospEpisodioBatchRequest $request,
        HospitalizationService $service
    ): JsonResponse {
        $data = $request->validated();
        $formulario = Formulario::where('codigo', 'SP10')->firstOrFail();
        [$departamentoId, $servicioId] = $this->optionalCorte(
            (int) $data['establecimiento_id'],
            $data['estructura_servicio_id'] ?? null
        );
        $record = Record::firstOrCreate([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $data['establecimiento_id'],
            'periodo_anio' => $data['periodo_anio'],
            'periodo_mes' => $data['periodo_mes'],
            'estructura_departamento_id' => $departamentoId,
            'estructura_servicio_id' => $servicioId,
        ], [
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        if (! $record->isEditable()) {
            return response()->json([
                'ok' => false,
                'message' => 'El registro SP10 no está disponible para edición.',
            ], 422);
        }

        $rows = $data['rows'] ?? [];
        if ($rows === []) {
            return response()->json([
                'ok' => true,
                'saved_at' => now()->timezone(config('app.timezone'))->format('H:i:s'),
                'empty' => true,
            ]);
        }

        $summary = $service->saveBatch(
            (int) $data['establecimiento_id'],
            (int) $data['periodo_anio'],
            (int) $data['periodo_mes'],
            $rows,
            $request->user(),
            $record,
            false
        );

        return response()->json([
            'ok' => true,
            'saved_at' => now()->timezone(config('app.timezone'))->format('H:i:s'),
            'summary' => $summary,
        ]);
    }

    public function update(HospEpisodioRequest $request, HospEpisodio $episodio, HospitalizationService $service): RedirectResponse
    {
        $this->authorize('update', $episodio);
        abort_unless($episodio->isAccessibleBy($request->user()), 403);
        $data = $request->validated();
        if (! $request->user()->can('bio.hosp.view_pii') && empty($data['cedula'])) {
            $data['cedula'] = $episodio->cedula;
        }
        $service->save($data, $request->user(), $episodio);

        return back()->with('success', 'Episodio actualizado y períodos recalculados.');
    }

    public function destroy(Request $request, HospEpisodio $episodio, HospitalizationService $service): RedirectResponse
    {
        $this->authorize('delete', $episodio);
        abort_unless($request->user()->can('bio.hosp.manage'), 403);
        abort_unless($episodio->isAccessibleBy($request->user()), 403);
        $service->delete($episodio, $request->user());

        return redirect()->route('bioestadistica.hospitalizacion.index')
            ->with('success', 'Episodio eliminado y consolidado recalculado.');
    }

    public function panel(Request $request, HospitalizationService $service): View
    {
        abort_unless($request->user()->can('bio.hosp.view'), 403);
        $establishments = $this->allowedEstablishments();
        $establecimientoId = $request->integer('establecimiento_id', (int) $establishments->first()?->id);
        abort_unless($establishments->contains('id', $establecimientoId) || Record::userHasGlobalAccess($request->user()), 403);
        $year = $request->integer('periodo_anio', (int) now()->subMonth()->year);
        $month = $request->integer('periodo_mes', (int) now()->subMonth()->month);
        $panel = $establecimientoId
            ? $service->panel($establecimientoId, $year, $month)
            : ['actual' => [], 'anterior' => [], 'tendencia' => []];

        return view('admin.bioestadistica.hospitalizacion.panel', [
            'panel' => $panel,
            'establecimientoId' => $establecimientoId,
            'periodo_anio' => $year,
            'periodo_mes' => $month,
            'establecimientos' => $establishments,
            'months' => $this->months(),
        ]);
    }

    public function consolidate(Request $request, HospitalizationService $service, AuditService $audit): RedirectResponse
    {
        abort_unless($request->user()->can('bio.hosp.manage'), 403);
        $data = $request->validate([
            'establecimiento_id' => ['required', 'integer'],
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
        ]);
        if (! Record::userHasGlobalAccess($request->user())
            && ! in_array((int) $data['establecimiento_id'], Record::assignedEstablishmentIds($request->user()), true)) {
            abort(403);
        }
        $record = $service->consolidate(
            (int) $data['establecimiento_id'],
            (int) $data['periodo_anio'],
            (int) $data['periodo_mes'],
            $request->user()
        );
        $audit->recordBatch('import', Record::class, (int) $record->id, [
            'episodios' => HospEpisodio::query()
                ->where('establecimiento_id', $data['establecimiento_id'])
                ->where('periodo_anio', $data['periodo_anio'])
                ->where('periodo_mes', $data['periodo_mes'])
                ->count(),
        ], [
            'establecimiento_id' => (int) $data['establecimiento_id'],
            'periodo_anio' => (int) $data['periodo_anio'],
            'periodo_mes' => (int) $data['periodo_mes'],
        ], ['phase' => 'consolidate']);

        return back()->with('success', "SP10 consolidado para el período {$data['periodo_mes']}/{$data['periodo_anio']} (registro #{$record->id}).");
    }

    public function importForm(): View
    {
        abort_unless(request()->user()->can('bio.hosp.manage'), 403);

        return view('admin.bioestadistica.hospitalizacion.import');
    }

    public function import(Request $request, HospEpisodioImporter $importer, AuditService $audit): RedirectResponse
    {
        abort_unless($request->user()->can('bio.hosp.manage'), 403);
        $data = $request->validate([
            'archivo' => ['required', 'file', 'mimes:xls,xlsx', 'max:20480'],
        ]);
        $file = $data['archivo'];
        $path = $file->storeAs(
            'bioestadistica/imports',
            now()->format('YmdHis').'-sp10-'.bin2hex(random_bytes(4)).'.'.$file->getClientOriginalExtension(),
            'local'
        );
        $absolute = Storage::disk('local')->path($path);
        $job = ImportJob::create([
            'tipo' => 'generico',
            'estado' => ImportJob::ESTADO_ANALIZADO,
            'archivo_original' => $file->getClientOriginalName(),
            'archivo_path' => $path,
            'checksum' => hash_file('sha256', $absolute),
            'analisis' => ['tipo' => 'hosp_episodios'],
            'created_by' => $request->user()->id,
        ]);
        try {
            $summary = $audit->withoutAuditing(fn () => $importer->import($absolute, $request->user(), $job->id));
            $job->update([
                'estado' => ImportJob::ESTADO_COMPLETADO,
                'resumen' => $summary,
                'confirmed_by' => $request->user()->id,
                'confirmed_at' => now(),
            ]);
            $audit->recordBatch('import', ImportJob::class, (int) $job->id, [
                'registros_creados' => (int) ($summary['registros_creados'] ?? 0),
                'registros_actualizados' => (int) ($summary['registros_actualizados'] ?? 0),
                'filas_omitidas' => (int) ($summary['filas_omitidas'] ?? 0),
            ], [
                'tipo' => 'hosp_episodios',
            ], ['phase' => 'commit']);
        } catch (\Throwable $exception) {
            $job->update(['estado' => ImportJob::ESTADO_ERROR, 'error' => $exception->getMessage()]);
            throw ValidationException::withMessages(['archivo' => $exception->getMessage()]);
        }

        return redirect()->route('bioestadistica.hospitalizacion.index')
            ->with('success', "Importación SP10: {$summary['registros_creados']} creados, {$summary['registros_actualizados']} actualizados, {$summary['filas_omitidas']} omitidas.");
    }

    public function export(Request $request, AuditService $audit): StreamedResponse
    {
        $this->authorize('export', HospEpisodio::class);
        abort_unless($request->user()->can('bio.hosp.export'), 403);
        $query = HospEpisodio::query()
            ->forUser($request->user())
            ->when($request->filled('establecimiento_id'), fn ($q) => $q->where('establecimiento_id', $request->integer('establecimiento_id')))
            ->when($request->filled('periodo_anio'), fn ($q) => $q->where('periodo_anio', $request->integer('periodo_anio')))
            ->when($request->filled('periodo_mes'), fn ($q) => $q->where('periodo_mes', $request->integer('periodo_mes')))
            ->orderBy('id');

        $audit->recordBatch('export', HospEpisodio::class, null, [
            'filas' => (clone $query)->count(),
        ], array_filter([
            'establecimiento_id' => $request->integer('establecimiento_id') ?: null,
            'periodo_anio' => $request->integer('periodo_anio') ?: null,
            'periodo_mes' => $request->integer('periodo_mes') ?: null,
        ]), ['phase' => 'hosp_csv']);

        $user = $request->user();

        return response()->streamDownload(function () use ($query, $user) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'establecimiento', 'periodo', 'nro_patronal', 'cedula', 'sexo', 'seguro', 'edad',
                'ciudad_residencia', 'ingreso', 'egreso', 'dias_internacion', 'servicio',
                'diagnostico', 'cie10', 'cirugia_mayor', 'cirugia_menor', 'tipo_alta',
                'cesarea', 'recien_nacido', 'rn_sexo', 'rn_peso',
            ]);
            $query->with('establecimiento')->chunkById(250, function ($rows) use ($out, $user) {
                foreach ($rows as $episodio) {
                    fputcsv($out, [
                        $episodio->establecimiento->nombre ?? '',
                        sprintf('%02d/%d', $episodio->periodo_mes, $episodio->periodo_anio),
                        $episodio->nro_patronal,
                        $episodio->visibleCedula($user),
                        $episodio->sexo,
                        $episodio->seguro,
                        $episodio->edad,
                        $episodio->ciudad_residencia,
                        optional($episodio->fecha_ingreso)->format('Y-m-d'),
                        optional($episodio->fecha_egreso)->format('Y-m-d'),
                        $episodio->stayDays(),
                        $episodio->servicio,
                        $episodio->diagnostico,
                        $episodio->cie10,
                        $episodio->tipo_cirugia === 'MAYOR' ? '1' : '0',
                        $episodio->tipo_cirugia === 'MENOR' ? '1' : '0',
                        $episodio->tipo_alta,
                        $episodio->cesarea ? '1' : '0',
                        $episodio->recien_nacido ? '1' : '0',
                        $episodio->rn_sexo,
                        $episodio->rn_peso,
                    ]);
                }
            });
            fclose($out);
        }, 'sp10-episodios.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * @return array{0: int|null, 1: int|null}
     */
    private function optionalCorte(int $establecimientoId, mixed $servicioId, bool $requiredWhenAssigned = false): array
    {
        $unidades = EstablecimientoServicio::query()
            ->where('establecimiento_id', $establecimientoId)
            ->get();
        if ($unidades->isEmpty()) {
            return [null, null];
        }
        $match = $unidades->firstWhere('servicio_id', (int) $servicioId);
        if (! $match) {
            if (! $requiredWhenAssigned && ($servicioId === null || $servicioId === '')) {
                return [null, null];
            }
            throw ValidationException::withMessages([
                'estructura_servicio_id' => 'Seleccione el departamento y servicio donde se carga la variable.',
            ]);
        }

        return [(int) $match->departamento_id, (int) $match->servicio_id];
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

    private function months(): array
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
    }
}
