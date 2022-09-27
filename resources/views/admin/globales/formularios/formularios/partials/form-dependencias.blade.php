{{ Form::hidden('status', 'PENDIENTE') }}
{{ Form::hidden('id', $id) }}
{{ Form::hidden('formulario', $formulario->formulario) }}
{{ Form::hidden('dependencia_emisor_id', $formulario->dependencia_emisor_id) }}
{{ Form::hidden('dependencia_receptor_id', $formulario->dependencia_receptor_id) }}
<div id="myProgress">
    <div id="myBar">
        {{ $countVariables }}
    </div>
</div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr class="table-warning">
                    @foreach ($collection->groupBy('type')->keys() as $value)
                        @php
                            $th = ucfirst($value);
                        @endphp

                        @switch($th)
                            @case($th == 'Service')
                                <th>Servicio</th>
                            @break

                            @case($th == 'Require')
                                <th>Requerimiento</th>
                            @break

                            @case($th == 'Item')
                                <th>Item</th>
                            @break

                            @case($th == 'Response')
                                <th>Respuesta</th>
                            @break

                            @default
                                <span>Pendiente</span>
                        @endswitch
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($collection as $key => $value)
                    @if ($value->deph == $collection->min('deph') || $collection[$key - 1]->deph == $value->deph)
                        <tr>
                    @endif

                    @if (isset($value->rowspan))
                        <td rowspan="{{ $value->rowspan }}">
                            {{ $value->name }}
                        </td>
                    @elseif (isset($value->colspan))
                        <td colspan="{{ $value->colspan }}">
                            {{ Form::hidden('variable_id[]', $value->id) }}
                            
                            {{ Form::checkbox('selected[]', $value->id, in_array($value->id, $selectionChecked) ? true : false, [
                                'class' => 'name',
                                'id' => 'discount'
                            ]) }}
                            {{ $value->name }}
                            
                        </td>
                    @endif

                    @if ($loop->last || $value->last || (!$loop->last && $collection[$key + 1]->deph == $collection->min('deph')))
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-group">
        {{ Form::submit('Guardar Formulario', ['class' => 'bt btn-sm btn-primary', 'id' => 'discount']) }}
    </div>
</div>

@section('scripts')
    <script>
        var countVariables = {{ $countVariables }}
        var i = 0;
        const element = document.getElementById("discount");
        element.addEventListener("click", myFunction);

        function myFunction() {
            document.getElementById("myBar").innerHTML = countVariables -1;
        }

        function move() {
            if (i == 0) {
                i = 1;
                var elem = document.getElementById("myBar");
                var width = 10;
                var id = setInterval(frame, 10);

                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                    } else {
                        width++;
                        elem.style.width = width + "%";
                        elem.innerHTML = width + "%";
                    }
                }
            }
        }
    </script>
@endsection
