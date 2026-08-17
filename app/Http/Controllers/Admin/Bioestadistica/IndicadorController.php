<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Application\Bioestadistica\Indicators\IndicatorEngine;
use App\Application\Bioestadistica\Statistics\StatisticsEngine;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\IndicadorFormula;
use App\Models\Bioestadistica\Record;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class IndicadorController extends Controller
{
    public function index(): View
    {
        return view('admin.bioestadistica.indicadores.index', [
            'indicadores' => Indicador::with('formulas')->orderBy('codigo')->paginate(30),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $indicator = Indicador::create($this->validateIndicator($request));

        return redirect()->route('bioestadistica.indicadores.show', $indicator)
            ->with('success', 'Indicador creado. Configure ahora su fórmula.');
    }

    public function show(Indicador $indicador): View
    {
        return $this->renderShow($indicador);
    }

    public function update(
        Request $request,
        Indicador $indicador,
        IndicatorCacheService $cache
    ): RedirectResponse
    {
        $data = $this->validateIndicator($request, $indicador);
        if ($data['codigo'] !== $indicador->codigo && $this->isReferenced($indicador->codigo)) {
            throw ValidationException::withMessages([
                'codigo' => 'No se puede cambiar el código porque otras fórmulas referencian este indicador.',
            ]);
        }
        $indicador->update($data);
        $cache->invalidateForIndicator($indicador);

        return back()->with('success', 'Indicador actualizado.');
    }

    public function destroy(Indicador $indicador): RedirectResponse
    {
        if ($this->isReferenced($indicador->codigo)) {
            throw ValidationException::withMessages([
                'indicador' => 'No se puede archivar porque otras fórmulas referencian este indicador.',
            ]);
        }
        $indicador->delete();

        return redirect()->route('bioestadistica.indicadores.index')
            ->with('success', 'Indicador archivado.');
    }

    public function validateFormula(
        Request $request,
        Indicador $indicador,
        FormulaAstValidator $validator
    ): RedirectResponse {
        $expression = $this->expression($request);
        $validator->validate($expression, $indicador);

        return back()->with('success', 'La fórmula es válida.')
            ->withInput(['expresion' => json_encode($expression, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)]);
    }

    public function storeFormula(
        Request $request,
        Indicador $indicador,
        FormulaAstValidator $validator,
        IndicatorCacheService $cache
    ): RedirectResponse {
        $dates = $request->validate([
            'vigente_desde' => ['nullable', 'date'],
            'vigente_hasta' => ['nullable', 'date', 'after_or_equal:vigente_desde'],
        ]);
        $expression = $this->expression($request);
        $validator->validate($expression, $indicador);
        DB::transaction(function () use ($indicador, $expression, $dates, $cache) {
            $this->closePreviousFormula($indicador, $dates);
            $this->ensureNoOverlap($indicador, $dates);
            $indicador->formulas()->create($dates + ['expresion' => $expression]);
            $cache->invalidateForIndicator($indicador);
        });

        return back()->with('success', 'Fórmula versionada y guardada.');
    }

    public function evaluate(
        Request $request,
        Indicador $indicador,
        IndicatorEngine $engine,
        StatisticsEngine $statistics
    ): View {
        $data = $request->validate([
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'establecimiento_id' => [
                Record::userHasGlobalAccess($request->user()) ? 'nullable' : 'required',
                Rule::exists(Establecimiento::class, 'id')->withoutTrashed(),
            ],
        ]);
        if (! Record::userHasGlobalAccess($request->user())
            && ! in_array((int) $data['establecimiento_id'], Record::assignedEstablishmentIds($request->user()), true)) {
            abort(403, 'No tiene asignado este establecimiento.');
        }
        $context = [
            'periodo_desde' => ['anio' => $data['periodo_anio'], 'mes' => $data['periodo_mes']],
            'periodo_hasta' => ['anio' => $data['periodo_anio'], 'mes' => $data['periodo_mes']],
        ];
        if (! empty($data['establecimiento_id'])) {
            $context['establecimiento_id'] = (int) $data['establecimiento_id'];
        }
        $result = $engine->evaluate($indicador, $context);
        $formula = $indicador->formulas()->findOrFail($result['formula_id']);
        $source = $this->singleSource($formula->expresion);
        $description = $source
            ? $statistics->describe($source, $context)
            : null;

        return $this->renderShow($indicador, [
            'evaluation' => $result,
            'evaluationContext' => $data,
            'description' => $description,
        ]);
    }

    public function destroyFormula(
        Indicador $indicador,
        IndicadorFormula $formula,
        IndicatorCacheService $cache
    ): RedirectResponse {
        abort_unless($formula->indicador_id === $indicador->id, 404);
        $formula->delete();
        $cache->invalidateForIndicator($indicador);

        return back()->with('success', 'Versión de fórmula eliminada.');
    }

    private function renderShow(Indicador $indicator, array $extra = []): View
    {
        $indicator->load('formulas');

        return view('admin.bioestadistica.indicadores.show', array_merge([
            'indicador' => $indicator,
            'sources' => $this->numericSources(),
            'indicators' => Indicador::activos()->where('id', '<>', $indicator->id)->orderBy('codigo')->get(),
            'establecimientos' => Establecimiento::query()
                ->when(
                    ! Record::userHasGlobalAccess(request()->user()),
                    fn ($query) => $query->whereIn('id', Record::assignedEstablishmentIds(request()->user()))
                )
                ->orderBy('nombre')
                ->get(),
            'months' => [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
            ],
        ], $extra));
    }

    private function validateIndicator(Request $request, ?Indicador $indicator = null): array
    {
        $data = $request->validate([
            'codigo' => [
                'required', 'alpha_dash', 'max:80',
                Rule::unique(Indicador::class, 'codigo')->ignore($indicator?->id)->withoutTrashed(),
            ],
            'nombre' => ['required', 'string', 'max:250'],
            'descripcion' => ['nullable', 'string', 'max:3000'],
            'unidad' => ['nullable', 'string', 'max:50'],
            'ambito' => ['required', Rule::in(['establecimiento', 'distrito', 'departamento', 'microred', 'pais'])],
            'decimales' => ['required', 'integer', 'between:0,4'],
            'activo' => ['nullable', 'boolean'],
        ]);
        $data['activo'] = $request->boolean('activo', true);
        return $data;
    }

    private function expression(Request $request): array
    {
        if ($request->input('formula_mode') === 'simple') {
            $data = $request->validate([
                'operator' => ['required', Rule::in(['sum', 'avg', 'count', 'count_distinct', 'max', 'min'])],
                'source' => ['required', 'string'],
                'catalog_item_ids' => ['nullable', 'array'],
                'catalog_item_ids.*' => ['integer'],
            ]);
            $source = collect($this->numericSources())->firstWhere('key', $data['source']);
            if (! $source) {
                throw ValidationException::withMessages(['source' => 'La fuente numérica seleccionada no existe.']);
            }
            $expression = array_filter([
                'op' => $data['operator'],
                'form' => $source['form'],
                'field' => $source['field'],
                'metric' => $source['metric'],
            ], fn ($value) => $value !== null);
            if (! empty($data['catalog_item_ids'])) {
                $expression['filter'] = ['catalog_item_ids' => array_map('intval', $data['catalog_item_ids'])];
            }
            return $expression;
        }

        $request->validate(['expresion' => ['required', 'string']]);
        try {
            return json_decode($request->input('expresion'), true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw ValidationException::withMessages(['expresion' => 'La expresión debe ser JSON válido.']);
        }
    }

    private function numericSources(): array
    {
        $sources = [];
        $forms = Formulario::with('secciones.fields')->orderBy('codigo')->get();
        foreach ($forms as $form) {
            foreach ($form->secciones->flatMap->fields as $field) {
                if (in_array($field->type, ['integer', 'decimal'], true)) {
                    $sources[] = [
                        'key' => "{$form->codigo}:{$field->code}",
                        'label' => "{$form->codigo} — {$field->label}",
                        'form' => $form->codigo,
                        'field' => $field->code,
                        'metric' => null,
                    ];
                }
                if ($field->type === 'tabla') {
                    foreach ($field->config['columns'] ?? [] as $column) {
                        if (in_array($column['type'] ?? null, ['integer', 'decimal'], true)) {
                            $sources[] = [
                                'key' => "{$form->codigo}:{$field->code}:{$column['code']}",
                                'label' => "{$form->codigo} — {$field->label} / {$column['label']}",
                                'form' => $form->codigo,
                                'field' => $field->code,
                                'metric' => $column['code'],
                            ];
                        }
                    }
                }
            }
        }
        return $sources;
    }

    private function ensureNoOverlap(Indicador $indicator, array $dates): void
    {
        $from = $dates['vigente_desde'] ?? '0001-01-01';
        $to = $dates['vigente_hasta'] ?? '9999-12-31';
        $overlap = $indicator->formulas()
            ->whereRaw("COALESCE(vigente_desde, DATE '0001-01-01') <= ?", [$to])
            ->whereRaw("COALESCE(vigente_hasta, DATE '9999-12-31') >= ?", [$from])
            ->exists();
        if ($overlap) {
            throw ValidationException::withMessages([
                'vigente_desde' => 'El rango de vigencia se superpone con otra fórmula.',
            ]);
        }
    }

    private function closePreviousFormula(Indicador $indicator, array $dates): void
    {
        if (empty($dates['vigente_desde'])) {
            return;
        }
        $newStart = \Carbon\CarbonImmutable::parse($dates['vigente_desde']);
        $previous = $indicator->formulas()
            ->where(fn ($query) => $query
                ->whereNull('vigente_desde')
                ->orWhere('vigente_desde', '<', $newStart))
            ->where(fn ($query) => $query
                ->whereNull('vigente_hasta')
                ->orWhere('vigente_hasta', '>=', $newStart))
            ->first();
        if ($previous) {
            $previous->update(['vigente_hasta' => $newStart->subDay()->toDateString()]);
        }
    }

    private function singleSource(array $expression): ?array
    {
        return in_array($expression['op'] ?? null, ['sum', 'avg', 'count', 'count_distinct', 'max', 'min'], true)
            ? array_filter([
                'form' => $expression['form'],
                'field' => $expression['field'],
                'metric' => $expression['metric'] ?? null,
            ], fn ($value) => $value !== null)
            : null;
    }

    private function isReferenced(string $code): bool
    {
        return IndicadorFormula::query()->get()->contains(
            fn (IndicadorFormula $formula) => $this->containsIndicatorReference($formula->expresion, $code)
        );
    }

    private function containsIndicatorReference(array $node, string $code): bool
    {
        if (($node['indicator'] ?? null) === $code) {
            return true;
        }
        foreach ($node['args'] ?? [] as $arg) {
            if (is_array($arg) && $this->containsIndicatorReference($arg, $code)) {
                return true;
            }
        }
        return false;
    }
}
