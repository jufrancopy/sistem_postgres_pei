<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de notas</title>
</head>
<body>
    <table>
        <thead>
            <th>ID</th>
            <th>Título</th>
            <th>Acción</th>
        </thead>
        <tbody>
            @foreach ($notes as $note)
                <tr>
                    <td>{{ $note->id }}</td>
                    <td>{{ $note->title }}</td>
                    <td>
                        @can('destroy_notes')
                            <a href="{{ route('notes.destroy', $note->id) }}">Eliminar nota</a>
                        @else
                            Usted no puede eliminar esta nota
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>