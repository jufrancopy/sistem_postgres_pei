@extends('layouts.master')
@section('content')
<div class="container">
	<div class="col-md-8 col-md-offset-2">
		<h1>Principal</h1>
		<div class="panel panel-default">
			<div class="panel-heading">
			Editar Servicios	
			</div>
			<div class="panel-body">
				{!!	Form::model($servicioadministrativo, ['route'=>['servs_adms.update', $servicioadministrativo->id], 'method'=>'PUT'])	!!}

					@include('proyecto.servs_adms.partials.form')
					
				{!!	Form::close()	!!}
			</div>
		</div>
	</div>
</div>
@endsection
