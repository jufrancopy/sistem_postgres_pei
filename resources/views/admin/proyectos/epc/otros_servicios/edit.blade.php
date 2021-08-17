@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$otroServicio->name}}</h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-ap_admins.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($otroServicio, ['route'=>['proyectos-epc-otros_servs.update', $otroServicio->id],
            'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.otros_servicios.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection