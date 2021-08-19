@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
                    <h4 class="card-title ">Editar {{$programa->programa}}</h4>
                    <a class="btn btn-warning" href="{{ route('peis-estrategias.show', $programa->estrategia_id) }}">
                        Atras</a>
					</div>
					<div class="card-body">
						{!! Form::model($programa, ['route'=>['peis-programas.update', $programa->id],'method'=>'PUT']) !!}

						@include('admin.planificacion.peis.programas.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection