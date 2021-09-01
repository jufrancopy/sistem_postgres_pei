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
		<button type="button" class="btn btn-sm btn-success" id="addEquipamientoCantidad">Agregar Equipamientos
		</button>

	</label>
	<div data-equipamiento-cantidad='@json($equipamientoCantidad)'>
		<div>
			<div id="equipamientoCantidadFields"></div>
		</div>
	</div>
</div>


<div class="form-group">
	<label for="tthhs">Talentos Humanos: <button type="button" class="btn btn-sm btn-success"
			id="addTthhCantidad">Agregar Talentos Humanos</button></label>
	<div data-tthh-cantidad='@json($tthhCantidad)'>
		<div>
			<div id="tthhCantidadFields"></div>
		</div>
	</div>
</div>

<div class="form-group">
	<label for="tthhs">Infraestructuras:
		<button type="button" class="btn btn-sm btn-success" id="addInfraestructuraCantidad">Agregar Infraestructuras
		</button>
	</label>

	<div data-infraestructura-cantidad='@json($infraestructuraCantidad)'>
		<div>
			<div id="infraestructuraCantidadFields"></div>
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

				var $remove = $('<button>', { type: 'button' }).addClass('btn btn-sm btn-danger')
									.css(marginTopBtn).text('Eliminar').appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
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

			var $remove = $('<button>', { type: 'button' }).addClass('btn btn-sm btn-danger')
								.css(marginTopBtn).text('Eliminar').appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
			
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
			var $tthh = $('<select>').addClass('form-control tthh-field')
								.prop('name', 'infraestructuras[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field').attr('placeholder', 'Ingrese Cantidad')
								.prop('name', 'cantidadesInfraestructuras[]')
								.css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

			var $remove = $('<button>', { type: 'button' }).addClass('btn btn-sm btn-danger')
								.css(marginTopBtn).text('Eliminar').appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
			
			if (data) {
				$('<option>').val(data.id).text(data.item).appendTo($tthh);
				$cantidad.val(data.pivot.cantidad).appendTo($cantidad);
				}
			
			$remove.click(function(e) {
				e.preventDefault();
				$divRow.remove();
			})
			
			addSelect2($tthh, 'infraestructuras', '/infraestructuras/get', 'Seleccionar Infraestructura');
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
		});
</script>
@endsection