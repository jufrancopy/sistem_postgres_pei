<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Imports\BioestadisticaDedicatedImporter;
use App\Application\Bioestadistica\Imports\ExcelImportAnalyzer;
use App\Application\Bioestadistica\Imports\FormulariosSpImporter;
use App\Application\Bioestadistica\Imports\GenericFormImporter;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\ImportJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ImportacionController extends Controller
{
    public function index(): View
    {
        $this->authorize('view', ImportJob::class);
        return view('admin.bioestadistica.importaciones.index', [
            'imports' => ImportJob::with('creator')->latest()->limit(500)->get(),
        ]);
    }

    public function store(Request $request, ExcelImportAnalyzer $analyzer): RedirectResponse
    {
        $this->authorize('execute', ImportJob::class);
        $data = $request->validate([
            'archivo' => ['required', 'file', 'mimes:xls,xlsx', 'max:20480'],
        ]);
        $file = $data['archivo'];
        $path = $file->storeAs(
            'bioestadistica/imports',
            now()->format('YmdHis').'-'.bin2hex(random_bytes(6)).'.'.$file->getClientOriginalExtension(),
            'local'
        );
        $absolutePath = Storage::disk('local')->path($path);

        try {
            $analysis = $analyzer->analyze($absolutePath);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw ValidationException::withMessages([
                'archivo' => 'No se pudo analizar el Excel: '.$exception->getMessage(),
            ]);
        }

        $job = ImportJob::create([
            'tipo' => $analysis['tipo'],
            'estado' => ImportJob::ESTADO_ANALIZADO,
            'archivo_original' => $file->getClientOriginalName(),
            'archivo_path' => $path,
            'checksum' => hash_file('sha256', $absolutePath),
            'analisis' => $analysis,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('bioestadistica.importaciones.show', $job)
            ->with('success', 'Archivo analizado. Confirme el mapeo antes de importar.');
    }

    public function show(ImportJob $importacion, FormulariosSpImporter $spImporter): View
    {
        $this->authorize('view', $importacion);
        $preview = ['formularios' => [], 'advertencias' => []];
        if ($importacion->tipo === 'formularios_sp' && Storage::disk('local')->exists($importacion->archivo_path)) {
            try {
                $preview = $spImporter->preview(
                    Storage::disk('local')->path($importacion->archivo_path),
                    $importacion->mapeo['decisiones'] ?? []
                );
            } catch (\Throwable $exception) {
                $preview = ['advertencias' => [$exception->getMessage()], 'formularios' => []];
            }
        }

        return view('admin.bioestadistica.importaciones.show', [
            'importacion' => $importacion,
            'formularios' => Formulario::orderBy('codigo')->get(),
            'spPreview' => $preview,
        ]);
    }

    public function map(Request $request, ImportJob $importacion): RedirectResponse
    {
        $this->authorize('execute', $importacion);
        $data = $request->validate([
            'mapeo' => ['nullable', 'string'],
            'decisiones' => ['nullable', 'array'],
            'decisiones.*' => ['nullable', 'string'],
        ]);
        $mapping = $importacion->mapeo ?? [];
        if (! empty($data['mapeo'])) {
            try {
                $decoded = json_decode($data['mapeo'], true, flags: JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                throw ValidationException::withMessages(['mapeo' => 'El mapeo debe ser JSON válido.']);
            }
            if (! is_array($decoded)) {
                throw ValidationException::withMessages(['mapeo' => 'El mapeo debe ser un objeto JSON.']);
            }
            $mapping = $decoded;
        }
        if (isset($data['decisiones'])) {
            $mapping['decisiones'] = array_filter(
                $data['decisiones'],
                fn ($value) => $value !== null && $value !== ''
            );
        }
        if ($mapping === []) {
            throw ValidationException::withMessages(['mapeo' => 'Indique un mapeo o decisiones de matching.']);
        }

        $importacion->update([
            'mapeo' => $mapping,
            'estado' => ImportJob::ESTADO_MAPEADO,
            'error' => null,
        ]);

        return back()->with('success', 'Mapeo guardado. Ya puede confirmar la importación.');
    }

    public function confirm(
        Request $request,
        ImportJob $importacion,
        GenericFormImporter $genericImporter,
        BioestadisticaDedicatedImporter $dedicatedImporter,
        AuditService $audit
    ): RedirectResponse {
        $this->authorize('execute', $importacion);
        if (! Storage::disk('local')->exists($importacion->archivo_path)) {
            throw ValidationException::withMessages(['archivo' => 'El archivo de la importación ya no está disponible.']);
        }
        $absolutePath = Storage::disk('local')->path($importacion->archivo_path);
        if (! hash_equals($importacion->checksum, hash_file('sha256', $absolutePath))) {
            throw ValidationException::withMessages(['archivo' => 'El archivo cambió desde que fue analizado. Vuelva a subirlo.']);
        }

        try {
            $summary = $audit->withoutAuditing(fn () => match ($importacion->tipo) {
                'variables_salud', 'establecimientos_dim' => $dedicatedImporter->import(
                    $absolutePath,
                    $importacion->tipo
                ),
                'formularios_sp' => $dedicatedImporter->import(
                    $absolutePath,
                    $importacion->tipo,
                    $importacion->mapeo['decisiones'] ?? []
                ),
                'generico' => $this->confirmGeneric($request, $importacion, $genericImporter),
                default => throw new \RuntimeException('Tipo de importación no admitido.'),
            });
            $importacion->update([
                'estado' => $importacion->tipo === 'generico'
                    ? ImportJob::ESTADO_CONFIRMADO
                    : ImportJob::ESTADO_COMPLETADO,
                'resumen' => $summary,
                'confirmed_by' => $request->user()->id,
                'confirmed_at' => now(),
                'error' => null,
            ]);
            $audit->recordBatch('import', ImportJob::class, (int) $importacion->id, [
                'registros' => (int) ($summary['registros'] ?? $summary['registros_guardados'] ?? $summary['creados'] ?? 0),
                'actualizados' => (int) ($summary['actualizados'] ?? $summary['registros_actualizados'] ?? 0),
            ], [
                'tipo' => $importacion->tipo,
            ], ['phase' => $importacion->tipo === 'generico' ? 'execute' : 'commit']);
        } catch (\Throwable $exception) {
            report($exception);
            $importacion->update([
                'estado' => ImportJob::ESTADO_ERROR,
                'error' => $exception->getMessage(),
            ]);
            throw ValidationException::withMessages([
                'importacion' => 'La importación no pudo completarse: '.$exception->getMessage(),
            ]);
        }

        return redirect()->route('bioestadistica.importaciones.show', $importacion)
            ->with('success', $importacion->tipo === 'generico'
                ? 'Formulario creado. Puede cargar datos indicando el período estadístico.'
                : 'Importación completada de forma idempotente.');
    }

    public function importData(
        Request $request,
        ImportJob $importacion,
        GenericFormImporter $genericImporter,
        AuditService $audit
    ): RedirectResponse {
        $this->authorize('execute', $importacion);
        if ($importacion->tipo !== 'generico') {
            throw ValidationException::withMessages([
                'importacion' => 'La carga de datos solo aplica al wizard genérico. Los Excel SP, de variables y de establecimientos no se cargan como registros EAV en esta fase.',
            ]);
        }
        if (! in_array($importacion->estado, [ImportJob::ESTADO_CONFIRMADO, ImportJob::ESTADO_COMPLETADO], true)) {
            throw ValidationException::withMessages([
                'importacion' => 'Confirme el mapeo y cree el formulario antes de cargar datos.',
            ]);
        }
        $data = $request->validate([
            'formulario_id' => ['required', 'integer'],
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'sobrescribir' => ['nullable', 'boolean'],
        ]);
        $formulario = Formulario::findOrFail($data['formulario_id']);
        $mapping = $importacion->mapeo;
        if (! $mapping) {
            throw ValidationException::withMessages(['mapeo' => 'Primero guarde y confirme el mapeo.']);
        }

        try {
            $summary = $audit->withoutAuditing(fn () => $genericImporter->importData(
                Storage::disk('local')->path($importacion->archivo_path),
                $formulario,
                $mapping,
                ['anio' => $data['periodo_anio'], 'mes' => $data['periodo_mes']],
                $request->user(),
                $request->boolean('sobrescribir')
            ));
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            $importacion->update(['error' => $exception->getMessage()]);
            throw ValidationException::withMessages([
                'importacion' => 'La carga de datos no pudo completarse: '.$exception->getMessage(),
            ]);
        }

        $importacion->update([
            'estado' => ImportJob::ESTADO_COMPLETADO,
            'error' => null,
            'resumen' => array_merge($importacion->resumen ?? [], ['carga_datos' => $summary]),
        ]);
        $audit->recordBatch('import', ImportJob::class, (int) $importacion->id, [
            'registros_guardados' => (int) ($summary['registros_guardados'] ?? 0),
        ], [
            'formulario_id' => (int) $data['formulario_id'],
            'periodo_anio' => (int) $data['periodo_anio'],
            'periodo_mes' => (int) $data['periodo_mes'],
        ], ['phase' => 'commit']);

        return back()->with('success', 'Segunda pasada completada: '.$summary['registros_guardados'].' registros guardados.');
    }

    public function destroy(ImportJob $importacion): RedirectResponse
    {
        $this->authorize('delete', $importacion);

        if ($importacion->archivo_path) {
            Storage::disk('local')->delete($importacion->archivo_path);
        }
        $nombre = $importacion->archivo_original;
        $importacion->delete();

        return redirect()
            ->route('bioestadistica.importaciones.index')
            ->with('success', "Importación eliminada: {$nombre}. No se revirtieron formularios ni datos ya confirmados.");
    }

    private function confirmGeneric(
        Request $request,
        ImportJob $importacion,
        GenericFormImporter $importer
    ): array {
        if (! $importacion->mapeo) {
            throw ValidationException::withMessages(['mapeo' => 'Guarde un mapeo confirmable antes de crear el formulario.']);
        }
        $summary = $importer->createForm($importacion->mapeo, $request->user()->id);

        return [
            ...$summary,
            'hojas_procesadas' => 1,
            'advertencias' => $importacion->analisis['advertencias'] ?? [],
        ];
    }
}
