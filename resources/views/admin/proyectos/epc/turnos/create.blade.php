@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar Nuevo Turno</h4>
					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'proyectos-epc-turnos.store']) !!}

						@include('admin.proyectos.epc.turnos.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection