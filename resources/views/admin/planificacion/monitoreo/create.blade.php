@extends('layouts.master')
@section('content')
<div class="container">
	<div class="col-md-8 col-md-offset-2">
		
		<div class="panel panel-default">
			<div class="panel-heading">
			Cargar Reportes de Dependencias
			</div>
			<div class="panel-body">
				{!!	Form::open(['route'=>'reportes.store'])	!!}

					@include('planificacion.evaluaciones.partials.form')

				{!!	Form::close()	!!}
			</div>
		</div>
	</div>
</div>
@endsection
