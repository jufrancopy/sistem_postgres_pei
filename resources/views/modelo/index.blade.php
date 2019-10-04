@extends('layouts.usuarios.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Panel de Matriculaciones</h4>
                        <a href="{{route('us_matriculaciones.create')}}" class="btn btn-sm btn-success">crear</a>
                    </div>
               
                    <div class="card-body"><div class="table-responsive">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
							    <!-- AquiBuscador -->
								<div class="float-right">
									{!! Form::open(['route' => 'us_matriculaciones.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
									<div class="form-group">
										{!! Form::text('ci',null, ['class'=>'form-control','placeholder'=>'Ingrese Nro de CI']) !!}
									</div>
									    <button type="submit" class="btn btn-default pull-right">Buscar</button>
								</div>
								
								{!!Form::close()!!}
								<!-- Fin Buscador -->
								<thead>
									<tr>
										<th>CI</th>
										<th>Nombre</th>
										<th>Apellido</th>
										<th>Curso</th>
										<th>Modalidad</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									@foreach($matriculaciones as $matriculacion)
									<tr>
										<td>{{$matriculacion->alumno->ci}}</td>
										<td>{{$matriculacion->alumno->nombre}}</td>
										<td>{{$matriculacion->alumno->apellido}}</td>
										<td>{{$matriculacion->curso->nombre}}</td>
										<td>{{$matriculacion->modalidad->nombre}}</td>
										
										<td width="10px">
											<a href="{{route('us_cronogramas.show', $matriculacion->id)}}"
												class="btn btn-sm btn-default">
												Materias
											</a>
										</td>
										
										<td width="10px">
											<a href="{{route('us_matriculaciones.show', $matriculacion->id)}}"
												class="btn btn-sm btn-default">
												ver
											</a>
										</td>
										
										<td width="10px">
											<a href="{{route('us_matriculaciones.edit',$matriculacion->id)}}"
												class="btn btn-sm btn-default">
												editar
											</a>
										</td>
										
										<td width="10px">
											{!! Form::open(['route' => ['us_matriculaciones.destroy', $matriculacion->id], 'method' => 'DELETE']) !!}
											<button class="btn btn-sm btn-danger" onclick="return confirm('Estas seguro de eliminar la mtriculacion de {{$matriculacion->alumno->nombre}} {{$matriculacion->alumno->apellido}}. Si lo eliminas también eliminarás los datos asociados a el.')">
											Eliminar
											</button>
											{!! Form::close() !!}
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							{{	$matriculaciones->render() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	@endsection