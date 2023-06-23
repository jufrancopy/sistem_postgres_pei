@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title ">Cruce de Ambientes</h4>

                            <div class="pull-right">
                                <a class="btn btn-warning" href="{{ route('planificacion-dashboard') }}"> Atras</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-bordered">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td>
                                                Perfil: <label>{{ $perfil->nombre }}</label><br />
                                                Contexto: <label>{{ $perfil->contexto }}</label>
                                            </td>
                                            <td class="table-success"><label>Fortalezas</label> <br />
                                                @foreach ($fortalezas as $v)
                                                    <b>F{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->nombre }}<br />
                                                @endforeach
                                                </th>
                                            <td class="table-danger"><label>Debilidades </label><br />

                                                @foreach ($debilidades as $v)
                                                    <b>D{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->nombre }}<br />
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-success"><label>Oportunidades</label> <br />
                                                @foreach ($oportunidades as $v)
                                                    <b>O{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->nombre }}<br />
                                                @endforeach
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td><a href="{{ route('foda-cruce-ambientes-fo', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar
                                                                F-O</a></td>
                                                    <tr>
                                                </table>
                                                <hr>
                                                @foreach ($FOs as $vi)
                                                    @foreach ($vi->fortalezas as $fortaleza)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $fortaleza->aspecto->nombre }}">F{{ $fortaleza->aspecto->id }}</label>
                                                    @endforeach

                                                    @foreach ($vi->oportunidades as $oportunidad)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $oportunidad->aspecto->nombre }}">O{{ $oportunidad->aspecto->id }}</label>
                                                    @endforeach

                                                    {{ $vi->estrategia }}
                                                    <a href="{{ route('foda-cruce-ambientes.edit', $vi->id) }}"
                                                        class="badge badge-secondary">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    {!! Form::open([
                                                        'route' => ['foda-cruce-ambientes.destroy', $vi->id],
                                                        'method' => 'DELETE',
                                                        'style' => 'display:inline',
                                                    ]) !!}
                                                    <button class="badge badge-secundary"
                                                        onclick="return confirm('Estas seguro de eliminar la estrategia {{ $vi->estrategia }}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                    <br />
                                                @endforeach
                                            </td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td><a href="{{ route('foda-cruce-ambientes-do', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar D-O
                                                        </td>
                                                    <tr>
                                                </table>
                                                <hr>

                                                @foreach ($DOs as $vi)
                                                    @foreach ($vi->debilidades as $debilidad)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $debilidad->aspecto->nombre }}">D{{ $debilidad->aspecto->id }}</label>
                                                    @endforeach

                                                    @foreach ($vi->oportunidades as $oportunidad)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $oportunidad->aspecto->nombre }}">O{{ $oportunidad->aspecto->id }}</label>
                                                    @endforeach

                                                    {{ $vi->estrategia }}
                                                    <a href="{{ route('foda-cruce-ambientes.edit', $vi->id) }}"
                                                        class="badge badge-secondary"><i class="fa fa-edit"
                                                            aria-hidden="true"></i></a>
                                                    {!! Form::open([
                                                        'route' => ['foda-cruce-ambientes.destroy', $vi->id],
                                                        'method' => 'DELETE',
                                                        'style' => 'display:inline',
                                                    ]) !!}
                                                    <button class="badge badge-secundary"
                                                        onclick="return confirm('Estas seguro de eliminar la estrategia {{ $vi->estrategia }}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                    <br />
                                                @endforeach
                                            </td>


                                        </tr>
                                        <tr>
                                            <td class="table-danger"><label>Amenazas </label><br />
                                                @foreach ($amenazas as $v)
                                                    <b>A{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->nombre }}<br />
                                                @endforeach
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td><a href="{{ route('foda-cruce-ambientes-fa', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar F-A
                                                            </a></td>
                                                    <tr>
                                                </table>
                                                <hr>
                                                @foreach ($FAs as $vi)
                                                    @foreach ($vi->fortalezas as $fortaleza)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $fortaleza->aspecto->nombre }}">F{{ $fortaleza->aspecto->id }}</label>
                                                    @endforeach
                                                    @foreach ($vi->amenazas as $amenaza)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $amenaza->aspecto->nombre }}">A{{ $amenaza->aspecto->id }}</label>
                                                    @endforeach
                                                    {{ $vi->estrategia }}
                                                    <a href="{{ route('foda-cruce-ambientes.edit', $vi->id) }}"
                                                        class="badge badge-secondary"><i class="fa fa-edit"
                                                            aria-hidden="true"></i></a>
                                                    {!! Form::open([
                                                        'route' => ['foda-cruce-ambientes.destroy', $vi->id],
                                                        'method' => 'DELETE',
                                                        'style' => 'display:inline',
                                                    ]) !!}
                                                    <button class="badge badge-secundary"
                                                        onclick="return confirm('Estas seguro de eliminar la estrategia {{ $vi->estrategia }}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                    <br />
                                                @endforeach
                                            </td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td><a href="{{ route('foda-cruce-ambientes-da', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar D-A
                                                            </a></td>
                                                    <tr>
                                                </table>
                                                <hr>
                                                @foreach ($DAs as $vi)
                                                    @foreach ($vi->debilidades as $debilidad)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $debilidad->aspecto->nombre }}">D{{ $debilidad->aspecto->id }}</label>
                                                    @endforeach
                                                    @foreach ($vi->amenazas as $amenaza)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $amenaza->aspecto->nombre }}">A{{ $amenaza->aspecto->id }}</label>
                                                    @endforeach
                                                    {{ $vi->estrategia }}
                                                    <a href="{{ route('foda-cruce-ambientes.edit', $vi->id) }}"
                                                        class="badge badge-secondary"><i class="fa fa-edit"
                                                            aria-hidden="true"></i></a>
                                                    {!! Form::open([
                                                        'route' => ['foda-cruce-ambientes.destroy', $vi->id],
                                                        'method' => 'DELETE',
                                                        'style' => 'display:inline',
                                                    ]) !!}
                                                    <button class="badge badge-secundary"
                                                        onclick="return confirm('Estas seguro de eliminar la estrategia {{ $vi->estrategia }}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                    <br />
                                                @endforeach
                                            </td>
                                        </tr>


                                    </tbody>

                                </table>
                                <a href="{{ route('foda-cruce-pdf', $idPerfil) }}" class="btn btn-sm btn-info">
                                    Descargar Cruce de Ambientes en PDF
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
@endsection
