{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{ Form::label('item', 'Nombre:')	}}
	{{ Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{ Form::label('type', 'Tipo')	}}
	{{ Form::select('type', array(
			'final' => 'Final',
			'de_apoyo' => 'De Apoyo'),
			null, ['class' => 'form-control'])	}}
</div>

<div class="form-group">
	{{ Form::label('description', 'Descripción:')	}}
	{{ Form::text('description', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	<label for="equipamientos">Equipamientos:
		<button type="button" class="btn btn-success btn-circle" id="addEquipamientoCantidad"><i class="fa fa-plus"></i>
		</button>

	</label>
	<div data-equipamiento-cantidad='@json($equipamientoCantidad)'>
		<div>
			<div id="equipamientoCantidadFields"></div>
		</div>
	</div>
</div>


<div class="form-group">
	<label for="tthhs">Talentos Humanos:</label>
	<button type="button" class="btn btn-success btn-circle" id="addTthhCantidad"><i class="fa fa-plus"></i>
	</button>


	<div data-tthh-cantidad='@json($tthhCantidad)'>
		<div>
			<div id="tthhCantidadFields"></div>
		</div>
	</div>
</div>

<div class="form-group">
	<label for="tthhs">Infraestructuras:
		<button type="button" class="btn btn-success btn-circle" id="addInfraestructuraCantidad"><i
				class="fa fa-plus"></i>
		</button>
	</label>

	<div data-infraestructura-cantidad='@json($infraestructuraCantidad)'>
		<div>
			<div id="infraestructuraCantidadFields"></div>
		</div>
	</div>
</div>

<div class="form-group">
	<label for="tthhs">Apoyo Administrativos:
		<button type="button" class="btn btn-success btn-circle" id="addApoyoAdministrativoCantidad"><i
				class="fa fa-plus"></i>
		</button>
	</label>
	<div data-apoyo-administrativo-cantidad='@json($apoyoAdministrativoCantidad)'>
		<div>
			<div id="apoyoAdministrativoCantidadFields"></div>
		</div>
	</div>
</div>

<div class="form-group">
	<label for="tthhs">Otros Servicios:
		<button type="button" class="btn btn-success btn-circle" id="addOtroServicioCantidad"><i
				class="fa fa-plus"></i>
		</button>
	</label>
	<div data-otro-servicio-cantidad='@json($otroServicioCantidad)'>
		<div>
			<div id="otroServicioCantidadFields"></div>
		</div>
	</div>
</div>

<div class="form-group">
	{{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')
<script>
	$(document).ready(function() {
		var addEquipamientoCantidad = function(data) {
				var $divRow = $('<div>').addClass('row')
					.css({ marginTop: '4px' }).appendTo('#equipamientoCantidadFields');
					
				var marginTopBtn = { marginTop: 0 };
				var $equipamiento = $('<select>').addClass('form-control equipamiento-field')
									.prop('name', 'equipamientos[]')
									.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

				var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field').attr('placeholder', 'Ingrese Cantidad')
					.prop('name', 'cantidadesEquipamientos[]')
					.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));
						
				var $remove = $('<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
									.css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
				if (data) {
					$('<option>').val(data.id).text(data.item).appendTo($equipamiento);
					$cantidad.val(data.pivot.cantidad).appendTo($cantidad);
					}
				
				$remove.click(function(e) {
					e.preventDefault();
					$divRow.remove();
				})
				
				addSelect2($equipamiento, 'equipamientos', '/equipamientos/get', 'Seleccionar Equipamiento');
			}

		var addTthhCantidad = function(data) {
			var $divRow = $('<div>').addClass('row')
				.css({ marginTop: '4px' }).appendTo('#tthhCantidadFields');
				
			var marginTopBtn = { marginTop: 0 };
			var $tthh = $('<select>').addClass('form-control tthh-field')
								.prop('name', 'tthhs[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field').attr('placeholder', 'Ingrese Cantidad')
								.prop('name', 'cantidadesTthh[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $remove = $('<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
			
			if (data) {
				$('<option>').val(data.id).text(data.item).appendTo($tthh);
				$cantidad.val(data.pivot.cantidad).appendTo($cantidad);
				}
			
			$remove.click(function(e) {
				e.preventDefault();
				$divRow.remove();
			})
			
			addSelect2($tthh, 'tthhs', '/tthhs/get', 'Seleccionar TTHH');
		}  

		var addInfraestructuraCantidad = function(data) {
			var $divRow = $('<div>').addClass('row')
				.css({ marginTop: '4px' }).appendTo('#infraestructuraCantidadFields');
				
			var marginTopBtn = { marginTop: 0 };
			var $infraestructura = $('<select>').addClass('form-control tthh-field')
								.prop('name', 'infraestructuras[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field').attr('placeholder', 'Ingrese Cantidad')
								.prop('name', 'cantidadesInfraestructuras[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $remove = $('<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
			
			if (data) {
				$('<option>').val(data.id).text(data.item).appendTo($infraestructura);
				$cantidad.val(data.pivot.cantidad).appendTo($cantidad);
				}
			
			$remove.click(function(e) {
				e.preventDefault();
				$divRow.remove();
			})
			
			addSelect2($infraestructura, 'infraestructuras', '/infraestructuras/get', 'Seleccionar Infraestructura');
		} 

		var addApoyoAdministrativoCantidad = function(data) {
			var $divRow = $('<div>').addClass('row')
				.css({ marginTop: '4px' }).appendTo('#apoyoAdministrativoCantidadFields');
				
			var marginTopBtn = { marginTop: 0 };
			var $apoyoAdministrativo = $('<select>').addClass('form-control tthh-field')
								.prop('name', 'apoyoAdministrativos[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field').attr('placeholder', 'Ingrese Cantidad')
								.prop('name', 'cantidadesApoyoAdministrativos[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $remove = $('<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
			
			if (data) {
				$('<option>').val(data.id).text(data.item).appendTo($apoyoAdministrativo);
				$cantidad.val(data.pivot.cantidad).appendTo($cantidad);
				}
			
			$remove.click(function(e) {
				e.preventDefault();
				$divRow.remove();
			})
			
			addSelect2($apoyoAdministrativo, 'apoyoAdministrativos', '/apoyo-administrativos/get', 'Seleccionar Apoyo Administrativo');
		}   
		
		var addOtroServicioCantidad = function(data) {
			var $divRow = $('<div>').addClass('row')
				.css({ marginTop: '4px' }).appendTo('#otroServicioCantidadFields');
				
			var marginTopBtn = { marginTop: 0 };
			var $otroServicio = $('<select>').addClass('form-control tthh-field')
								.prop('name', 'otroServicios[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field').attr('placeholder', 'Ingrese Cantidad')
								.prop('name', 'cantidadesOtroServicios[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $remove = $('<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
			
			if (data) {
				$('<option>').val(data.id).text(data.item).appendTo($otroServicio);
				$cantidad.val(data.pivot.cantidad).appendTo($cantidad);
				}
			
			$remove.click(function(e) {
				e.preventDefault();
				$divRow.remove();
			})
			
			addSelect2($otroServicio, 'otroServicios', '/otro-servicios/get', 'Seleccionar Otros Servicios');
		} 
		var addSelect2 = function($element, name, url, placeholder) {
			var parameters = {};
	
			$element.select2({
				width: 'resolve',
				ajax: {
					url: url,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						parameters.q = params.term;
						
						return parameters;
					},
					processResults: function (data, params) {
						return { results: data };
					},
				},
				placeholder: placeholder,
				escapeMarkup: function (markup) { return markup; },
				minimumInputLength: 1,
				templateResult: function(data) {        
					if (data.loading) { return data.text; }
					var markup = data.text;
					return markup;
				},
				templateSelection: function(data) { return data.text; }
			});    
		} 
		      
			$(window).on("load", function() {
				var equipamientoCantidad = $('[data-equipamiento-cantidad]').data('equipamientoCantidad');
				$(equipamientoCantidad).each(function(index, data) {
					addEquipamientoCantidad(data);
				})

				var tthhCantidad = $('[data-tthh-cantidad]').data('tthhCantidad');
				$(tthhCantidad).each(function(index, data) {
					addTthhCantidad(data);
				})

				var infraestructuraCantidad = $('[data-infraestructura-cantidad]').data('infraestructuraCantidad');
				$(infraestructuraCantidad).each(function(index, data) {
					addInfraestructuraCantidad(data);
				})

				var apoyoAdministrativoCantidad = $('[data-apoyo-administrativo-cantidad]').data('apoyoAdministrativoCantidad');
				$(apoyoAdministrativoCantidad).each(function(index, data) {
					addApoyoAdministrativoCantidad(data);
				})

				var otroServicioCantidad = $('[data-otro-servicio-cantidad]').data('otroServicioCantidad');
				$(otroServicioCantidad).each(function(index, data) {
					addOtroServicioCantidad(data);
				})
			});

			$('#addEquipamientoCantidad').on('click', function(event) {
				event.preventDefault();
				addEquipamientoCantidad();
			});

			$('#addTthhCantidad').on('click', function(event) {
				event.preventDefault();
				addTthhCantidad();
			});

			$('#addInfraestructuraCantidad').on('click', function(event) {
				event.preventDefault();
				addInfraestructuraCantidad();
			});

			$('#addApoyoAdministrativoCantidad').on('click', function(event) {
				event.preventDefault();
				addApoyoAdministrativoCantidad();
			});

			$('#addOtroServicioCantidad').on('click', function(event) {
				event.preventDefault();
				addOtroServicioCantidad();
			});
			
		});
</script>
@endsection