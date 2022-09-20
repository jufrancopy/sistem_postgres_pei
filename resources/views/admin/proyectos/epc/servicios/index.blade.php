@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Listado de Serivicios</h4>
                    <a class="btn btn-success" href="{{ route('proyectos-epc-servicios.create') }}">Agregar</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('proyectos-epc-home') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <!-- AquiBuscador -->
                            <div class="float-right">
                                {!! Form::open(['route' => 'globales.organigramas.index','method' => 'GET',
                                'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                <div class="form-group">
                                    {!! Form::text('nombre',null, ['class'=>'form-control','placeholder'=>'Buscar
                                    Organigrama']) !!}
                                </div>
                                <button type="submit" class="btn btn-default pull-right">Buscar</button>
                            </div>

                            {!!Form::close()!!}
                            <!-- Fin Buscador -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Descripcion</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $key => $servicio)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $servicio->item }}</td>

                                    @switch( $servicio->type )
                                    @case('final')
                                    <td>Final</td>
                                    @break

                                    @case('de_apoyo')
                                    <td>De Apoyo</td>
                                    @break

                                    @default
                                    <td>Sin tipo</td>
                                    @endswitch

                                    <td>{!! $servicio->description !!}</td>
                                    <td>
                                        <a class="btn btn-primary btn-circle"
                                            href="{{ route('proyectos-epc-servicios.edit',$servicio->id) }}"><i
                                                class="far fa-edit"></i></a>
                                        </i>
                                        </a>
                                        <a class="btn btn-info btn-circle"
                                            href="{{route('proyectos-epc-servicios.show', $servicio->id)}}"><i
                                                class="far fa-eye"></i></a>

                                        <a href="#" onclick="deleteConfirm('{{ $servicio->id }}')">
                                            <button class="btn btn-danger btn-circle">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </a>

                                        {!! Form::open([
                                        'route' => ['proyectos-epc-servicios.destroy', $servicio->id],
                                        'method' => 'DELETE',
                                        'id' => $servicio->id,
                                        'style' => 'display:inline',
                                        ]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
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
@section('scripts')
<script>
    window.deleteConfirm = function(formId) {
            Swal.fire({
                icon: 'warning',
                text: 'Quieres borrar esto?',
                showCancelButton: true,
                confirmButtonText: 'Borrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e3342f',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit()
                }
            });
        }
</script>
@endsection