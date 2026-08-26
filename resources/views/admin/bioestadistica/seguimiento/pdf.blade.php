<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $dataset['title'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        h1 { font-size: 15px; margin-bottom: 4px; }
        .meta { margin-bottom: 10px; }
        .meta p { margin: 1px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 3px 5px; }
        th { background: #eee; }
        .footer { margin-top: 14px; font-size: 9px; color: #555; }
    </style>
</head>
<body>
    <h1>Bioestadística — Seguimiento de datos</h1>
    <div class="meta">
        @foreach($dataset['meta'] as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>
    <table>
        <thead>
            <tr>
                @foreach($dataset['headers'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($dataset['rows'] as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($dataset['headers']) }}">Sin datos para los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <p class="footer">Instituto de Previsión Social — Dirección de Planificación. Período estadístico, no fecha de emisión.</p>
</body>
</html>
