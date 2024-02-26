    {{ Form::hidden('patrimony_id', null, ['id' => 'group_id']) }}

    <div class="form-group">
        {{ Form::label('type', 'Tipo') }}
        {{ Form::select(
            'type',
            [
                'BIEN DE USO' => 'BIEN DE USO',
                'BIEN DE RENTA' => 'BIEN DE RENTA',
            ],
            null,
            ['class' => 'form-control', 'placeholder' => '', 'id' => 'type', 'style' => 'width:100%'],
        ) }}
    </div>

    <div class="form-group">
        {{ Form::label('quantityAccountCurrent', 'Cantidad de Cuenta Corriente:', ['class' => 'control-label']) }}
        {{ Form::number('quantityAccountCurrent', null, ['class' => 'form-control', 'id' => 'quantityAccountCurrent']) }}
    </div>

    <div class="form-group">
        {{ Form::label('detailLocation', 'Detalle de Ubicación:', ['class' => 'control-label']) }}
        {{ Form::text('detailLocation', null, ['class' => 'form-control', 'id' => 'detailLocation']) }}
    </div>

    <div class="form-group">
        {{ Form::label('infrastructureType', 'Tipo de Infraestructura') }}
        {{ Form::select(
            'infrastructureType',
            [
                'Casa' => 'Casa',
                'Edificio' => 'Edificio',
                'Predio Vacío' => 'Predio Vacío',
            ],
            null,
            ['class' => 'form-control', 'id' => 'infrastructureType', 'style' => 'width:100%'],
        ) }}
    </div>


    <div class="form-group">
        {{ Form::label('startDateContract', 'Fecha Inicio Contrato:', ['class' => 'control-label']) }}
        {{ Form::date('startDateContract', null, ['class' => 'form-control', 'id' => 'startDateContract']) }}
    </div>

    <div class="form-group">
        {{ Form::label('endDateContract', 'Fecha Fin Contrato:', ['class' => 'control-label']) }}
        {{ Form::date('endDateContract', null, ['class' => 'form-control', 'id' => 'endDateContract']) }}
    </div>

    <div class="form-group">
        {{ Form::label('estateQuantity', 'Cantidad de Fincas:', ['class' => 'control-label']) }}
        {{ Form::number('estateQuantity', null, ['class' => 'form-control', 'id' => 'estateQuantity']) }}
    </div>

    <div class="form-group">
        {{ Form::label('currentAccount', 'Cuenta Corriente:', ['class' => 'control-label']) }}
        {{ Form::number('currentAccount', null, ['class' => 'form-control', 'id' => 'currentAccount']) }}
    </div>

    <div class="form-group">
        {!! Form::label('state', 'Departamento:') !!}
        {!! Form::select('state', $states, null, ['class' => 'form-control', 'id' => 'state', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group city">
        {!! Form::label('city', 'Ciudad:') !!}
        {!! Form::select('city', [], null, ['class' => 'form-control', 'id' => 'city', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group locality">
        {!! Form::label('locality', 'Barrio:') !!}
        {!! Form::select('locality', [], null, ['class' => 'form-control', 'id' => 'locality', 'style' => 'width:100%']) !!}
    </div>

    <div class="description mb-2">
        {{ Form::label('description', 'Descripción:', ['class' => 'control-label']) }}
        {{ Form::textarea('description', null, [
            'class' => 'form-control editor',
            'id' => 'description',
        ]) }}
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('file', 'Evidencia:') }}
                <div class="container-file">
                    <div class="choose-doc">
                        <div class="holder">
                            <img id="imgPreviewEvidence" src="https://placekitten.com/640/360" />
                        </div>
                        <label for="file" class="btn btn-warning d-block rounded-0">Evidencia</label>
                        <input type="file" name="evidenceFile" id="file" style="display:none">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('file', 'Foto Principal:') }}
                <div class="container-file">
                    <div class="choose-img">
                        <div class="holder">
                            <img id="imgPreview" src="" />
                        </div>
                        <label for="mainPhotoFile" class="btn d-block rounded-0">Foto</label>
                        <input type="file" name="mainPhotoFile" id="mainPhotoFile" style="display:none">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-offset-2 col-sm-10">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar
            cambios
        </button>
    </div>
