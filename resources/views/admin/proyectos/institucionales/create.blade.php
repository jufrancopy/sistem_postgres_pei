@extends('layouts.master')
@section('title', 'Nuevo Proyecto Institucional')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-plus mr-2"></i>Nuevo Proyecto Institucional</h4>
        <p class="card-category">El proyecto se creará en estado <strong>Solicitud</strong></p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('proyectos-institucionales.index') }}">Proyectos</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>

    <div class="card-body">
        <form action="{{ route('proyectos-institucionales.store') }}" method="POST">
            @csrf

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre del Proyecto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required
                               placeholder="Ej: Modernización del Sistema de Gestión de Salud">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha estimada de finalización</label>
                        <input type="date" name="fecha_fin_estimada" class="form-control" value="{{ old('fecha_fin_estimada') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Descripción / Justificación</label>
                <textarea name="descripcion" class="form-control" rows="4"
                          placeholder="Describa el proyecto, su justificación y alcance...">{{ old('descripcion') }}</textarea>
            </div>

            {{-- ── Vinculación PEI (obligatoria para avanzar) ── --}}
            <div class="card border-left-success mb-3">
                <div class="card-body py-3">
                    <label class="font-weight-bold text-success">
                        <i class="fa fa-link mr-1"></i>
                        Vinculación al Plan Estratégico Institucional
                    </label>
                    <small class="d-block text-muted mb-2">
                        Seleccioná la Acción del PEI a la que responde este proyecto.
                        Podés dejarlo vacío ahora y vincularlo después, pero
                        <strong>no podrás avanzar del estado Solicitud sin esta vinculación.</strong>
                    </small>
                    <select name="pei_profile_id" id="pei_profile_id" class="form-control" style="width:100%">
                        <option value="">Buscar acción del PEI... (opcional al crear)</option>
                    </select>
                    <div id="peiAviso" class="alert alert-warning mt-2 mb-0 py-2" style="display:none;font-size:.85rem">
                        <i class="fa fa-exclamation-triangle mr-1"></i>
                        Sin vinculación al PEI el proyecto quedará en estado <strong>Solicitud</strong> hasta que se vincule.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Dependencia Solicitante</label>
                        <select id="raiz_solicitante" class="form-control" style="width:100%">
                            <option value="">1. Elegir organigrama raíz...</option>
                        </select>
                        <label class="text-muted mt-2 mb-1" style="font-size:.85rem">Dependencia solicitante:</label>
                        <select name="dependencia_solicitante_id" id="dep_solicitante" class="form-control" style="width:100%" disabled>
                            <option value="">2. Elegir dependencia...</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Dependencia Ejecutora</label>
                        <small class="d-block text-muted mb-1">
                            <i class="fa fa-info-circle mr-1"></i>
                            Se carga automáticamente al elegir el organigrama raíz de la Solicitante.
                        </small>
                        <select id="raiz_ejecutora" class="form-control" style="width:100%" disabled>
                            <option value="">— Igual que la raíz solicitante —</option>
                        </select>
                        <label class="text-muted mt-2 mb-1" style="font-size:.85rem">Dependencia ejecutora:</label>
                        <select name="dependencia_ejecutora_id" id="dep_ejecutora" class="form-control" style="width:100%" disabled>
                            <option value="">2. Elegir dependencia...</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Analista Responsable</label>
                        <select name="analista_id" id="analista_id" class="form-control" style="width:100%">
                            <option value="">Buscar analista...</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Presupuesto Estimado (Gs.)</label>
                        <input type="number" name="presupuesto_estimado" class="form-control"
                               value="{{ old('presupuesto_estimado') }}" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="text-right mt-3">
                <a href="{{ route('proyectos-institucionales.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save mr-1"></i> Crear Proyecto
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    // PEI acciones
    $('#pei_profile_id').select2({
        placeholder: 'Buscar acción del PEI... (opcional al crear)',
        allowClear: true,
        ajax: {
            url: '{{ route('proyectos-institucionales.pei-acciones') }}',
            dataType: 'json', delay: 250,
            processResults: function(data) { return { results: data }; },
            cache: true
        }
    });

    // Mostrar aviso si no se selecciona PEI al intentar guardar
    $('form').on('submit', function() {
        if (!$('#pei_profile_id').val()) {
            $('#peiAviso').show();
        }
    });

    $('#pei_profile_id').on('change', function() {
        if ($(this).val()) {
            $('#peiAviso').hide();
        }
    });

    // ── Selector raíz → hija (reutilizable) ──────────────────────────────────
    function cargarHijos(raizId, raizNombre, $hija, nombreHija) {
        $hija.empty().append('<option value="">2. Elegir ' + nombreHija + '...</option>');
        $hija.prop('disabled', false);
        $hija.select2({
            placeholder: '2. Buscar ' + nombreHija + '...',
            allowClear: true,
            ajax: {
                url: '{{ url('admin/globales/get-dependencies') }}/' + raizId,
                dataType: 'json', delay: 250,
                processResults: function(data) {
                    var results = [{ id: raizId, text: raizNombre + ' (raíz)' }];
                    if (Array.isArray(data)) {
                        data.forEach(function(i) { results.push({ id: i.id, text: i.dependency }); });
                    }
                    return { results: results };
                }, cache: true
            }
        });
    }

    // Inicializar selector de raíz solicitante
    $('#raiz_solicitante').select2({
        placeholder: '1. Elegir organigrama raíz...',
        allowClear: true,
        ajax: {
            url: '{{ route('globales.get-dependencies-root') }}',
            dataType: 'json', delay: 250,
            processResults: function(data) {
                return { results: $.map(data, function(i) { return { id: i.id, text: i.dependency }; }) };
            }, cache: true
        }
    });

    // Al elegir raíz solicitante → sincronizar ejecutora automáticamente
    $('#raiz_solicitante').on('change', function() {
        var raizId     = $(this).val();
        var raizNombre = $(this).find('option:selected').text();

        // Resetear solicitante
        $('#dep_solicitante').empty().append('<option value="">2. Elegir dependencia...</option>').prop('disabled', true);

        // Resetear ejecutora
        $('#raiz_ejecutora').empty().append('<option value="">— Igual que la raíz solicitante —</option>').prop('disabled', true);
        $('#dep_ejecutora').empty().append('<option value="">2. Elegir dependencia...</option>').prop('disabled', true);

        if (!raizId) return;

        // Cargar hijos para solicitante
        cargarHijos(raizId, raizNombre, $('#dep_solicitante'), 'dependencia solicitante');

        // Sincronizar raíz ejecutora con la misma raíz
        var optEjec = new Option(raizNombre, raizId, true, true);
        $('#raiz_ejecutora').empty().append(optEjec).prop('disabled', false);

        // Cargar hijos para ejecutora (misma raíz)
        cargarHijos(raizId, raizNombre, $('#dep_ejecutora'), 'dependencia ejecutora');
    });

    // Analistas
    $('#analista_id').select2({
        placeholder: 'Buscar analista...', allowClear: true,
        ajax: {
            url: '{{ route('globales.get-users') }}',
            dataType: 'json', delay: 250,
            processResults: function(data) {
                return { results: $.map(data, function(i) { return { id: i.id, text: i.name }; }) };
            }, cache: true
        }
    });
});
</script>
@stop
