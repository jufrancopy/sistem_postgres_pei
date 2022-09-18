@extends('layouts.master')

@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar dependencia a {{$dependencia->dependency}}</h4>
						<div class="pull-right">
							<a class="btn btn-warning" href="{{ route('globales.organigrama-gestionar', $rootId) }}"> Atras</a>
						  </div>
						
					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'globales.organigramas.store']) !!}

						@include('admin.globales.organigramas.partials.form-crear-sub-dependencia')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
