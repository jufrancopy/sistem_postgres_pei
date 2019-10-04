@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Listado de <b>{{$fodas[0]->nivel}}</b></h4>
						
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
										<th>Ambiente</th>
										<th>Aspecto</th>
										<th>Tipo</th>
										<th colspan="2">Acciones</th>


									</tr>
								</thead>
								<tbody>
									@foreach($fodas as $foda)
									<tr>
										<td><i class="material-icons">call_received

											</i></td>
										<td>{{$foda->aspecto}}</td>

										@switch($foda->tipo)

											@case('fortaleza')
											<td class="badge badge-success">Fortaleza</td>
											@break

											@case('oportunidad')
											<td class="badge badge-info">Oportunidad</td>
											@break
											@case('debilidad')
											
											<td class="badge badge-danger">Debilidad</td>
											@break

											@case('amenaza')
											<td cclass="badge badge-warning">Amenaza</td>
											@break

											@default
											<td>Pendiente</td>
										@endswitch



										<td width="5px">
											<a href="{{route('analisis.fortaleza', $foda->id) }}" class="btn btn-sm btn">
												fortaleza
											</a>
										</td>
										<td width="5px">
											<a href="{{route('analisis.debilidad', $foda->id)}}" class="btn btn-sm btn">
												Debilidad
											</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer" style="text-align: center;">
						<!-- Aqui va el render -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection