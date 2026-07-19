@extends('layouts.master')
@section('title', 'Mapeo de Actores')

@section('content')
<div class="card">
    <div class="card-header" style="background:linear-gradient(60deg,#e8f4fd,#d1ecf1);color:#0c5460;">
        <h4 class="card-title"><i class="fa fa-users mr-2"></i>Mapeo de Actores</h4>
        <p class="card-category">{{ strip_tags($profile->name) }}</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles PEI</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.proceso', $profile->id) }}">Proceso</a></li>
            <li class="breadcrumb-item active">Mapeo de Actores</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="mb-3">
            <button class="btn btn-primary btn-sm" id="btnNuevoActor">
                <i class="fa fa-plus mr-1"></i> Agregar Actor
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Tipo</th>
                        <th>Dependencia Institucional</th>
                        <th>Persona Referente</th>
                        <th>Aportes Técnicos</th>
                        <th style="width:100px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actores as $i => $actor)
                    <tr id="fila-{{ $actor->id }}">
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <span class="badge badge-{{ $actor->tipo === 'interno' ? 'info' : 'secondary' }}">
                                {{ ucfirst($actor->tipo) }}
                            </span>
                        </td>
                        <td>{{ $actor->dependencia_label }}</td>
                        <td>
                            {{ $actor->persona_label }}
                            @if($actor->tipo === 'externo' && $actor->email_externo)
                                <br><small class="text-muted">{{ $actor->email_externo }}</small>
                            @endif
                        </td>
                        <td><small>{{ $actor->aportes }}</small></td>
                        <td>
                            <button class="btn btn-warning btn-circle btn-editar"
                                data-id="{{ $actor->id }}"
                                data-tipo="{{ $actor->tipo }}"
                                data-organigrama="{{ $actor->organigrama_id }}"
                                data-user="{{ $actor->user_id }}"
                                data-dependencia="{{ $actor->dependencia_externa }}"
                                data-persona="{{ $actor->persona_referente }}"
                                data-email="{{ $actor->email_externo }}"
                                data-aportes="{{ $actor->aportes }}"
                                data-orden="{{ $actor->orden }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-circle btn-eliminar"
                                data-id="{{ $actor->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr id="filaVacia">
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa fa-users fa-2x mb-2 d-block"></i>
                            No hay actores registrados. Agregue el primero.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('pei-profiles.proceso', $profile->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Proceso
            </a>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalActor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo"><i class="fa fa-user-plus mr-2"></i>Agregar Actor</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="actor_id">

                <div class="form-group">
                    <label class="font-weight-bold">Tipo de Actor</label>
                    <div class="btn-group btn-group-toggle d-block" data-toggle="buttons">
                        <label class="btn btn-outline-info active" id="btnInterno">
                            <input type="radio" name="tipo" value="interno" checked> <i class="fa fa-building mr-1"></i> Interno
                        </label>
                        <label class="btn btn-outline-secondary" id="btnExterno">
                            <input type="radio" name="tipo" value="externo"> <i class="fa fa-globe mr-1"></i> Externo
                        </label>
                    </div>
                </div>

                <div id="camposInternos">
                    <div class="form-group">
                        <label>Dependencia Institucional <span class="text-danger">*</span></label>
                        <select class="form-control" id="organigrama_id">
                            <option value="">— Seleccione —</option>
                            @foreach($organigramas as $org)
                                <option value="{{ $org->id }}">{{ $org->dependency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Persona Referente <span class="text-danger">*</span></label>
                        <select class="form-control" id="user_id">
                            <option value="">— Seleccione —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="camposExternos" style="display:none">
                    <div class="form-group">
                        <label>Institución / Dependencia <span class="text-danger">*</span></label>
                        <select class="form-control" id="institucion_id" style="width:100%">
                            <option value="">— Buscar institución —</option>
                        </select>
                        <small class="text-muted">Si no aparece, escribí el nombre completo abajo</small>
                    </div>
                    <div class="form-group">
                        <label>Nombre alternativo <small class="text-muted">(si no está en la lista)</small></label>
                        <input type="text" class="form-control" id="dependencia_externa" placeholder="Ej: Organización comunitaria XYZ">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label>Persona Referente <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="persona_referente" placeholder="Nombre completo">
                        </div>
                        <div class="form-group col-md-5">
                            <label>Email <small class="text-muted">(opcional)</small></label>
                            <input type="email" class="form-control" id="email_externo" placeholder="correo@ejemplo.com">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Aportes Técnicos</label>
                    <textarea class="form-control" id="aportes" rows="3" placeholder="Describa los aportes técnicos del actor..."></textarea>
                </div>

                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" class="form-control" id="orden" value="0" style="width:100px">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardar">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

@stop

@push('scripts')
<script>
$(function () {
    const STORE_URL = '{{ route('pei-actores.store', $profile->id) }}';
    const DELETE_BASE = '{{ url('pei-profiles/' . $profile->id . '/actores') }}';
    const CSRF = '{{ csrf_token() }}';

    function setTipo(tipo) {
        if (tipo === 'interno') {
            $('input[name=tipo][value=interno]').prop('checked', true);
            $('#btnInterno').addClass('active');
            $('#btnExterno').removeClass('active');
            $('#camposInternos').show();
            $('#camposExternos').hide();
        } else {
            $('input[name=tipo][value=externo]').prop('checked', true);
            $('#btnExterno').addClass('active');
            $('#btnInterno').removeClass('active');
            $('#camposInternos').hide();
            $('#camposExternos').show();
        }
    }

    // Inicializar select2 dentro del modal
    $('#modalActor').on('shown.bs.modal', function () {
        $('#organigrama_id').select2({ dropdownParent: $('#modalActor'), width: '100%' });
        $('#user_id').select2({ dropdownParent: $('#modalActor'), width: '100%' });
        $('#institucion_id').select2({
            dropdownParent: $('#modalActor'),
            width: '100%',
            placeholder: '— Buscar institución —',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('instituciones-paraguay.buscar') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term || '' }; },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            },
            templateResult: function(item) {
                if (!item.id) return item.text;
                var tipos = {
                    ministerio: 'danger', secretaria: 'warning', ente_descentralizado: 'info',
                    empresa_publica: 'dark', organismo_internacional: 'success',
                    gremio: 'secondary', universidad: 'primary', gobierno_local: 'light', poder: 'dark'
                };
                var color = tipos[item.tipo] || 'secondary';
                return $('<span><span class="badge badge-' + color + ' mr-1" style="font-size:.65rem">' + (item.tipo || '') + '</span>' + item.text + '</span>');
            }
        });
    });

    function resetModal() {
        $('#modalTitulo').html('<i class="fa fa-user-plus mr-2"></i>Agregar Actor');
        $('#actor_id').val('');
        $('#organigrama_id').val('').trigger('change');
        $('#user_id').val('').trigger('change');
        $('#institucion_id').val(null).trigger('change');
        $('#dependencia_externa, #persona_referente, #email_externo, #aportes').val('');
        $('#orden').val(0);
        setTipo('interno');
    }

    $('input[name=tipo]').on('change', function () { setTipo(this.value); });

    $('#btnNuevoActor').on('click', function () {
        resetModal();
        $('#modalActor').modal('show');
    });

    $(document).on('click', '.btn-editar', function () {
        const d = $(this).data();
        $('#modalTitulo').html('<i class="fa fa-edit mr-2"></i>Editar Actor');
        $('#actor_id').val(d.id);
        setTipo(d.tipo);
        if (d.tipo === 'interno') {
            $('#organigrama_id').val(d.organigrama).trigger('change');
            $('#user_id').val(d.user).trigger('change');
        } else {
            $('#dependencia_externa').val(d.dependencia);
            $('#persona_referente').val(d.persona);
            $('#email_externo').val(d.email);
        }
        $('#aportes').val(d.aportes);
        $('#orden').val(d.orden);
        $('#modalActor').modal('show');
    });

    $('#btnGuardar').on('click', function () {
        const tipo = $('input[name=tipo]:checked').val();
        const data = {
            _token: CSRF,
            actor_id: $('#actor_id').val(),
            tipo,
            aportes: $('#aportes').val(),
            orden: $('#orden').val(),
        };

        if (tipo === 'interno') {
            data.organigrama_id = $('#organigrama_id').val();
            data.user_id = $('#user_id').val();
            if (!data.organigrama_id || !data.user_id)
                return Swal.fire('Atención', 'Seleccione la dependencia y la persona referente.', 'warning');
        } else {
            data.dependencia_externa = $('#dependencia_externa').val();
            data.institucion_id    = $('#institucion_id').val();
            data.persona_referente = $('#persona_referente').val();
            data.email_externo = $('#email_externo').val();
            if (!data.institucion_id && !data.dependencia_externa)
                return Swal.fire('Atención', 'Seleccioné o escriba la institución.', 'warning');
        }

        $('#btnGuardar').prop('disabled', true);
        $.post(STORE_URL, data)
            .done(() => { $('#modalActor').modal('hide'); location.reload(); })
            .fail(() => Swal.fire('Error', 'No se pudo guardar el actor.', 'error'))
            .always(() => $('#btnGuardar').prop('disabled', false));
    });

    $(document).on('click', '.btn-eliminar', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar actor?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({ url: DELETE_BASE + '/' + id, type: 'DELETE', data: { _token: CSRF } })
                .done(() => $('#fila-' + id).remove())
                .fail(() => Swal.fire('Error', 'No se pudo eliminar.', 'error'));
        });
    });
});
</script>
@endpush
