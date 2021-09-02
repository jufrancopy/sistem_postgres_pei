@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$servicio->item}}</h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-home') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($servicio, ['route'=>['proyectos-epc-servicios.update', $servicio->id],
            'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.servicios.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection