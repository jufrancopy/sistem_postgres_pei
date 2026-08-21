<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Dashboards\DashboardService;
use App\Application\Bioestadistica\NumericSourceCatalog;
use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Application\Bioestadistica\Reports\ReportDefinitionValidator;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\DashboardWidget;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Reporte;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('admin.bioestadistica.dashboards.index', [
            'institucionales' => Dashboard::query()->whereNull('user_id')->withCount('widgets')->orderBy('nombre')->get(),
            'personales' => Dashboard::query()->where('user_id', $user->id)->withCount('widgets')->orderBy('nombre')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.bioestadistica.dashboards.form', [
            'dashboard' => new Dashboard(['es_default' => false]),
        ]);
    }

    public function edit(Request $request, Dashboard $dashboard): View
    {
        $this->authorize('update', $dashboard);

        return view('admin.bioestadistica.dashboards.form', [
            'dashboard' => $dashboard,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Dashboard::class);
        $data = $this->validatedDashboard($request);
        $dashboard = Dashboard::create([
            ...$data,
            'user_id' => null,
        ]);
        if ($dashboard->es_default) {
            $this->clearOtherDefaults($dashboard);
        }

        return redirect()->route('bioestadistica.dashboards.show', $dashboard)
            ->with('success', 'Plantilla institucional creada. Agregue widgets.');
    }

    public function show(Request $request, Dashboard $dashboard, DashboardService $service): View
    {
        $this->authorize('view', $dashboard);
        $service->assertVisible($dashboard, $request->user());
        $closed = PeriodContext::lastClosed();
        $dashboard->load('widgets');
        $editingWidget = null;
        if ($request->filled('edit_widget')) {
            $editingWidget = $dashboard->widgets->firstWhere('id', $request->integer('edit_widget'));
        }

        return view('admin.bioestadistica.dashboards.show', [
            'dashboard' => $dashboard,
            'editingWidget' => $editingWidget,
            'editable' => $this->canEdit($dashboard, $request->user()),
            'months' => PeriodContext::MONTHS,
            'period' => [
                'desde' => [
                    'anio' => $request->integer('periodo_desde_anio', $closed['anio']),
                    'mes' => $request->integer('periodo_desde_mes', $closed['mes']),
                ],
                'hasta' => [
                    'anio' => $request->integer('periodo_hasta_anio', $closed['anio']),
                    'mes' => $request->integer('periodo_hasta_mes', $closed['mes']),
                ],
            ],
            'sources' => app(NumericSourceCatalog::class)->all(),
            'indicators' => Indicador::activos()->orderBy('codigo')->get(),
            'reportes' => Reporte::orderBy('codigo')->get(),
            'widgetTypes' => ReportDefinitionValidator::WIDGET_TYPES,
            'dimensions' => ReportDefinitionValidator::DIMENSIONS,
        ]);
    }

    public function update(Request $request, Dashboard $dashboard, DashboardService $service): RedirectResponse
    {
        $this->authorize('update', $dashboard);
        $service->assertEditable($dashboard, $request->user());
        $dashboard->update($this->validatedDashboard($request, $dashboard));
        if ($dashboard->es_default && $dashboard->isInstitutional()) {
            $this->clearOtherDefaults($dashboard);
        }

        return back()->with('success', 'Dashboard actualizado.');
    }

    public function destroy(Request $request, Dashboard $dashboard, DashboardService $service): RedirectResponse
    {
        $this->authorize('delete', $dashboard);
        $service->assertEditable($dashboard, $request->user());
        $dashboard->delete();

        return redirect()->route('bioestadistica.dashboards.index')
            ->with('success', 'Dashboard archivado.');
    }

    public function cloneTemplate(Request $request, Dashboard $dashboard, DashboardService $service): RedirectResponse
    {
        $copy = $service->cloneTemplate($dashboard, $request->user());

        return redirect()->route('bioestadistica.dashboards.show', $copy)
            ->with('success', 'Se creó su copia personal. Puede mover y editar widgets sin afectar la plantilla.');
    }

    public function layout(Request $request, Dashboard $dashboard, DashboardService $service): JsonResponse
    {
        $this->authorize('update', $dashboard);
        $service->assertEditable($dashboard, $request->user());
        $data = $request->validate([
            'widgets' => ['required', 'array'],
            'widgets.*.id' => ['required', 'integer'],
            'widgets.*.pos_x' => ['required', 'integer', 'between:0,11'],
            'widgets.*.pos_y' => ['required', 'integer', 'min:0'],
            'widgets.*.ancho' => ['required', 'integer', 'between:1,12'],
            'widgets.*.alto' => ['required', 'integer', 'between:1,12'],
        ]);
        foreach ($data['widgets'] as $item) {
            $widget = DashboardWidget::query()
                ->where('dashboard_id', $dashboard->id)
                ->whereKey($item['id'])
                ->first();
            if (! $widget) {
                continue;
            }
            $widget->update([
                'pos_x' => $item['pos_x'],
                'pos_y' => $item['pos_y'],
                'ancho' => $item['ancho'],
                'alto' => $item['alto'],
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function storeWidget(
        Request $request,
        Dashboard $dashboard,
        DashboardService $service,
        ReportDefinitionValidator $validator
    ): RedirectResponse {
        $this->authorize('update', $dashboard);
        $service->assertEditable($dashboard, $request->user());
        $data = $this->validatedWidget($request);
        $config = $validator->validateWidgetConfig($data['tipo'], $this->widgetConfigFromRequest($request));
        $maxY = (int) $dashboard->widgets()->max('pos_y');
        $dashboard->widgets()->create([
            'tipo' => $data['tipo'],
            'titulo' => $data['titulo'],
            'query_config' => $config,
            'pos_x' => 0,
            'pos_y' => $maxY + 1,
            'ancho' => (int) $data['ancho'],
            'alto' => (int) $data['alto'],
        ]);

        return back()->with('success', 'Widget agregado.');
    }

    public function updateWidget(
        Request $request,
        Dashboard $dashboard,
        DashboardWidget $widget,
        DashboardService $service,
        ReportDefinitionValidator $validator
    ): RedirectResponse {
        $this->authorize('update', $dashboard);
        $service->assertEditable($dashboard, $request->user());
        abort_unless($widget->dashboard_id === $dashboard->id, 404);
        $data = $this->validatedWidget($request);
        $widget->update([
            'tipo' => $data['tipo'],
            'titulo' => $data['titulo'],
            'query_config' => $validator->validateWidgetConfig($data['tipo'], $this->widgetConfigFromRequest($request)),
            'ancho' => (int) $data['ancho'],
            'alto' => (int) $data['alto'],
        ]);

        return redirect()->route('bioestadistica.dashboards.show', $dashboard)
            ->with('success', 'Widget actualizado.');
    }

    public function destroyWidget(
        Request $request,
        Dashboard $dashboard,
        DashboardWidget $widget,
        DashboardService $service
    ): RedirectResponse {
        $this->authorize('update', $dashboard);
        $service->assertEditable($dashboard, $request->user());
        abort_unless($widget->dashboard_id === $dashboard->id, 404);
        $widget->delete();

        return back()->with('success', 'Widget eliminado.');
    }

    public function widgetData(
        Request $request,
        Dashboard $dashboard,
        DashboardWidget $widget,
        DashboardService $service
    ): JsonResponse {
        $this->authorize('view', $dashboard);
        $service->assertVisible($dashboard, $request->user());
        abort_unless($widget->dashboard_id === $dashboard->id, 404);
        $closed = PeriodContext::lastClosed();
        $filters = array_filter([
            'periodo_desde' => $request->filled('periodo_desde_anio')
                ? [
                    'anio' => $request->integer('periodo_desde_anio'),
                    'mes' => $request->integer('periodo_desde_mes', $closed['mes']),
                ]
                : null,
            'periodo_hasta' => $request->filled('periodo_hasta_anio')
                ? [
                    'anio' => $request->integer('periodo_hasta_anio'),
                    'mes' => $request->integer('periodo_hasta_mes', $closed['mes']),
                ]
                : null,
        ]);

        return response()->json($service->widgetPayload($widget, $request->user(), $filters));
    }

    private function validatedDashboard(Request $request, ?Dashboard $dashboard = null): array
    {
        $data = $request->validate([
            'codigo' => ['required', 'alpha_dash', 'max:80'],
            'nombre' => ['required', 'string', 'max:250'],
            'descripcion' => ['nullable', 'string', 'max:3000'],
            'es_default' => ['nullable', 'boolean'],
        ]);

        $isInstitutional = $dashboard ? $dashboard->isInstitutional() : true;
        if ($isInstitutional) {
            $data['es_default'] = $request->user()->can('bio.dashboard.manage')
                ? $request->boolean('es_default')
                : (bool) ($dashboard?->es_default ?? false);
        } else {
            $data['es_default'] = false;
        }

        $exists = Dashboard::query()
            ->when($dashboard, fn ($query) => $query->where('id', '<>', $dashboard->id))
            ->where('codigo', $data['codigo'])
            ->where(fn ($query) => $dashboard?->user_id
                ? $query->where('user_id', $dashboard->user_id)
                : $query->whereNull('user_id'))
            ->exists();
        if ($exists) {
            throw ValidationException::withMessages(['codigo' => 'Ya existe un dashboard con ese código.']);
        }

        return $data;
    }

    private function validatedWidget(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', Rule::in(ReportDefinitionValidator::WIDGET_TYPES)],
            'titulo' => ['required', 'string', 'max:250'],
            'ancho' => ['required', 'integer', 'between:1,12'],
            'alto' => ['required', 'integer', 'between:1,12'],
        ]);
    }

    private function widgetConfigFromRequest(Request $request): array
    {
        $config = array_filter([
            'indicator' => $request->input('indicator') ?: null,
            'dimension' => $request->input('dimension') ?: null,
            'dimension_x' => $request->input('dimension_x') ?: null,
            'dimension_y' => $request->input('dimension_y') ?: null,
            'reporte_id' => $request->filled('reporte_id') ? $request->integer('reporte_id') : null,
            'agg' => $request->input('agg') ?: 'sum',
            'label' => $request->input('label') ?: null,
        ], fn ($value) => $value !== null && $value !== '');

        if ($request->filled('source')) {
            $parts = explode(':', (string) $request->input('source'));
            $config['form'] = $parts[0] ?? null;
            $config['field'] = $parts[1] ?? null;
            $config['metric'] = $parts[2] ?? null;
        }
        if ($request->filled('umbral_verde_min')) {
            $config['umbrales'] = [
                'verde' => [(float) $request->input('umbral_verde_min'), (float) $request->input('umbral_verde_max', 999999)],
                'amarillo' => [(float) $request->input('umbral_amarillo_min', 0), (float) $request->input('umbral_amarillo_max', 0)],
                'rojo' => [(float) $request->input('umbral_rojo_min', 0), (float) $request->input('umbral_rojo_max', 0)],
            ];
        }

        return $config;
    }

    private function clearOtherDefaults(Dashboard $dashboard): void
    {
        Dashboard::query()
            ->whereNull('user_id')
            ->where('id', '<>', $dashboard->id)
            ->update(['es_default' => false]);
    }

    private function canEdit(Dashboard $dashboard, $user): bool
    {
        return $dashboard->isInstitutional()
            ? $user->can('bio.dashboard.manage')
            : ((int) $dashboard->user_id === (int) $user->id && $user->can('bio.dashboard.personalize'));
    }
}
