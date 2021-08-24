<div class="form-group">
	{{	Form::label('item', 'Nombre:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('MyName', array(
			'Value One' => 'Insumos y Medicamentos',
			'Value Two' => 'Dos',
			'Value Three' => 'Tres'),
			null, ['class' => 'form-control'])	}}
</div>

<div class="form-group">
	{{	Form::label('cost', 'Costo:')	}}
	{{	Form::text('cost', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

 
 <div>
	 Text: <span id="text"></span>
 </div>
 <div>
	 Value: <span id="value"></span>
 </div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>


@section('scripts')
<script>
	$("[name='MyName']").change(function() {
    
    $("#text").text($("[name='MyName'] option:selected").text());
    
    $("#value").text($("[name='MyName']").val());
    
});
$("[name='MyName']").change();
</script>
@endsection