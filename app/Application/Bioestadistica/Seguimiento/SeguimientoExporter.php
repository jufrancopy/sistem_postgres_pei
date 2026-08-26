<?php

namespace App\Application\Bioestadistica\Seguimiento;

use App\Exports\BioestadisticaReportExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SeguimientoExporter
{
    public function __construct(private SeguimientoDatosService $service)
    {
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{title:string,headers:array<int,string>,rows:Collection,meta:array<int,string>}
     */
    public function dataset(User $user, array $filters): array
    {
        $tab = $filters['tab'] ?? SeguimientoDatosService::TAB_ACTIVIDAD;
        $periodo = sprintf('%04d-%02d', $filters['periodo_anio'], $filters['periodo_mes']);
        $tabLabel = $tab === SeguimientoDatosService::TAB_PENDIENTES
            ? 'Sin reportar'
            : 'Actividad de usuarios';

        if ($tab === SeguimientoDatosService::TAB_ACTIVIDAD) {
            $rows = $this->service->actividad($user, $filters);
            $headers = [
                'Establecimiento', 'Departamento', 'Distrito', 'SP', 'Formulario', 'Período', 'Estado',
                'Creó', 'Creó ID', 'Último editó', 'Último editó ID',
                'Envió', 'Envió ID', 'Aprobó', 'Aprobó ID', 'Enviado el', 'Aprobado el',
            ];
            $mapped = $rows->map(fn (array $row) => [
                $row['establecimiento'],
                $row['departamento'],
                $row['distrito'],
                $row['formulario'],
                $row['formulario_nombre'],
                $row['periodo'],
                $row['estado_label'],
                $row['created_by'],
                $row['created_by_id'] ?? '',
                $row['updated_by'],
                $row['updated_by_id'] ?? '',
                $row['submitted_by'],
                $row['submitted_by_id'] ?? '',
                $row['approved_by'],
                $row['approved_by_id'] ?? '',
                $row['submitted_at'] ?? '',
                $row['approved_at'] ?? '',
            ]);
        } else {
            $result = $this->service->pendientes($user, $filters);
            $rows = $result['rows'];
            $headers = [
                'Establecimiento', 'Departamento', 'Distrito', 'SP', 'Formulario',
                'Situación', 'Estado', 'Último actor', 'Último actor ID',
            ];
            $mapped = $rows->map(fn (array $row) => [
                $row['establecimiento'],
                $row['departamento'],
                $row['distrito'],
                $row['formulario'],
                $row['formulario_nombre'],
                $row['situacion_label'],
                $row['estado_label'],
                $row['ultimo_actor'],
                $row['ultimo_actor_id'] ?? '',
            ]);
            $summary = $result['summary'];
        }

        $meta = [
            'Seguimiento de datos — '.$tabLabel,
            'Período estadístico: '.$periodo,
            'Fecha de emisión: '.now()->format('d/m/Y H:i'),
            'Usuario: '.$user->name,
        ];
        if ($tab === SeguimientoDatosService::TAB_PENDIENTES) {
            $meta[] = sprintf(
                'Resumen: pendientes=%d; sin abrir=%d; sin enviar=%d; al día=%d',
                $summary['pendientes'],
                $summary['sin_abrir'],
                $summary['sin_enviar'],
                $summary['al_dia']
            );
        }

        return [
            'title' => $tabLabel,
            'headers' => $headers,
            'rows' => $mapped,
            'meta' => $meta,
            'tab' => $tab,
            'periodo_anio' => (int) $filters['periodo_anio'],
            'periodo_mes' => (int) $filters['periodo_mes'],
        ];
    }

    public function csv(User $user, array $filters): StreamedResponse
    {
        $dataset = $this->dataset($user, $filters);

        return response()->streamDownload(function () use ($dataset) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            foreach ($dataset['meta'] as $line) {
                fputcsv($out, [$line], ';');
            }
            fputcsv($out, [], ';');
            fputcsv($out, $dataset['headers'], ';');
            foreach ($dataset['rows'] as $row) {
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $this->filename($dataset, 'csv'), [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function xlsx(User $user, array $filters): BinaryFileResponse
    {
        $dataset = $this->dataset($user, $filters);
        $sheet = [];
        foreach ($dataset['meta'] as $line) {
            $sheet[] = [$line];
        }
        $sheet[] = [];
        $sheet[] = $dataset['headers'];
        foreach ($dataset['rows'] as $row) {
            $sheet[] = $row;
        }

        return Excel::download(
            new BioestadisticaReportExport($sheet, 'Seguimiento'),
            $this->filename($dataset, 'xlsx')
        );
    }

    public function pdf(User $user, array $filters)
    {
        $dataset = $this->dataset($user, $filters);
        $pdf = Pdf::loadView('admin.bioestadistica.seguimiento.pdf', [
            'dataset' => $dataset,
            'user' => $user,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($this->filename($dataset, 'pdf'));
    }

    /**
     * @param  array<string, mixed>  $dataset
     */
    private function filename(array $dataset, string $extension): string
    {
        return sprintf(
            'seguimiento-%s-%04d-%02d.%s',
            $dataset['tab'],
            $dataset['periodo_anio'],
            $dataset['periodo_mes'],
            $extension
        );
    }
}
