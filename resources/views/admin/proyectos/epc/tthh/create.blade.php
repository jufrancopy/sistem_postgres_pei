@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar Nuevo Talento humano</h4>
						<div class="pull-right">
							<a class="btn btn-warning pull-right" href="{{ route('proyectos-epc-tthh.index') }}"> Atras</a>
						</div>
					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'proyectos-epc-tthh.store']) !!}

						@include('admin.proyectos.epc.tthh.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection