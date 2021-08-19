@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar nuevo Item de Otro Servicio</h4>
					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'proyectos-epc-otros_servs.store']) !!}

						@include('admin.proyectos.epc.otros_servicios.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection