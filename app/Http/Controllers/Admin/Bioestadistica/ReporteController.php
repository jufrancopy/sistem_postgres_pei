<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\NumericSourceCatalog;
use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Application\Bioestadistica\Reports\ReportBuilder;
use App\Application\Bioestadistica\Reports\ReportDefinitionValidator;
use App\Application\Bioestadistica\Reports\ReportExporter;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Reporte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(): View
    {
        return view('admin.bioestadistica.reportes.index', [
            'reportes' => Reporte::with('formulario')->orderBy('codigo')->paginate(30),
        ]);
    }

    public function create(NumericSourceCatalog $sources): View
    {
        return $this->form(new Reporte([
            'definicion' => [
                'agg' => 'sum',
                'dimensions' => ['establecimiento', 'periodo'],
                'limit' => 500,
                'totales' => true,
            ],
            'publico' => true,
        ]), $sources);
    }

    public function store(Request $request, ReportDefinitionValidator $validator): RedirectResponse
    {
        $data = $this->validatedMeta($request);
        $definition = $validator->validate($this->definitionFromRequest($request));
        $reporte = Reporte::create([
            ...$data,
            'definicion' => $definition,
            'formulario_id' => $definition['formulario_id'] ?? $data['formulario_id'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('bioestadistica.reportes.run', $reporte)
            ->with('success', 'Reporte creado. Revise el resultado tabular.');
    }

    public function edit(Reporte $reporte, NumericSourceCatalog $sources): View
    {
        return $this->form($reporte, $sources);
    }

    public function update(
        Request $request,
        Reporte $reporte,
        ReportDefinitionValidator $validator
    ): RedirectResponse {
        $data = $this->validatedMeta($request, $reporte);
        $definition = $validator->validate($this->definitionFromRequest($request));
        $reporte->update([
            ...$data,
            'definicion' => $definition,
            'formulario_id' => $definition['formulario_id'] ?? $data['formulario_id'] ?? null,
        ]);

        return redirect()->route('bioestadistica.reportes.run', $reporte)
            ->with('success', 'Reporte actualizado.');
    }

    public function destroy(Reporte $reporte): RedirectResponse
    {
        $reporte->delete();

        return redirect()->route('bioestadistica.reportes.index')
            ->with('success', 'Reporte archivado.');
    }

    public function run(Request $request, Reporte $reporte, ReportBuilder $builder): View
    {
        $result = $builder->execute($reporte->definicion, $request->user(), $this->filterOverrides($request));

        return view('admin.bioestadistica.reportes.run', [
            'reporte' => $reporte,
            'result' => $result,
            'months' => PeriodContext::MONTHS,
            'filters' => $this->filterOverrides($request) ?: ($reporte->definicion['filtros'] ?? []),
        ]);
    }

    public function exportCsv(Request $request, Reporte $reporte, ReportBuilder $builder, ReportExporter $exporter, AuditService $audit)
    {
        $result = $this->exportable($request, $reporte, $builder);
        $this->auditExport($audit, $reporte, $result, 'csv');

        return $exporter->csv($reporte, $result, $request->user());
    }

    public function exportXlsx(Request $request, Reporte $reporte, ReportBuilder $builder, ReportExporter $exporter, AuditService $audit)
    {
        $result = $this->exportable($request, $reporte, $builder);
        $this->auditExport($audit, $reporte, $result, 'xlsx');

        return $exporter->xlsx($reporte, $result, $request->user());
    }

    public function exportPdf(Request $request, Reporte $reporte, ReportBuilder $builder, ReportExporter $exporter, AuditService $audit)
    {
        $result = $this->exportable($request, $reporte, $builder);
        $this->auditExport($audit, $reporte, $result, 'pdf');

        return $exporter->pdf($reporte, $result, $request->user());
    }

    private function exportable(Request $request, Reporte $reporte, ReportBuilder $builder): array
    {
        $result = $builder->execute($reporte->definicion, $request->user(), $this->filterOverrides($request));
        if ($result['meta']['truncated']) {
            throw ValidationException::withMessages([
                'reporte' => 'El resultado supera '.ReportBuilder::MAX_ROWS
                    .' filas. Ajuste filtros o el límite; la cola asíncrona se habilitará en una fase posterior.',
            ]);
        }

        return $result;
    }

    private function form(Reporte $reporte, NumericSourceCatalog $sources): View
    {
        return view('admin.bioestadistica.reportes.form', [
            'reporte' => $reporte,
            'sources' => $sources->all(),
            'indicators' => Indicador::activos()->orderBy('codigo')->get(),
            'formularios' => Formulario::orderBy('codigo')->get(),
            'dimensions' => ReportDefinitionValidator::DIMENSIONS,
            'aggregations' => ReportDefinitionValidator::AGGREGATIONS,
            'months' => PeriodContext::MONTHS,
        ]);
    }

    private function validatedMeta(Request $request, ?Reporte $reporte = null): array
    {
        $data = $request->validate([
            'codigo' => [
                'required', 'alpha_dash', 'max:80',
                Rule::unique(Reporte::class, 'codigo')->ignore($reporte?->id)->withoutTrashed(),
            ],
            'nombre' => ['required', 'string', 'max:250'],
            'descripcion' => ['nullable', 'string', 'max:3000'],
            'formulario_id' => ['nullable', 'integer'],
            'publico' => ['nullable', 'boolean'],
        ]);
        $data['publico'] = $request->boolean('publico', true);

        return $data;
    }

    private function definitionFromRequest(Request $request): array
    {
        if ($request->input('definition_mode') === 'advanced') {
            $request->validate(['definicion' => ['required', 'string']]);
            try {
                return json_decode($request->input('definicion'), true, flags: JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                throw ValidationException::withMessages(['definicion' => 'La definición debe ser JSON válido.']);
            }
        }

        $data = $request->validate([
            'source' => ['nullable', 'string'],
            'indicator' => ['nullable', 'string', 'max:80'],
            'agg' => ['required', Rule::in(ReportDefinitionValidator::AGGREGATIONS)],
            'dimensions' => ['nullable', 'array'],
            'dimensions.*' => [Rule::in(ReportDefinitionValidator::DIMENSIONS)],
            'limit' => ['required', 'integer', 'min:1', 'max:'.ReportBuilder::MAX_ROWS],
            'periodo_desde_anio' => ['nullable', 'integer'],
            'periodo_desde_mes' => ['nullable', 'integer', 'between:1,12'],
            'periodo_hasta_anio' => ['nullable', 'integer'],
            'periodo_hasta_mes' => ['nullable', 'integer', 'between:1,12'],
            'estado_record' => ['nullable', Rule::in(['borrador', 'enviado', 'aprobado', 'objetado'])],
            'order_ref' => ['nullable', 'string'],
            'order_dir' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $definition = [
            'agg' => $data['agg'],
            'indicator' => $data['indicator'] ?: null,
            'dimensions' => array_values($data['dimensions'] ?? []),
            'limit' => (int) $data['limit'],
            'totales' => $request->boolean('totales', true),
            'filtros' => array_filter([
                'estado_record' => $data['estado_record'] ?? 'aprobado',
                'periodo_desde' => isset($data['periodo_desde_anio'], $data['periodo_desde_mes'])
                    ? ['anio' => (int) $data['periodo_desde_anio'], 'mes' => (int) $data['periodo_desde_mes']]
                    : null,
                'periodo_hasta' => isset($data['periodo_hasta_anio'], $data['periodo_hasta_mes'])
                    ? ['anio' => (int) $data['periodo_hasta_anio'], 'mes' => (int) $data['periodo_hasta_mes']]
                    : null,
            ]),
        ];

        if (! empty($data['source'])) {
            $parts = explode(':', $data['source']);
            $definition['form'] = $parts[0] ?? null;
            $definition['field'] = $parts[1] ?? null;
            $definition['metric'] = $parts[2] ?? null;
        }
        if (! empty($data['order_ref'])) {
            $definition['order_by'] = [[
                'ref' => $data['order_ref'],
                'dir' => $data['order_dir'] ?? 'asc',
            ]];
        }

        return $definition;
    }

    private function filterOverrides(Request $request): array
    {
        $filters = [];
        if ($request->filled('periodo_desde_anio') && $request->filled('periodo_desde_mes')) {
            $filters['periodo_desde'] = [
                'anio' => $request->integer('periodo_desde_anio'),
                'mes' => $request->integer('periodo_desde_mes'),
            ];
        }
        if ($request->filled('periodo_hasta_anio') && $request->filled('periodo_hasta_mes')) {
            $filters['periodo_hasta'] = [
                'anio' => $request->integer('periodo_hasta_anio'),
                'mes' => $request->integer('periodo_hasta_mes'),
            ];
        }
        if ($request->filled('estado_record')) {
            $filters['estado_record'] = $request->string('estado_record')->toString();
        }

        return $filters;
    }

    private function auditExport(AuditService $audit, Reporte $reporte, array $result, string $format): void
    {
        $audit->recordBatch('export', Reporte::class, (int) $reporte->id, [
            'filas' => count($result['rows'] ?? []),
        ], [
            'formato' => $format,
            'codigo' => $reporte->codigo,
        ], ['phase' => $format]);
    }
}
