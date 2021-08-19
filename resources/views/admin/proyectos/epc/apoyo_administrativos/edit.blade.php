@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$apoyoAdministrativo->name}}</h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-ap_admins.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($apoyoAdministrativo, ['route'=>['proyectos-epc-ap_admins.update', $apoyoAdministrativo->id],
            'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.equipamientos.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection