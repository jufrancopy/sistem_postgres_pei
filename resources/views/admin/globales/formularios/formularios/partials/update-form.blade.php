{{ Form::hidden('status', 'COMPLETADO') }}
{{ Form::hidden('id', $id) }}
{{ Form::hidden('formulario', $formulario->formulario) }}
{{ Form::hidden('dependencia_emisor_id', $formulario->dependencia_emisor_id) }}
{{ Form::hidden('dependencia_receptor_id', $formulario->dependencia_receptor_id) }}
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
            @php
                $selection = [];
                foreach ($formulario->variables as $key => $v) {
                    echo $arr1[] = $v->pivot->variable_id;
                    echo $selection[] = $v->pivot->value;
                    // $formularioVariableAttach[$v] = ['value' => $request->value[$key]];
                }
                $responses = [1 => 'Si', 0 => 'No'];
            @endphp

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
                            {{$selectionChecked}}
                            {{-- @foreach ($responses as $key => $response)
                                {{ Form::checkbox('value[]', $key, in_array($value->id, $selectionChecked) ? true : false, ['class' => 'name']) }}
                                {{$response}}
                                {{$value->id}}
                                {{$key}}
                            @endforeach --}}
                            @foreach($responses as $key => $v)
                            {{ Form::checkbox('value[]', $key, in_array($value->id, $selectionChecked) ? true : false, ['class' => 'name']) }}
                            {{$value->id}}
                            {{$v}}
                            @endforeach
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
        {{ Form::submit('Guardar Formulario', ['class' => 'bt btn-sm btn-primary']) }}
    </div>
</div>
