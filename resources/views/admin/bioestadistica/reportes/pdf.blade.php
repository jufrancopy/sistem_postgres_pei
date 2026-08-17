<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $reporte->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .meta { margin-bottom: 12px; }
        .meta p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; }
        th { background: #eee; }
        tfoot th { background: #f5f5f5; }
        .footer { margin-top: 16px; font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h1>Bioestadística — {{ $reporte->nombre }}</h1>
    <div class="meta">
        @foreach($header as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>
    <table>
        <thead>
            <tr>
                @foreach($result['columns'] as $column)
                    <th>{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($result['rows'] as $row)
                <tr>
                    @foreach($result['columns'] as $column)
                        <td>{{ $row[$column['key']] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        @if($result['totals'])
            <tfoot>
                <tr>
                    @foreach($result['columns'] as $index => $column)
                        <th>{{ $column['key'] === 'valor' ? $result['totals']['valor'] : ($index === 0 ? 'Total' : '') }}</th>
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>
    <p class="footer">Instituto de Previsión Social — Dirección de Planificación. Las cifras corresponden al período estadístico informado, no a la fecha de emisión.</p>
</body>
</html>
