@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar  <b>{{$prestacion->item}}</b></h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-horarios.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($prestacion, ['route'=>['proyectos-epc-prestaciones.update', $prestacion->id],
            'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.prestaciones.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection