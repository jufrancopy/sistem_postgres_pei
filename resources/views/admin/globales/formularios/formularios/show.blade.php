@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Modulo de Planificación Estratégica</h4>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('globales.formularios.index') }}">
                            Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <!-- AquiBuscador -->
                            <!-- <div class="float-right">
                                {!! Form::open(['route' => 'globales.organigramas.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                <div class="form-group">
                                    {!! Form::text('nombre',null, ['class'=>'form-control','placeholder'=>'Buscar Organigrama']) !!}
                                </div>
                                <button type="submit" class="btn btn-default pull-right">Buscar</button>
                            </div> -->
                            {!!Form::close()!!}
                            <!-- Fin Buscador -->
                            <thead>
                                <tr class="table-warning">
                                    @foreach ($collection->groupBy('type')->keys() as $value)
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
                                    <td rowspan="{{ $value->rowspan }}">{{ $value->name }}</td>
                                    @elseif (isset($value->colspan))
                                        <td colspan="{{ $value->colspan }}">
                                            <input type="checkbox" value="{{$key}}"> {{ $value->name }}
                                        </td>
                                    @endif

                                    @if ($loop->last || $value->last
                                    || (!$loop->last && $collection[$key+1]->deph == $collection->min('deph')))
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection