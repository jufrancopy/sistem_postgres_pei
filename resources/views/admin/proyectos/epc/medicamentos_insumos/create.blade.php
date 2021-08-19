@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Agregar nuevo Item de Producto</h4>
					</div>
					<div class="card-body">
						{!! Form::open(['route'=>'proyectos-epc-mds_ins.store']) !!}

						@include('admin.proyectos.epc.medicamentos_insumos.partials.form')

						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection