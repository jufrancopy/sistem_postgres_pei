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
                        <h4 class="card-title ">Turnos</h4>
                        <a class="btn btn-success" href="{{ route('proyectos-epc-turnos.create') }}">Agregar</a>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('proyectos-dashboard') }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                
                                <!-- Aqui dBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'proyectos-epc-turnos.index', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}
                                    <div class="form-group">
                                        {!! Form::text('nombre', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Buscar Turno',
                                        ]) !!}
                                    </div>
                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>
                                </div>
                                {!! Form::close() !!}
                                <!-- Fin Buscador -->
                                
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ítem</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($turnos as $key => $turno)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $turno->item }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-circle"
                                                    href="{{ route('proyectos-epc-turnos.edit', $turno->id) }}"><i
                                                        class="far fa-edit"></i></a>
                                                </i>
                                                </a>


                                                <a href="#" onclick="deleteConfirm('{{ $turno->id }}')">
                                                    <button class="btn btn-danger btn-circle">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </a>

                                                {!! Form::open([
                                                    'route' => ['proyectos-epc-turnos.destroy', $turno->id],
                                                    'method' => 'DELETE',
                                                    'id' => $turno->id,
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
