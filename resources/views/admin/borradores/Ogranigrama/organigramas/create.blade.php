@extends('layouts.usuarios.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Panel de Matriculación</h4>
					</div>
					<div class="card-body">
						{!! Form::open(['route' => 'us_matriculaciones.store', 'files'=>true]) !!}
						@include('usuarios.matriculaciones.partials.form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection