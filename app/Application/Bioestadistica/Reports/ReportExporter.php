<?php

namespace App\Application\Bioestadistica\Reports;

use App\Exports\BioestadisticaReportExport;
use App\Models\Bioestadistica\Reporte;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExporter
{
    public function csv(Reporte $reporte, array $result, User $user): StreamedResponse
    {
        $filename = $this->filename($reporte, 'csv');

        return response()->streamDownload(function () use ($reporte, $result, $user) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            foreach ($this->headerLines($reporte, $result, $user) as $line) {
                fputcsv($out, [$line], ';');
            }
            fputcsv($out, [], ';');
            fputcsv($out, array_column($result['columns'], 'label'), ';');
            foreach ($result['rows'] as $row) {
                fputcsv($out, $this->rowValues($result['columns'], $row), ';');
            }
            if ($result['totals']) {
                $total = array_fill(0, count($result['columns']) - 1, '');
                $total[] = $result['totals']['valor'];
                $total[0] = 'Total';
                fputcsv($out, $total, ';');
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function xlsx(Reporte $reporte, array $result, User $user)
    {
        $rows = [];
        foreach ($this->headerLines($reporte, $result, $user) as $line) {
            $rows[] = [$line];
        }
        $rows[] = [];
        $rows[] = array_column($result['columns'], 'label');
        foreach ($result['rows'] as $row) {
            $rows[] = $this->rowValues($result['columns'], $row);
        }
        if ($result['totals']) {
            $total = array_fill(0, count($result['columns']) - 1, '');
            $total[] = $result['totals']['valor'];
            $total[0] = 'Total';
            $rows[] = $total;
        }

        return Excel::download(
            new BioestadisticaReportExport($rows, $reporte->codigo),
            $this->filename($reporte, 'xlsx')
        );
    }

    public function pdf(Reporte $reporte, array $result, User $user)
    {
        $pdf = Pdf::loadView('admin.bioestadistica.reportes.pdf', [
            'reporte' => $reporte,
            'result' => $result,
            'header' => $this->headerLines($reporte, $result, $user),
            'user' => $user,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($this->filename($reporte, 'pdf'));
    }

    public function headerLines(Reporte $reporte, array $result, User $user): array
    {
        $filters = $result['meta']['filtros'] ?? [];
        $filterText = $filters
            ? collect($filters)->map(fn ($value, $key) => "{$key}={$value}")->implode('; ')
            : 'sin filtros adicionales';
        $cobertura = $result['meta']['cobertura'] ?? [];
        $coverage = isset($cobertura['informados'])
            ? "{$cobertura['informados']} de {$cobertura['esperados']} registros esperados"
            : 'n/d';

        return [
            'Reporte: '.$reporte->nombre.' ('.$reporte->codigo.')',
            'Período de los datos: '.($result['meta']['periodo_label'] ?? 'n/d'),
            'Filtros: '.$filterText,
            'Cobertura: '.$coverage,
            'Fecha de emisión: '.now()->format('d/m/Y H:i'),
            'Usuario: '.$user->name,
        ];
    }

    private function rowValues(array $columns, array $row): array
    {
        return array_map(fn (array $column) => $row[$column['key']] ?? '', $columns);
    }

    private function filename(Reporte $reporte, string $extension): string
    {
        return 'bioestadistica-'.$reporte->codigo.'-'.now()->format('Ymd-His').'.'.$extension;
    }
}
