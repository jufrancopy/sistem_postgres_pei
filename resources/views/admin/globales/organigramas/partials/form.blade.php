{{ Form::hidden('parent_id', null) }}
<div class="form-group">
    {{ Form::label('dependency', 'Nombre:') }}
    {{ Form::text('dependency', null, ['class' => 'form-control', 'id' => 'nombre']) }}
</div>

<div class="form-group">
    <label for="user_id_select" class="font-weight-bold">
        Responsable del sistema
        <span class="text-muted font-weight-normal" style="font-size:.8rem">(vincula un usuario del sistema)</span>
    </label>
    <select id="user_id_select" name="user_id" style="width:100%" class="form-control">
        @if(isset($dependencia) && $dependencia->user_id && $dependencia->user)
            <option value="{{ $dependencia->user_id }}" selected>
                {{ $dependencia->user->name }} — {{ $dependencia->user->email }}
            </option>
        @endif
    </select>
    <small class="text-muted">Escribí al menos 2 letras para buscar. Al seleccionar, se completan el nombre y correo.</small>
</div>

<div class="form-group">
    {{ Form::label('manager', 'Nombre del responsable:') }}
    {{ Form::text('manager', null, ['class' => 'form-control', 'id' => 'manager', 'placeholder' => 'Se completa al seleccionar usuario, o escribí manualmente']) }}
</div>

<div class="form-group">
    {{ Form::label('phone', 'Teléfono:') }}
    {{ Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) }}
</div>

<div class="form-group">
    {{ Form::label('email', 'Correo:') }}
    {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
</div>

<div class="form-group">
    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

<script>
$(function() {
    $('#user_id_select').select2({
        placeholder: '— Buscar usuario del sistema —',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ url("admin/globales/usuarios-buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) {
                return { results: data.map(function(u) {
                    return { id: u.id, text: u.name + ' — ' + u.email, name: u.name, email: u.email };
                })};
            }
        },
        templateResult: function(u) {
            if (!u.id) return u.text;
            return $('<span><i class="fa fa-user mr-1 text-muted"></i><strong>' + u.text.split('—')[0] + '</strong><small class="text-muted ml-1">' + (u.text.split('—')[1]||'') + '</small></span>');
        }
    });

    $('#user_id_select').on('select2:select', function(e) {
        var data = e.params.data;
        $('#manager').val(data.name || '');
        $('#email').val(data.email || '');
    });

    $('#user_id_select').on('select2:clear', function() {
        $('#manager').val('');
    });
});
</script>
