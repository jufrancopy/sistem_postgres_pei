<div class="form-group">
	{{	Form::label('nombre', 'Nombre')	}}
	{{	Form::text('nombre', null,['class'=>'form-control', 'id'=> 'nombre'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'btn btn-sm btn-primary'])	}}
</div>

<!-- Aqui puede ir codido JavaScript personalizado -->
@section('script')
@endsection