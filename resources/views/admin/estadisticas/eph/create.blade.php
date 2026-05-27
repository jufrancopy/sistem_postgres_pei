@extends('layouts.master')
@section('title', 'Cargar Dataset EPH')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-upload mr-2"></i>Cargar Dataset EPH</h4>
        <p class="card-category">Pegá el JSON descargado del INE Paraguay</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.eph.index') }}">EPH</a></li>
            <li class="breadcrumb-item active">Nuevo Dataset</li>
        </ol>
    </nav>

    <div class="card-body">
        <form action="{{ route('siess.eph.store') }}" method="POST" id="formEph" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control"
                               value="{{ old('titulo') }}" required
                               placeholder="Ej: Ingreso Familiar por Departamento 2018">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Categoría <span class="text-danger">*</span></label>
                        <select name="categoria" id="categoria" class="form-control" style="width:100%" required>
                            <option value="">Seleccionar...</option>
                            @foreach($categorias as $key => $label)
                            <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Año <span class="text-danger">*</span></label>
                        <select name="anio" id="anio" class="form-control" style="width:100%" required>
                            <option value="">Seleccionar...</option>
                            @foreach($anios as $a)
                            <option value="{{ $a }}" {{ old('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fuente</label>
                        <input type="text" name="fuente" class="form-control"
                               value="{{ old('fuente', 'INE Paraguay — EPH') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" class="form-control"
                               value="{{ old('descripcion') }}"
                               placeholder="Descripción breve del dataset...">
                    </div>
                </div>
            </div>

            {{-- ── Área de carga ── --}}
            <div class="form-group">
                <label class="font-weight-bold">
                    Datos <span class="text-danger">*</span>
                    <small class="text-muted font-weight-normal ml-2">JSON o CSV del INE Paraguay</small>
                </label>

                {{-- Tabs: Archivo o Pegar texto --}}
                <ul class="nav nav-tabs mb-3" id="tabsCarga">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tabArchivo">
                            <i class="fa fa-upload mr-1"></i> Subir archivo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tabPegar">
                            <i class="fa fa-paste mr-1"></i> Pegar JSON
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Tab 1: Subir archivo --}}
                    <div class="tab-pane fade show active" id="tabArchivo">
                        <div class="border rounded p-4 text-center" id="dropZone"
                             style="border-style:dashed!important;cursor:pointer;background:#f8f9fa">
                            <i class="fa fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                            <div class="font-weight-bold">Arrastrá tu archivo aquí</div>
                            <div class="text-muted mb-2" style="font-size:.85rem">o hacé click para seleccionar</div>
                            <div class="mt-2">
                                <span class="badge badge-light border mr-1">.json</span>
                                <span class="badge badge-light border">.csv</span>
                            </div>
                        </div>
                        {{-- Input fuera del dropZone para evitar loop de eventos --}}
                        <input type="file" name="archivo" id="archivoInput"
                               accept=".json,.csv" style="display:none">
                        <div id="archivoSeleccionado" class="mt-2" style="display:none">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="fa fa-file mr-1"></i>
                                <span id="nombreArchivo"></span>
                                <button type="button" class="btn btn-sm btn-link text-danger float-right p-0" id="btnQuitarArchivo">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Pegar JSON --}}
                    <div class="tab-pane fade" id="tabPegar">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Pegá el contenido JSON directamente:</small>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnFormatear">
                                    <i class="fa fa-indent mr-1"></i> Formatear
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnLimpiar">
                                    <i class="fa fa-times mr-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                        <textarea name="json_raw" id="json_raw" class="form-control font-monospace"
                                  rows="10"
                                  placeholder='[{"campo1": "valor1", "campo2": 123}, ...]'
                                  style="font-size:.8rem">{{ old('json_raw') }}</textarea>
                    </div>
                </div>

                {{-- Preview automático --}}
                <div id="previewArea" style="display:none" class="mt-3">
                    <div class="alert alert-success py-2 mb-2" id="previewInfo"></div>
                    <div class="table-responsive" style="max-height:200px;overflow-y:auto">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light" id="previewHead"></thead>
                            <tbody id="previewBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-right mt-3">
                <a href="{{ route('siess.eph.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-success" id="btnGuardar">
                    <i class="fa fa-save mr-1"></i> Guardar Dataset
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    // ── Select2 ───────────────────────────────────────────────────────────────
    $('#categoria').select2({ placeholder: 'Seleccionar categoría...', allowClear: true, width: '100%' });
    $('#anio').select2({ placeholder: 'Seleccionar año...', allowClear: true, width: '100%' });

    // ── Drag & Drop zona de archivo ───────────────────────────────────────────
    var $dropZone = $('#dropZone');

    $dropZone.on('click', function(e) {
        // Evitar loop: solo abrir el input si el click no viene del input mismo
        if ($(e.target).is('#archivoInput')) return;
        $('#archivoInput').click();
    });

    $dropZone.on('dragover', function(e) {
        e.preventDefault();
        $(this).css('background', '#e3f2fd');
    }).on('dragleave', function() {
        $(this).css('background', '#f8f9fa');
    }).on('drop', function(e) {
        e.preventDefault();
        $(this).css('background', '#f8f9fa');
        var file = e.originalEvent.dataTransfer.files[0];
        if (file) procesarArchivo(file);
    });

    $('#archivoInput').on('change', function() {
        if (this.files[0]) procesarArchivo(this.files[0]);
    });

    $('#btnQuitarArchivo').on('click', function() {
        $('#archivoInput').val('');
        $('#archivoSeleccionado').hide();
        $('#dropZone').show();
        $('#previewArea').hide();
    });

    function procesarArchivo(file) {
        var ext = file.name.split('.').pop().toLowerCase();
        if (!['json','csv'].includes(ext)) {
            toastr.error('Solo se aceptan archivos .json o .csv');
            return;
        }
        $('#nombreArchivo').text(file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)');
        $('#archivoSeleccionado').show();
        $('#dropZone').hide();

        var reader = new FileReader();
        reader.onload = function(e) {
            var contenido = e.target.result;
            if (ext === 'json') {
                try {
                    var datos = JSON.parse(contenido);
                    mostrarPreview(datos);
                } catch(err) {
                    toastr.error('JSON inválido: ' + err.message);
                }
            } else if (ext === 'csv') {
                var datos = parsearCsvJS(contenido);
                mostrarPreview(datos);
            }
        };
        reader.readAsText(file, 'UTF-8');
    }

    // ── Parser CSV en JS (para preview) ──────────────────────────────────────
    function parsearCsvJS(contenido) {
        contenido = contenido.replace(/^\uFEFF/, ''); // quitar BOM
        var lineas = contenido.split(/\r\n|\r|\n/).filter(function(l) { return l.trim(); });
        if (lineas.length < 2) return [];

        var sep = (lineas[0].split(';').length >= lineas[0].split(',').length) ? ';' : ',';
        var cols = lineas[0].split(sep).map(function(c) { return c.trim().replace(/^"|"$/g,''); });

        return lineas.slice(1).map(function(linea) {
            var vals = linea.split(sep).map(function(v) { return v.trim().replace(/^"|"$/g,''); });
            var fila = {};
            cols.forEach(function(c, i) { fila[c] = vals[i] || null; });
            return fila;
        });
    }

    // ── Formatear JSON ────────────────────────────────────────────────────────
    $('#btnFormatear').on('click', function() {
        try {
            var parsed = JSON.parse($('#json_raw').val());
            $('#json_raw').val(JSON.stringify(parsed, null, 2));
            mostrarPreview(parsed);
        } catch(e) { toastr.error('JSON inválido: ' + e.message); }
    });

    $('#btnLimpiar').on('click', function() {
        $('#json_raw').val('');
        $('#previewArea').hide();
    });

    $('#json_raw').on('input paste', function() {
        var val = $(this).val().trim();
        if (val.length > 10) {
            setTimeout(function() {
                try {
                    var datos = JSON.parse($('#json_raw').val());
                    mostrarPreview(datos);
                } catch(e) { $('#previewArea').hide(); }
            }, 400);
        }
    });

    // ── Preview ───────────────────────────────────────────────────────────────
    function mostrarPreview(datos) {
        if (!Array.isArray(datos) || datos.length === 0) { $('#previewArea').hide(); return; }
        var cols   = Object.keys(datos[0]);
        var muestra = datos.slice(0, 5);

        $('#previewInfo').html(
            '<i class="fa fa-check-circle mr-1"></i> <strong>' + datos.length +
            '</strong> registros · <strong>' + cols.length + '</strong> columnas detectadas: ' +
            cols.slice(0,6).map(function(c) { return '<span class="badge badge-light border mr-1">'+c+'</span>'; }).join('') +
            (cols.length > 6 ? '<span class="text-muted">+' + (cols.length-6) + ' más</span>' : '')
        );

        $('#previewHead').html('<tr>' + cols.map(function(c) {
            return '<th style="font-size:.72rem;white-space:nowrap">' + c + '</th>';
        }).join('') + '</tr>');

        $('#previewBody').html(muestra.map(function(fila) {
            return '<tr>' + cols.map(function(c) {
                var v = fila[c] !== null && fila[c] !== undefined ? fila[c] : '—';
                return '<td style="font-size:.72rem">' + String(v).substring(0,25) + '</td>';
            }).join('') + '</tr>';
        }).join(''));

        $('#previewArea').show();
    }

    // ── Validar antes de enviar ───────────────────────────────────────────────
    $('#formEph').on('submit', function(e) {
        var tieneArchivo = $('#archivoInput')[0].files.length > 0;
        var tieneJson    = $('#json_raw').val().trim().length > 0;

        if (!tieneArchivo && !tieneJson) {
            e.preventDefault();
            toastr.error('Debés subir un archivo o pegar el contenido JSON.');
            return;
        }
        if (tieneJson && !tieneArchivo) {
            try { JSON.parse($('#json_raw').val()); }
            catch(err) { e.preventDefault(); toastr.error('JSON inválido: ' + err.message); return; }
        }
        $('#btnGuardar').html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);
    });
});
</script>
@stop
