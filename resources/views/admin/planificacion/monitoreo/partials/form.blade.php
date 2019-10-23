<div class="form-group">
	{{	Form::label('item', 'Item')	}}
	{{	Form::text('item', null,['class'=>'form-control', 'id'=> 'item'])	}}
</div>

<div class="form-group">
	{{	Form::label('tipo_evaluacion_id', 'Seleccione una Carrera:')	}}
	{!! Form::select('tipo_evaluacion_id', $tipoevaluaciones ,null,['placeholder'=>'Elija una carrera','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}
</div>

<div class="form-group">
	{{	Form::label('beneficiado', 'Beneficiado')	}}
	{{	Form::select('beneficiado', array('docentes' => 'docentes', 'funcionarios' => 'funcionarios','institucion' => 'institucion','class'=>'form-control'))	}}
</div>

<div class="form-group">
	{{	Form::label('beneficiado', 'Beneficiado')	}}
	{{	Form::select('beneficiado', array('informatico' => 'informatico', 'biomedico' => 'biomedico','class'=>'form-control'))	}}
</div>

<div class="form-group">
	{{	Form::label('tipo', 'Tipo')	}}
	{{	Form::select('tipo', array('nueva' => 'Nueva', 'readecuacion' => 'Readeacuación', 'read_ampl' => 'Readecuación y Ampliación'))	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'btn btn-sm btn-primary'])	}}
</div>
<!-- Aqui puede ir codido JavaScript personalizado -->
@section('script')
@endsection