@extends('layouts.master') @section('content')
<div class="container">
	<div class="col-md-12">
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Panel de Evaluaciones</h1>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div id=contenedorico>
							<p id="tituloico">Capacitaciones</p>
							<span class="iconheader-capacitacion"></span>
							<p id="bordecontador">{{$capacitaciones}}</p>
						</div>
					</div>
					<!-- /.Incorparacion -->
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div id=contenedorico>
							<p id="tituloico">Incorporaciones</p>
							<span class="iconheader-hospital_central"></span>
							<p id="bordecontador">{{$incorporaciones}}</p>
						</div>
					</div>
					
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div id=contenedorico>
							<p id="tituloico">Verificacion</p>
							<span class="iconheader-verificacion"></span>
							<p id="bordecontador">{{$capacitaciones}}</p>
						</div>
					</div>
					<!-- /.Evaluacion Desempeño -->
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div id=contenedorico>
							<p id="tituloico">Ev. Desempeño</p>
							<span class="iconheader-evaluacion"></span>
							<p id="bordecontador">11</p>
						</div>
					</div>
					<!-- /.Clinicas Perifericas -->
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div id=contenedorico>
							<p id="tituloico">Fiscalizacion</p>
							<span class="iconheader-fiscalizacion"></span>
							<p id="bordecontador">11</p>
						</div>
					</div>
					<!-- /.Puestos Sanitarios -->
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div id=contenedorico>
							<p id="tituloico">Jubilaciones</p>
							<span class="iconheader-jubilacion_ordinaria"></span>
							<p id="bordecontador">11</p>
						</div>
					</div>
				</div>
				<div class="row">
				</section>
				<section>
					<div class="col-md-2 col-sm-6 col-xs-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<ul class="list-group list-group-flush">
									<li class="list-group-item"><a href="{{route('reportes.create')}}" class="card-link">Reportes</a></li>
									<li class="list-group-item"><a href="{{route('listar_reportes')}}" class="card-link">Listar</a></li>
									<li class="list-group-item"><a href="{{route('tipo_evaluaciones.index')}}" class="card-link">Tipos de Evaluacion</a></li>
								</ul>
							</div>
						</div>
				</section>
				<section>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3>Cotizantes y Beneficiarios</h3>
							</div>
								<div id="piechart"></div>
							</div>

								<script type="text/javascript">
							google.charts.load('current', {'packages':['corechart']});
							google.charts.setOnLoadCallback(drawChart);
							
							function drawChart() {
							
							var data = google.visualization.arrayToDataTable([
							['id', 'Cursos'],
							@foreach ($items as $item)
							['{{$item->tipos_evaluaciones->nombre}}', {{$item->total}}],
							@endforeach
							]);
							
							var options = {
							title: ''
							};
							
							var chart = new google.visualization.PieChart(document.getElementById('piechart'));
							
							chart.draw(data, options);
							}
						</script>
						</div>
						
					
						
						<script>
							google.charts.load('current', {'packages':['bar']});
							google.charts.setOnLoadCallback(drawChart);
							function drawChart() {
							var data = google.visualization.arrayToDataTable([
							['Año', 'Tipo de Evaluacion', 'Egreso'/*@foreach ($capacitaciones_graf as $capacitacion_graf) '{{$capacitacion_graf->item}}', @endforeach*/ ],
							['2014', 80,60],
							['2015', 215,150],
							]);
							var options = {
							chart: {
							title: 'Company Performance',
							subtitle: 'Sales, Expenses, and Profit: 2014-2017',
							}
							};
							var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
							chart.draw(data, google.charts.Bar.convertOptions(options));
							}
						</script>
						<div class="col-md-2 col-sm-6 col-xs-12">
							<b>Clasificación por Areas Funcionales</b>
							<div class="info-box-content">
								<div class="col-md-2 col-sm-6 col-xs-12">
									<div id="piechart">
										
									</div>
									<div id="columnchart_material">
										
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
			@endsection