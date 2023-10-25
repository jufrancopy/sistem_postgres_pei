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
                            @if (isset($profile))
                                <h3 class="card-title ">Matriz FODA - {{ $profile->name }}</h3>
                            @else
                                <h3 class="card-title ">Matriz FODA Consolidado</h3>
                            @endif
                        </div>
                        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                @if (isset($profile))
                                    <li class="breadcrumb-item"><a href="{{ route('foda-list-groups') }}">Matriz Consolidado
                                        </a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('foda-matriz-groups', $profile->group_id) }}">Consolidado -
                                            {{ $profile->name }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Matriz Foda -
                                        {{ $profile->name }}</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">Matriz Foda Consolidado</li>
                                @endif
                            </ol>
                        </nav>

                        <div class="card-body">
                            <div class="table-bordered">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td>
                                                profile: <label>{{ $profile->name }}</label><br />
                                                Contexto: <label>{{ $profile->context }}</label>
                                            </td>
                                            <td class="table-success"><label>Fortalezas</label> <br />
                                                @foreach ($fortalezas as $v)
                                                    <b>F{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->name }}<br />
                                                @endforeach
                                                </th>
                                            <td class="table-danger"><label>Debilidades </label><br />

                                                @foreach ($debilidades as $v)
                                                    <b>D{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->name }}<br />
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-success"><label>Oportunidades</label> <br />
                                                @foreach ($oportunidades as $v)
                                                    <b>O{{ $v->aspecto->id }} -</b>
                                                    {{ $v->aspecto->name }}<br />
                                                @endforeach
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td><a href="{{ route('foda-matriz-crossing-fo', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar
                                                                F-O</a></td>
                                                    <tr>
                                                </table>
                                                <hr>
                                                @foreach ($FOs as $vi)
                                                    @foreach ($vi->fortalezas as $fortaleza)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $fortaleza->name }}">F{{ $fortaleza->id }}</label>
                                                    @endforeach

                                                    @foreach ($vi->oportunidades as $oportunidad)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $oportunidad->name }}">O{{ $oportunidad->id }}</label>
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
                                                        <td><a href="{{ route('foda-matriz-crossing-do', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar D-O
                                                        </td>
                                                    <tr>
                                                </table>
                                                <hr>

                                                @foreach ($DOs as $vi)
                                                    @foreach ($vi->debilidades as $debilidad)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $debilidad->name }}">D{{ $debilidad->id }}</label>
                                                    @endforeach

                                                    @foreach ($vi->oportunidades as $oportunidad)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $oportunidad->name }}">O{{ $oportunidad->id }}</label>
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
                                                    {{ $v->aspecto->name }}<br />
                                                @endforeach
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td><a href="{{ route('foda-matriz-crossing-fa', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar F-A
                                                            </a></td>
                                                    <tr>
                                                </table>
                                                <hr>
                                                @foreach ($FAs as $vi)
                                                    @foreach ($vi->fortalezas as $fortaleza)
                                                        <label class="badge badge-success" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $fortaleza->name }}">F{{ $fortaleza->id }}</label>
                                                    @endforeach
                                                    @foreach ($vi->amenazas as $amenaza)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $amenaza->name }}">A{{ $amenaza->id }}</label>
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
                                                        <td><a href="{{ route('foda-matriz-crossing-da', $idPerfil) }}"><i
                                                                    class="fa fa-recycle" aria-hidden="true"></i> Cruzar D-A
                                                            </a></td>
                                                    <tr>
                                                </table>
                                                <hr>
                                                @foreach ($DAs as $vi)
                                                    @foreach ($vi->debilidades as $debilidad)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $debilidad->name }}">D{{ $debilidad->id }}</label>
                                                    @endforeach
                                                    @foreach ($vi->amenazas as $amenaza)
                                                        <label class="badge badge-danger" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{{ $amenaza->name }}">A{{ $amenaza->id }}</label>
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
