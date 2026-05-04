@extends('layouts.master')
@section('title', 'Editar — ' . $proyecto->codigo)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-edit mr-2"></i>Editar Proyecto — {{ $proyecto->codigo }}</h4>
    </div>
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('proyectos-institucionales.index') }}">Proyectos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('proyectos-institucionales.show', $proyecto->id) }}">{{ $proyecto->codigo }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
    <div class="card-body">
        <form action="{{ route('proyectos-institucionales.update', $proyecto->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ $proyecto->nombre }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Avance (%)</label>
                        <input type="number" name="avance_pct" class="form-control" min="0" max="100"
                               value="{{ $proyecto->avance_pct }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ $proyecto->descripcion }}</textarea>
            </div>

            {{-- Vinculación PEI --}}
            <div class="card border-left-success mb-3">
                <div class="card-body py-3">
                    <label class="font-weight-bold text-success"><i class="fa fa-link mr-1"></i> Vinculación al PEI</label>
                    <select name="pei_profile_id" id="pei_profile_id" class="form-control" style="width:100%">
                        @if($proyecto->peiProfile)
                        <option value="{{ $proyecto->pei_profile_id }}" selected>
                            {{ strip_tags($proyecto->peiProfile->name) }}
                        </option>
                        @else
                        <option value="">Buscar acción del PEI...</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dependencia Solicitante</label>
                        <select id="raiz_solicitante" class="form-control mb-2" style="width:100%">
                            <option value="">1. Elegir organigrama raíz...</option>
                        </select>
                        <select name="dependencia_solicitante_id" id="dep_solicitante" class="form-control" style="width:100%"
                                {{ $proyecto->dependenciaSolicitante ? '' : 'disabled' }}>
                            @if($proyecto->dependenciaSolicitante)
                            <option value="{{ $proyecto->dependencia_solicitante_id }}" selected>
                                {{ $proyecto->dependenciaSolicitante->dependency }}
                            </option>
                            @else
                            <option value="">2. Elegir dependencia...</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dependencia Ejecutora</label>
                        <select id="raiz_ejecutora" class="form-control mb-2" style="width:100%">
                            <option value="">1. Elegir organigrama raíz...</option>
                        </select>
                        <select name="dependencia_ejecutora_id" id="dep_ejecutora" class="form-control" style="width:100%"
                                {{ $proyecto->dependenciaEjecutora ? '' : 'disabled' }}>
                            @if($proyecto->dependenciaEjecutora)
                            <option value="{{ $proyecto->dependencia_ejecutora_id }}" selected>
                                {{ $proyecto->dependenciaEjecutora->dependency }}
                            </option>
                            @else
                            <option value="">2. Elegir dependencia...</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Presupuesto Estimado</label>
                        <input type="number" name="presupuesto_estimado" class="form-control" value="{{ $proyecto->presupuesto_estimado }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Presupuesto Aprobado</label>
                        <input type="number" name="presupuesto_aprobado" class="form-control" value="{{ $proyecto->presupuesto_aprobado }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Presupuesto Ejecutado</label>
                        <input type="number" name="presupuesto_ejecutado" class="form-control" value="{{ $proyecto->presupuesto_ejecutado }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nro. Resolución</label>
                        <input type="text" name="nro_resolucion" class="form-control" value="{{ $proyecto->nro_resolucion }}">
                    </div>
                </div>
            </div>

            <div class="text-right mt-3">
                <a href="{{ route('proyectos-institucionales.show', $proyecto->id) }}" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    $('#pei_profile_id').select2({
        placeholder: 'Buscar acción del PEI...', allowClear: true,
        ajax: { url: '{{ route('proyectos-institucionales.pei-acciones') }}', dataType: 'json', delay: 250,
            processResults: function(d) { return { results: d }; }, cache: true }
    });
    function initDepDosPasos(raizId, hijaId, nombreHija, precargarRaizId, precargarRaizNombre) {
        var $raiz = $(raizId);
        var $hija = $(hijaId);

        $raiz.select2({
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

        // Precargar raíz si viene de edición
        if (precargarRaizId && precargarRaizNombre) {
            var opt = new Option(precargarRaizNombre, precargarRaizId, true, true);
            $raiz.append(opt).trigger('change');
            $hija.prop('disabled', false);
            $hija.select2({
                placeholder: '2. Buscar ' + nombreHija + '...',
                allowClear: true,
                ajax: {
                    url: '{{ url('admin/globales/get-dependencies') }}/' + precargarRaizId,
                    dataType: 'json', delay: 250,
                    processResults: function(data) {
                        var results = [{ id: precargarRaizId, text: precargarRaizNombre + ' (raíz)' }];
                        if (Array.isArray(data)) {
                            data.forEach(function(i) { results.push({ id: i.id, text: i.dependency }); });
                        }
                        return { results: results };
                    }, cache: true
                }
            });
        }

        $raiz.on('change', function() {
            var rId = $(this).val();
            var rNombre = $(this).find('option:selected').text();
            $hija.empty().append('<option value="">2. Elegir ' + nombreHija + '...</option>');
            $hija.prop('disabled', !rId);
            if (!rId) return;
            $hija.select2({
                placeholder: '2. Buscar ' + nombreHija + '...',
                allowClear: true,
                ajax: {
                    url: '{{ url('admin/globales/get-dependencies') }}/' + rId,
                    dataType: 'json', delay: 250,
                    processResults: function(data) {
                        var results = [{ id: rId, text: rNombre + ' (raíz)' }];
                        if (Array.isArray(data)) {
                            data.forEach(function(i) { results.push({ id: i.id, text: i.dependency }); });
                        }
                        return { results: results };
                    }, cache: true
                }
            });
        });
    }

    // Precargar raíces desde el servidor si hay dependencias asignadas
    @if($proyecto->dependenciaSolicitante)
    $.get('{{ url('admin/globales/get-root-of-dependency') }}/{{ $proyecto->dependencia_solicitante_id }}', function(raiz) {
        initDepDosPasos('#raiz_solicitante', '#dep_solicitante', 'dependencia solicitante', raiz.id, raiz.dependency);
    });
    @else
    initDepDosPasos('#raiz_solicitante', '#dep_solicitante', 'dependencia solicitante');
    @endif

    @if($proyecto->dependenciaEjecutora)
    $.get('{{ url('admin/globales/get-root-of-dependency') }}/{{ $proyecto->dependencia_ejecutora_id }}', function(raiz) {
        initDepDosPasos('#raiz_ejecutora', '#dep_ejecutora', 'dependencia ejecutora', raiz.id, raiz.dependency);
    });
    @else
    initDepDosPasos('#raiz_ejecutora', '#dep_ejecutora', 'dependencia ejecutora');
    @endif
});
</script>
@stop
