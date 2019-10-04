@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Panel de Foda</h4>
						<a href="{{route('fodas.create')}}" class="btn btn-sm btn-success">Nuevo Aspecto</a>
					</div>
					
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<!-- AquiBuscador -->
								<div class="float-right">
									{!! Form::open(['route' => 'fodas.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','aspecto'=>'search']) !!}
									<div class="form-group">
										{!! Form::text('aspecto',null, ['class'=>'form-control','placeholder'=>'Buscar Aspecto']) !!}
									</div>
									
									<button type="submit" class="btn btn-default pull-right">Buscar</button>
									
								</div>
								
								{!!Form::close()!!}
								<!-- Fin Buscador -->
								<thead>
									<tr>
										<th>id</th>
										<th>Nivel</th>
										<th>Aspecto</th>
										<th>Tipo</th>
										<th>Ambiente</th>
										<th>Ocurrencia</th>
										<th>Impacto</th>
									</tr>
								</thead>
								<tbody>
									@foreach($tthhs as $tthh)
									<tr>
										<td>{{$tthh->id}}</td>
										<td>{{$tthh->nivel}}</td>
										<td>{{$tthh->aspecto}}</td>
										<td>{{$tthh->tipo}}</td>
										<td>{{$tthh->ambiente}}</td>
										<td>{{$tthh->ocurrencia}}</td>
										<td>{{$tthh->impacto}}</td>
										
										<td width="5px">
											<a href="{{route('fodas.show', $tthh->id)}}"
												class="btn btn-sm btn-default">
												ver
											</a>
										</td>
										<td width="5px">
											<a href="{{route('fodas.edit',$tthh->id)}}"
												class="btn btn-sm btn-default">
												editar
											</a>
										</td>
										<td width="5px">
											{!! Form::open(['route' => ['fodas.destroy', $tthh->id], 'method' => 'DELETE']) !!}
											<button class="btn btn-sm btn-danger" onclick="return confirm('Estas seguro de eliminar a {{$tthh->aspecto}} . Si lo eliminas también eliminarás los datos asociados a el.')">
											Eliminar
											</button>
											{!! Form::close() !!}
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer" style="text-align: center;">
						{{$tthhs->render()}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection