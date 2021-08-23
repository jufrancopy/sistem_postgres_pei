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
		@foreach($detailEquipamientos as $key => $value)
		<option value="{{ $key }}" {{ in_array($key, $detailEquipamientosChecked) ? 'selected' : null }}>{{ $value }}
		</option>
		@endforeach
	</select>
</div>


<div class="form-group">
	{{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>