@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
					<h4 class="card-title ">Editar {{$tthh->name}}</h4>
					<a class="btn btn-warning" href="{{ route('proyectos-epc-tthh.index') }}">
						Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($tthh, ['route'=>['proyectos-epc-tthh.update', $tthh->id],
            'method'=>'PUT']) !!}

						@include('admin.proyectos.epc.tthh.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection