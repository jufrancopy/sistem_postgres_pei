@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$estrategia->estrategia}}</h4>
					<a class="btn btn-warning" href="{{ route('peis-objetivos.show', $estrategia->objetivo_id) }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($estrategia, ['route'=>['peis-estrategias.update', $estrategia->id],
            'method'=>'PUT']) !!}

						@include('admin.planificacion.peis.estrategias.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection