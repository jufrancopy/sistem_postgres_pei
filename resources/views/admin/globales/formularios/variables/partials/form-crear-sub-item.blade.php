{{ Form::hidden('parent_id', $idVariable) }}
{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{ Form::label('name', 'Nombre:') }}
	{{ Form::text('name', null,['class'=>'form-control','id'=>'name']) }}
</div>


<div class="form-group">
	{{ Form::label('type', 'Tipo') }}
	{{ Form::select(
	'type',
	[
	'service' => 'Servicio',
	'require' => 'Requerimiento',
	'item' => 'Item',
	'response' => 'Respuesta'
	],
	null,
	['class' => 'form-control', 'placeholder'=>'', 'id'=>'type'],
	) }}
</div>

<div class="form-group">

	{{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary']) }}
</div>

@section('scripts')
<script>
	$(document).ready(function() {
    $('#type').select2();
});
</script>
@endsection