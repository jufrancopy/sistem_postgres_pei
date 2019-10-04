@extends('layouts.master')

@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title ">Selecccionar Ambiente</h4>

						<div class="pull-right">
							<a class="btn btn-warning" href="{{ route('fodas-dashboard') }}"> Atras</a>
						</div>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<p><label>Perfil:</label> {{ $perfil->nombre}}</p>
								<p><label>Contexto</label> {{ $perfil->contexto}}</p>
								
								<table class="table table-bordered">
										<tr>
											<th>Ambiente</th>
											<th >Tipos</th>
											<th >Acciones</th>
										</tr>
										
										<tr>
											<td><h1><b>INTERNO</b></h1></td>
											<td><p class="btn btn-success btn-lg">Fortalezas<p> <p class="btn btn-danger btn-lg">Debilidades<p></td>
											<td><a href="{{route('foda-analisis-ambiente-interno', $perfil->id)}}"><button class="btn btn-default">Analizar</button></a></td>
										</tr>

										<tr>
											<td><h1><b>EXTERNO</b></h1></td>
											<td><p class="btn btn-success btn-lg">Oportunidades<p> <p class="btn btn-danger btn-lg">Amenazas<p></td>
											<td><a href="{{route('foda-analisis-ambiente-externo', $perfil->id)}}"><button class="btn btn-default">Analizar</button></a></td>
										</tr>
										
									</table>
								</div>
								
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection