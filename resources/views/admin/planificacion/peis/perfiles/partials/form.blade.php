{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
    {{ Form::label('nombre', 'Nombre:')	}}
    {{ Form::text('nombre', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
    {{ Form::label('responsable', 'Responsable:')	}}
    {{ Form::text('responsable', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
    {{ Form::label('mision', 'Misión:')	}}
    {{ Form::text('mision', null,['class'=>'form-control','id'=>'mision'])	}}
</div>

<div class="form-group">
    {{ Form::label('vision', 'Visión:')	}}
    {{ Form::text('vision', null,['class'=>'form-control','id'=>'vision'])	}}
</div>

<div class="form-group">
    {{ Form::label('valores', 'Valores:')	}}
    {{ Form::text('valores', null,['class'=>'form-control','id'=>'valores'])	}}
</div>

<div class="form-group">
    {{ Form::label('objetivo_id', 'Objetivos:') }} 
    <select multiple="multiple" name="objetivo_id[]" id="objetivo_id" class="selectors" style="width:100%">
		@foreach($objetivos as $key => $value)
			<option value="{{ $key }}" {{ in_array($key, $objetivosChecked) ? 'selected' : null }}>{{ $value }}</option>
		@endforeach
	</select>    
</div>

{{-- <div class="form-group">
    {{ Form::label('estraetgia_id', 'Estrategias') }} 
    <select multiple="multiple" name="estraetgia_id[]" id="estraetgia_id" class="selectors" style="width:100%">
		@foreach($estrategias as $key => $value)
			<option value="{{ $key }}" {{ in_array($key, $estrategiasChecked) ? 'selected' : null }}>{{ $value }}</option>
		@endforeach
	</select>    
</div> --}}

<div class="form-group">
    {{ Form::label('vigencia', 'Vigencia:')	}}
    {{ Form::text('vigencia', null,['class'=>'form-control','id'=>'vigencia'])	}}
</div>

<div class="form-group">
    {{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')

<script>
	// Select2
	$(document).ready(function() {
		$('.selectors').select2({
			placeholder: "Agregue Objetivos",
			language: "es",
		});
	})
</script>
@endsection