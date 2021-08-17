@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$especialidad->item}}</h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-especialidades.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($especialidad, ['route'=>['proyectos-epc-especialidades.update', $especialidad->id],
            'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.especialidades.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection