    {{ Form::hidden('patrimony_id', null, ['id' => 'patrimony_id']) }}

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
        {{ Form::label('estateQuantity', 'Cantidad de Fincas:', ['class' => 'control-label']) }}
        {{ Form::number('estateQuantity', null, ['class' => 'form-control', 'id' => 'estateQuantity']) }}
    </div>

    <div class="form-group">
        {!! Form::label('department', 'Departamento:') !!}
        {!! Form::select('department', $departments, null, [
            'class' => 'form-control',
            'id' => 'state',
            'style' => 'width:100%',
        ]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('city', 'Ciudad:') !!}
        {!! Form::select('city', [], null, ['class' => 'form-control', 'id' => 'city', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('locality', 'Barrio:') !!}
        {!! Form::select('locality', [], null, ['class' => 'form-control', 'id' => 'locality', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('latitude', 'Latitud:') !!}
        {!! Form::number('latitude', null, ['class' => 'form-control', 'id' => 'latitude', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('longitude', 'Longitud:') !!}
        {!! Form::number('longitude', null, ['class' => 'form-control', 'id' => 'longitude', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group">
        {{ Form::label('locationAddress', 'Detalle de Ubicación:', ['class' => 'control-label']) }}
        {{ Form::text('locationAddress', null, ['class' => 'form-control', 'id' => 'locationAddress']) }}
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
        {{ Form::label('description', 'Descripción:', ['class' => 'control-label']) }}
        {{ Form::textarea('description', null, [
            'class' => 'form-control editor',
            'id' => 'description',
            'style' => 'margin-top:10px !important',
        ]) }}
    </div>

    <div class="form-group">
        {{ Form::label('cadastralCurrentAccount', 'Cuenta Corriente Catastral:', ['class' => 'control-label']) }}
        {{ Form::number('cadastralCurrentAccount', null, ['class' => 'form-control', 'id' => 'cadastralCurrentAccount']) }}
    </div>

    <div class="form-group">
        {{ Form::label('estateStatus', 'Estado del Inmueble') }}
        {{ Form::select(
            'estateStatus',
            [
                'ALQUILADO + INV' => 'ALQUILADO + INV',
                'USUFRUCTO DEL IPS' => 'USUFRUCTO DEL IPS',
                'ALQUILADO' => 'ALQUILADO',
                'OCUPADO' => 'OCUPADO',
                'DISPONIBLE' => 'DISPONIBLE',
                'EN GESTION JUDICIAL' => 'EN GESTION JUDICIAL',
                'REMITIDO A CONSEJO PARA SU NO RENOVACION' => 'REMITIDO A CONSEJO PARA SU NO RENOVACION',
                'OCUPACION IRREGULAR' => 'OCUPACION IRREGULAR',
                'USO INSTITUCIONAL' => 'USO INSTITUCIONAL',
            ],
            null,
            ['class' => 'form-control', 'id' => 'estateStatus', 'style' => 'width:100%'],
        ) }}
    </div>

    <div class="form-group">
        {{ Form::label('committed_investment', 'Inversión Comprometida:', ['class' => 'control-label']) }}
        {{ Form::number('committed_investment', null, ['class' => 'form-control', 'id' => 'committed_investment']) }}
    </div>

    <div class="form-group">
        {{ Form::label('transfer', 'Transferencia:', ['class' => 'control-label']) }}
        {{ Form::number('transfer', null, ['class' => 'form-control', 'id' => 'transfer']) }}
    </div>

    <div class="form-group">
        {{ Form::label('balanceForTransfer', 'Balance por Transferencia:', ['class' => 'control-label']) }}
        {{ Form::number('balanceForTransfer', null, ['class' => 'form-control', 'id' => 'balanceForTransfer']) }}
    </div>

    <div class="form-group">
        {{ Form::label('tenant', 'Arrendatario:', ['class' => 'control-label']) }}
        {{ Form::text('tenant', null, ['class' => 'form-control', 'id' => 'tenant']) }}
    </div>

    <div class="form-group">
        {!! Form::label('rentAmount', 'Precio de Alquiler:') !!}
        {!! Form::number('rentAmount', null, ['class' => 'form-control', 'id' => 'rentAmount', 'style' => 'width:100%']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('registryNumber', 'Registro Número:') !!}
        {!! Form::number('registryNumber', null, [
            'class' => 'form-control',
            'id' => 'registryNumber',
            'style' => 'width:100%',
        ]) !!}
    </div>

    @php
        $years = [];
        $currentYear = date('Y');
        for ($i = 1943; $i <= $currentYear + 10; $i++) {
            $years[$i] = $i;
        }
    @endphp
    <div class="form-group">
        {!! Form::label('rentAmountPeriod', 'Año del Periodo Precio de Alquiler:') !!}
        {!! Form::select('rentAmountPeriod', $years, null, ['class' => 'form-control', 'id' => 'rentAmountPeriod', 'style'=>'width:100%']) !!}
    </div>

    <div class="form-group">
        {{ Form::label('contractResolution', 'Resolución de Contrato:', ['class' => 'control-label']) }}
        {{ Form::text('contractResolution', null, ['class' => 'form-control', 'id' => 'contractResolution']) }}
    </div>

    <div class="form-group">
        {{ Form::label('contractNumber', 'Nro de Contrato:', ['class' => 'control-label']) }}
        {{ Form::text('contractNumber', null, ['class' => 'form-control', 'id' => 'contractNumber']) }}
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
        {{ Form::label('statusDocumentation', 'Estado del Inmueble') }}
        {{ Form::select(
            'statusDocumentation',
            [
                'A' => 'A',
                'TITULO ORIGINAL' => 'TITULO ORIGINAL',
                
            ],
            null,
            ['class' => 'form-control', 'id' => 'statusDocumentation', 'style' => 'width:100%'],
        ) }}
    </div>

    <div class="form-group">
        {{ Form::label('landAreaMt2', 'Dimensiones Terreno en Metro Cuadrado:', ['class' => 'control-label']) }}
        {{ Form::number('landAreaMt2', null, ['class' => 'form-control', 'id' => 'landAreaMt2']) }}
    </div>

    <div class="form-group">
        {{ Form::label('landAreaHectares', 'Dimensiones Terreno Hectárea:', ['class' => 'control-label']) }}
        {{ Form::text('landAreaHectares', null, ['class' => 'form-control', 'id' => 'landAreaHectares']) }}
    </div>

    <div class="form-group">
        {{ Form::label('landSubArea', 'Dimeniones SubArea:', ['class' => 'control-label']) }}
        {{ Form::text('landSubArea', null, ['class' => 'form-control', 'id' => 'landSubArea']) }}
    </div>

    <div class="form-group">
        {{ Form::label('builtAreaM2', 'Área construida (mt2):', ['class' => 'control-label']) }}
        {{ Form::text('builtAreaM2', null, ['class' => 'form-control', 'id' => 'builtAreaM2']) }}
    </div>

    <div class="form-group">
        {{ Form::label('builtValueGs', 'Valor Edificado en Guaranies:', ['class' => 'control-label']) }}
        {{ Form::text('builtValueGs', null, ['class' => 'form-control', 'id' => 'builtValueGs']) }}
    </div>

    <div class="form-group">
        {{ Form::label('propertyValueGs', 'Valor Edificado en Guaranies:', ['class' => 'control-label']) }}
        {{ Form::text('propertyValueGs', null, ['class' => 'form-control', 'id' => 'builtValueGs']) }}
    </div>

    <div class="form-group">
        {{ Form::label('totalValueGs', 'Valor del Propiedad (Guaranies):', ['class' => 'control-label']) }}
        {{ Form::text('totalValueGs', null, ['class' => 'form-control', 'id' => 'totalValueGs']) }}
    </div>

    <div class="form-group">
        {{ Form::label('possessionRentWithoutTitle', 'Posesión Alquiler Sin Título:', ['class' => 'control-label']) }}
        {{ Form::text('possessionRentWithoutTitle', null, ['class' => 'form-control', 'id' => 'possessionRentWithoutTitle']) }}
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
        <button type="submit" class="btn btn-success" id="saveBtnPatrimony" value="create">Guardar
            cambios
        </button>
    </div>
