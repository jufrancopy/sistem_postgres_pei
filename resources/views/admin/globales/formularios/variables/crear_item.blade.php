@extends('layouts.master')

@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar dependencia a {{$variable->name}}</h4>
						<div class="pull-right">
							@if($rootId == null)
							<a class="btn btn-warning"
								href="{{ route('globales.gestionar-variable', $variable->id) }}"> Atras</a>
							@else
							<a class="btn btn-warning" href="{{ route('globales.gestionar-variable', $rootId) }}">
								Atras</a>
							@endif
						</div>

					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'globales.variables.store']) !!}

						@include('admin.globales.formularios.variables.partials.form-crear-sub-item')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection