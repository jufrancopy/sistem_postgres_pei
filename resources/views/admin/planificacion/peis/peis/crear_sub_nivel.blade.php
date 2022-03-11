@extends('layouts.master')

@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar Sub-Dependencia {{$nivel->first()->nivel}} </h4>
					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'peis.store']) !!}

						@include('admin.planificacion.peis.peis.partials.form-crear-sub-nivel')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
