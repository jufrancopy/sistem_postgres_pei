@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar turno <b>{{$turno->item}}</b></h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-turnos.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($turno, ['route'=>['proyectos-epc-turnos.update', $turno->id],
            			'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.turnos.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection