<table border="1">
    <thead>
        <tr>
            @foreach ($collection->groupBy('tipo')->keys() as $value)
                <th>{{ ucfirst($value) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach ($collection as $key => $value)
            @if ($value->deph == $collection->min('deph')
                    || $collection[$key-1]->deph == $value->deph)
                <tr>
            @endif
            
            @if (isset($value->rowspan))
                <td rowspan="{{ $value->rowspan }}">{{ $value->nivel }}</td>
            @elseif (isset($value->colspan))
                <td colspan="{{ $value->colspan }}">{{ $value->nivel }}</td>
            @endif
            
            @if ($loop->last || $value->last
                    || (!$loop->last && $collection[$key+1]->deph == $collection->min('deph')))
                </tr>
            @endif       
        @endforeach

    </tbody>
</table>