{{ Form::hidden('parent_id', $idDependencia ?? null) }}

<div class="form-group">
    {{ Form::label('dependency', 'Nombre:') }}
    {{ Form::text('dependency', null, ['class' => 'form-control', 'id' => 'nombre', 'required']) }}
</div>

<div class="form-group">
    {{ Form::label('manager', 'Responsable:') }}
    {{ Form::text('manager', null, ['class' => 'form-control', 'id' => 'manager']) }}
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('phone', 'Teléfono:') }}
            {{ Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('email', 'Correo:') }}
            {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
        </div>
    </div>
</div>

{{-- ── Toggle establecimiento ── --}}
<div class="form-group mt-3">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="esEstablecimiento"
               {{ old('tipo_establecimiento') ? 'checked' : '' }}>
        <label class="custom-control-label font-weight-bold" for="esEstablecimiento">
            <i class="fa fa-hospital mr-1 text-info"></i>
            ¿Es un establecimiento de salud o punto de atención?
        </label>
    </div>
    <small class="text-muted">Activá esta opción para hospitales, centros de salud, puestos sanitarios, etc.</small>
</div>

{{-- ── Campos de establecimiento (ocultos por defecto) ── --}}
<div id="camposEstablecimiento" style="display:{{ old('tipo_establecimiento') ? 'block' : 'none' }}">
    <div class="card card-body bg-light mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-2">
                    {{ Form::label('tipo_establecimiento', 'Tipo:') }}
                    {{ Form::select('tipo_establecimiento', [
                        ''           => 'Seleccionar...',
                        'H.'         => 'H. — Hospital',
                        'H.R.'       => 'H.R. — Hospital Regional',
                        'C.S.'       => 'C.S. — Centro de Salud',
                        'C.P.'       => 'C.P. — Centro de Policlínica',
                        'C.M.I.'     => 'C.M.I. — Centro Materno Infantil',
                        'U.S.'       => 'U.S. — Unidad Sanitaria',
                        'P.S.'       => 'P.S. — Puesto Sanitario',
                        'AOP'        => 'AOP — Aporte Obrero Patronal',
                    ], old('tipo_establecimiento'), ['class' => 'form-control', 'id' => 'tipo_establecimiento']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    {{ Form::label('nivel_complejidad', 'Nivel de complejidad:') }}
                    {{ Form::select('nivel_complejidad', [
                        ''   => 'Seleccionar...',
                        'N1' => 'N1 — Básico',
                        'N2' => 'N2 — Mediano',
                        'N3' => 'N3 — Alto',
                        'N4' => 'N4 — Máxima complejidad',
                    ], old('nivel_complejidad'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    {{ Form::label('tenencia', 'Tenencia:') }}
                    {{ Form::select('tenencia', [
                        ''             => 'Seleccionar...',
                        'PROPIO'       => 'Propio',
                        'CONVENIO'     => 'Convenio',
                        'TERCERIZADO'  => 'Tercerizado',
                    ], old('tenencia'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    {{ Form::label('region', 'Región:') }}
                    {{ Form::select('region', [
                        ''                      => 'Seleccionar...',
                        'ASUNCIÓN Y CENTRAL'    => 'Asunción y Central',
                        'ORIENTAL'              => 'Región Oriental',
                        'OCCIDENTAL'            => 'Región Occidental',
                    ], old('region'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 mt-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="tiene_aop"
                               name="tiene_aop" value="1" {{ old('tiene_aop') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="tiene_aop">
                            <i class="fa fa-coins text-warning mr-1"></i>
                            Tiene boca de atención AOP (Aporte Obrero Patronal)
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group mt-3">
    {{ Form::submit('Guardar', ['class' => 'btn btn-success']) }}
    <a href="javascript:history.back()" class="btn btn-secondary ml-2">Cancelar</a>
</div>

<script>
document.getElementById('esEstablecimiento').addEventListener('change', function() {
    var campos = document.getElementById('camposEstablecimiento');
    campos.style.display = this.checked ? 'block' : 'none';
    if (!this.checked) {
        document.getElementById('tipo_establecimiento').value = '';
    }
});
</script>
