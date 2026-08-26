<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Seguimiento\SeguimientoDatosService;
use App\Application\Bioestadistica\Seguimiento\SeguimientoExporter;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Departamento;
use App\Models\Bioestadistica\Distrito;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SeguimientoController extends Controller
{
    public function index(Request $request, SeguimientoDatosService $service): View
    {
        abort_unless($request->user()->can('bio.record.view'), 403);

        $filters = $this->filters($request);
        $tab = $filters['tab'];
        $actividad = collect();
        $pendientes = ['rows' => collect(), 'summary' => [
            'pendientes' => 0,
            'sin_abrir' => 0,
            'sin_enviar' => 0,
            'al_dia' => 0,
        ]];

        if ($tab === SeguimientoDatosService::TAB_ACTIVIDAD) {
            $actividad = $service->actividad($request->user(), $filters);
        } else {
            $pendientes = $service->pendientes($request->user(), $filters);
        }

        return view('admin.bioestadistica.seguimiento.index', [
            'tab' => $tab,
            'filters' => $filters,
            'actividad' => $actividad,
            'pendientes' => $pendientes['rows'],
            'summary' => $pendientes['summary'],
            'formularios' => Formulario::where('estado', 'activo')->ordenSp()->get(['id', 'codigo', 'nombre']),
            'departamentos' => Departamento::orderBy('nombre')->get(['id', 'nombre']),
            'distritos' => Distrito::query()
                ->when(
                    $filters['departamento_id'],
                    fn ($q) => $q->where('departamento_id', $filters['departamento_id'])
                )
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'departamento_id']),
            'establecimientos' => $this->allowedEstablishments($request),
            'months' => $this->months(),
            'estados' => [
                Record::ESTADO_BORRADOR => Record::estadoLabel(Record::ESTADO_BORRADOR),
                Record::ESTADO_ENVIADO => Record::estadoLabel(Record::ESTADO_ENVIADO),
                Record::ESTADO_APROBADO => Record::estadoLabel(Record::ESTADO_APROBADO),
                Record::ESTADO_OBJETADO => Record::estadoLabel(Record::ESTADO_OBJETADO),
            ],
        ]);
    }

    public function exportCsv(Request $request, SeguimientoExporter $exporter): StreamedResponse
    {
        $this->authorizeExport($request);

        return $exporter->csv($request->user(), $this->filters($request));
    }

    public function exportXlsx(Request $request, SeguimientoExporter $exporter): BinaryFileResponse
    {
        $this->authorizeExport($request);

        return $exporter->xlsx($request->user(), $this->filters($request));
    }

    public function exportPdf(Request $request, SeguimientoExporter $exporter)
    {
        $this->authorizeExport($request);

        return $exporter->pdf($request->user(), $this->filters($request));
    }

    /**
     * Compatibilidad con la ruta anterior /seguimiento/exportar.
     */
    public function export(Request $request, SeguimientoExporter $exporter): StreamedResponse
    {
        return $this->exportCsv($request, $exporter);
    }

    private function authorizeExport(Request $request): void
    {
        abort_unless($request->user()->can('bio.record.view'), 403);
        abort_unless($request->user()->can('bio.report.export'), 403);
    }

    /**
     * @return array{
     *   tab:string,
     *   periodo_anio:int,
     *   periodo_mes:int,
     *   formulario_ids:array<int,int>,
     *   departamento_id:int|null,
     *   distrito_id:int|null,
     *   establecimiento_id:int|null,
     *   estado:string|null
     * }
     */
    private function filters(Request $request): array
    {
        $data = $request->validate([
            'tab' => ['nullable', 'in:actividad,pendientes'],
            'periodo_anio' => ['nullable', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['nullable', 'integer', 'between:1,12'],
            'formulario_ids' => ['nullable', 'array'],
            'formulario_ids.*' => ['integer'],
            'departamento_id' => ['nullable', 'integer'],
            'distrito_id' => ['nullable', 'integer'],
            'establecimiento_id' => ['nullable', 'integer'],
            'estado' => ['nullable', 'in:borrador,enviado,aprobado,objetado'],
        ]);

        $default = now()->subMonth();

        return [
            'tab' => $data['tab'] ?? SeguimientoDatosService::TAB_ACTIVIDAD,
            'periodo_anio' => (int) ($data['periodo_anio'] ?? $default->year),
            'periodo_mes' => (int) ($data['periodo_mes'] ?? $default->month),
            'formulario_ids' => array_values(array_map('intval', $data['formulario_ids'] ?? [])),
            'departamento_id' => isset($data['departamento_id']) ? (int) $data['departamento_id'] : null,
            'distrito_id' => isset($data['distrito_id']) ? (int) $data['distrito_id'] : null,
            'establecimiento_id' => isset($data['establecimiento_id']) ? (int) $data['establecimiento_id'] : null,
            'estado' => $data['estado'] ?? null,
        ];
    }

    private function allowedEstablishments(Request $request)
    {
        return Establecimiento::query()
            ->with('distrito.departamento')
            ->whereNotNull('distrito_id')
            ->when(
                ! Record::userHasGlobalAccess($request->user()),
                fn ($query) => $query->whereIn('id', Record::assignedEstablishmentIds($request->user()))
            )
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'distrito_id']);
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
