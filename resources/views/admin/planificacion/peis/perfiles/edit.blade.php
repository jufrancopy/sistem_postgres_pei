@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Editar Perfil de Planficación Estratégica</h4>
					</div>
					<div class="card-body">
						{!! Form::model($perfilPei, ['route'=>['peis-perfiles.update', $perfilPei->id],
            'method'=>'PUT']) !!}

						@include('admin.planificacion.peis.perfiles.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection