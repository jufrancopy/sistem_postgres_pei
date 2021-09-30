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
                    <h4 class="card-title ">Horarios</h4>
                    <a class="btn btn-success" href="{{ route('proyectos-epc-horarios.create') }}">Agregar</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('proyectos-epc-dashboard') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <!-- AquiBuscador -->
                            <div class="float-right">
                                {!! Form::open(['route' => 'organigramas-listado','method' => 'GET',
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
                                    <th>Ítem</th>
                                    <th>Hora de Entrada</th>
                                    <th>Hora Final</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horarios as $key => $horario)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $horario->item }}</td>
                                    <td>{{ $horario->start_time }}</td>
                                    <td>{{ $horario->end_time }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-circle"
                                            href="{{ route('proyectos-epc-horarios.edit',$horario->id) }}"><i class="far fa-edit"></i></a>
                                            </i>
                                        </a>
                                       
                                        <a href="#" onclick="deleteConfirm('{{$horario->id}}')"> 
                                            <button class="btn btn-danger btn-circle">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </a>

                                        {!! Form::open([
                                            'route' => ['proyectos-epc-horarios.destroy',$horario->id],
                                            'method' => 'DELETE',
                                            'id'=> $horario->id,
                                            'style'=>'display:inline']) !!}
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
    window.deleteConfirm = function(formId)
{
    console.log(formId)
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
