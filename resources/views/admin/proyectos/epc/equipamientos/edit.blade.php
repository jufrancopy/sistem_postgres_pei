@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$equipamiento->name}}</h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-equipamientos.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($equipamiento, ['route'=>['proyectos-epc-equipamientos.update', $equipamiento->id],
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