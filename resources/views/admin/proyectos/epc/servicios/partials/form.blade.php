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
	{{ Form::label('detail_equipamiento_id', 'Equipamientos:') }}
	<select multiple="multiple" name="detail_equipamiento_id[]" id="detail_equipamiento_id" class="js-example-responsive" style="width:100%">
		@foreach($equipamientos as $key => $value)
		<option value="{{ $key }}" {{ in_array($key, $equipamientosChecked) ? 'selected' : null }}>{{ $value }}
		</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	{{ Form::label('detail_tthh_id', 'Talentos Humanos:') }}
	<select multiple="multiple" name="detail_tthh_id[]" id="detail_tthh_id" class="js-example-responsive" style="width:100%">
		@foreach($tthh as $key => $value)
		<option value="{{ $key }}" {{ in_array($key, $tthhChecked) ? 'selected' : null }}>{{ $value }}
		</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	{{ Form::label('detail_medicamento-insumo_id', 'Medicamentos e Insumos:') }}
	<select multiple="multiple" name="detail_medicamento-insumo_id[]" id="detail_medicamento-insumo_id" class="js-example-responsive" style="width:100%">
		@foreach($medicamentoInsumos as $key => $value)
		<option value="{{ $key }}" {{ in_array($key, $medicamentoInsumosChecked) ? 'selected' : null }}>{{ $value }}
		</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	{{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>