@extends('layouts.usuarios.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Detalles de Matriculación</h4>
					</div>
					<div class="card-body">

				    <div class="img-thumbnail pull-right">
				        <img src="{{ $matriculacion->alumno->file}}" >
				    </div>
					<p><strong>Alumno: </strong>{{ $matriculacion->alumno->nombre}}</p>
					<p><strong>Apellido: </strong>{{ $matriculacion->alumno->apellido}}</p>
					<p><strong>Cédula de Identidad: </strong>{{ $matriculacion->alumno->ci}}</p>
					<p><strong>Teléfono: </strong>{{ $matriculacion->alumno->telefono}}</p>
					<p><strong>Correo: </strong>{{ $matriculacion->alumno->correo}}</p>
					<p><strong>Modalidad: </strong>{{ $matriculacion->modalidad->nombre}}</p>
					<p><strong>Carrera: </strong>{{ $matriculacion->carrera->nombre}}</p>
					<p><strong>Curso: </strong>{{ $matriculacion->curso->nombre}}</p>
					<p><strong>Materias Habilitadas: </strong></p>
                  <!--<div class="table-responsive">
    			        <table class="table table-bordered">
    			            <tr>
    			                <th>Nombre</th>
                                <th colspan="3">Acciones</th>
                            </tr>
                            @foreach($materias as $materia)
    				        <tr>
    				            <td>{{ $materia->nombre }}</td>
    				            <td><a href="{{route('us_calificaciones.show', ['idMatr' => $matriculacion->id, 'idMat' => $materia->id])}}"><i class="fas fa-eye"></i></a></td>
        	                    <td><a href="{{route('us_calificaciones.create', ['idMatr' => $matriculacion->id, 'idMat' => $materia->id])}}"><i class="fas fa-plus-circle"></i></a></td>
                            </tr>
                           @endforeach
                       </table>
                    </div>-->
	                <p><strong>Periodo: </strong>	{{ $matriculacion->periodo}}</p>
					<p><strong>Estado: </strong>	{{ $matriculacion->estado}}</p>
					<hr>
					<p><strong>Responsable: </strong>	{{ $matriculacion->user->name}}</p>
					<p><strong>Actualizado: </strong>	{{ $matriculacion->created_at->diffForHumans()}}</p>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
