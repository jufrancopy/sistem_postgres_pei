@extends('layouts.master')

@section('title', 'Configuración de Juntas Consultivas (Consejo de Sabios)')

@section('content')
<div class="content-wrapper p-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="mb-1 text-primary font-weight-bold">
                <i class="fas fa-landmark mr-2"></i> Juntas Consultivas (Consejo de Sabios)
            </h3>
            <p class="text-muted mb-0 small">
                Gestión de Juntas por Programa Institucional (Salud, Jubilaciones, Finanzas) y registro de Firma Hológrafa del Presidente.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.juntas.intervenciones') }}" class="btn btn-warning shadow-sm font-weight-bold">
                <i class="fas fa-inbox mr-1"></i> Ir a Bandeja de Intervención (Alertas)
            </a>
            <button type="button" class="btn btn-primary shadow-sm font-weight-bold" onclick="abrirModalJunta()">
                <i class="fas fa-plus-circle mr-1"></i> Nueva Junta Consultiva
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Cards de Juntas por Programa -->
    <div class="row">
        @foreach($juntas as $j)
            @php
                $colorHeader = 'bg-info';
                $badgeProgram = 'badge-info';
                if ($j->programa === 'salud') { $colorHeader = 'bg-success'; $badgeProgram = 'badge-success'; }
                elseif ($j->programa === 'jubilaciones') { $colorHeader = 'bg-primary'; $badgeProgram = 'badge-primary'; }
                elseif ($j->programa === 'finanzas') { $colorHeader = 'bg-warning text-dark'; $badgeProgram = 'badge-warning'; }
            @endphp
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-lg">
                    <div class="card-header {{ $colorHeader }} text-white font-weight-bold d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users mr-2"></i> {{ $j->nombre }}</span>
                        <span class="badge badge-light text-uppercase">{{ $j->programa }}</span>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">{{ $j->descripcion ?: 'Junta técnica especializada de orientación y mitigación.' }}</p>
                        
                        <div class="p-3 bg-light rounded mb-3 border">
                            <div class="small font-weight-bold text-dark mb-1">
                                <i class="fas fa-user-tie text-primary mr-1"></i> {{ $j->presidente_nombre }}
                            </div>
                            <div class="small text-muted">{{ $j->presidente_cargo }}</div>
                            <div class="mt-2 text-right">
                                <span class="badge badge-secondary">Código: {{ $j->codigo }}</span>
                            </div>
                        </div>

                        <!-- Muestra de Firma Registrada -->
                        <div class="border rounded p-2 text-center bg-white mb-3">
                            <div class="small text-muted font-weight-bold mb-1">Firma Digital Registrada:</div>
                            @if($j->firma_digital_url)
                                <img src="{{ $j->firma_digital_url }}" alt="Firma Presidente" style="max-height: 50px; max-width: 100%; object-fit: contain;">
                            @else
                                <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> Firma no registrada</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-muted"><i class="fas fa-file-alt mr-1"></i> {{ $j->intervenciones_count }} Expedientes</span>
                            <button class="btn btn-sm btn-outline-primary font-weight-bold" onclick='editarJunta(@json($j))'>
                                <i class="fas fa-edit mr-1"></i> Configurar / Firma
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal para Crear/Editar Junta -->
<div class="modal fade" id="modalJunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.juntas.store') }}" method="POST" enctype="multipart/form-data" id="formJunta">
            @csrf
            <input type="hidden" name="id" id="junta_id">
            <input type="hidden" name="firma_digital_base64" id="junta_firma_base64">

            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="modalJuntaTitle"><i class="fas fa-landmark mr-2"></i> Configurar Junta Consultiva</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-bold">Nombre de la Junta <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="junta_nombre" class="form-control" required placeholder="Ej: Junta Consultiva de Salud y Servicios Médicos">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Programa Institucional <span class="text-danger">*</span></label>
                            <select name="programa" id="junta_programa" class="form-control" required>
                                <option value="salud">Salud (Servicios Médicos)</option>
                                <option value="jubilaciones">Jubilaciones y Pensiones</option>
                                <option value="finanzas">Administración y Finanzas</option>
                                <option value="institucional">Institucional / General</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Nombre del Presidente <span class="text-danger">*</span></label>
                            <input type="text" name="presidente_nombre" id="junta_presidente_nombre" class="form-control" required placeholder="Ej: Dr. Juan Carlos Pérez">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Cargo / Título Institucional <span class="text-danger">*</span></label>
                            <input type="text" name="presidente_cargo" id="junta_presidente_cargo" class="form-control" required placeholder="Ej: Presidente de la Junta de Salud">
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Descripción / Competencias de la Junta</label>
                            <textarea name="descripcion" id="junta_descripcion" class="form-control" rows="2" placeholder="Comité consultivo para la evaluación y mitigación de desviaciones operativas..."></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- Registro de Firma Hológrafa -->
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">
                            <i class="fas fa-signature mr-1"></i> Firma Hológrafa Registrada del Presidente (Se estampará automáticamente en los dictámenes)
                        </label>
                        
                        <div class="row">
                            <div class="col-md-7">
                                <div class="border rounded p-2 bg-light text-center">
                                    <small class="text-muted d-block mb-1">Dibujar Firma en Pantalla (Mouse o Stylus):</small>
                                    <canvas id="signaturePad" width="380" height="130" class="border bg-white rounded" style="cursor: crosshair; touch-action: none;"></canvas>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limpiarCanvasFirma()">
                                            <i class="fas fa-eraser"></i> Limpiar Trazo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <small class="text-muted d-block mb-1">O cargar imagen de firma (PNG Transparente):</small>
                                <input type="file" name="firma_digital_file" class="form-control-file border p-2 rounded bg-white" accept="image/*">
                                <div id="previewFirmaExistente" class="mt-2 text-center d-none border p-2 rounded">
                                    <small class="text-muted d-block">Firma Actual Registrada:</small>
                                    <img id="imgFirmaExistente" src="" style="max-height: 60px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="prepararFirmaBase64()">
                        <i class="fas fa-save mr-1"></i> Guardar Junta y Firma
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let canvas = null;
let ctx = null;
let isDrawing = false;

document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('signaturePad');
    if (canvas) {
        ctx = canvas.getContext('2d');
        ctx.strokeStyle = '#000080';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);
    }
});

function startDrawing(e) {
    isDrawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
}

function draw(e) {
    if (!isDrawing) return;
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
}

function stopDrawing() {
    isDrawing = false;
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    if (e.type === 'touchstart') {
        isDrawing = true;
        ctx.beginPath();
        ctx.moveTo(x, y);
    } else if (e.type === 'touchmove' && isDrawing) {
        ctx.lineTo(x, y);
        ctx.stroke();
    }
}

function limpiarCanvasFirma() {
    if (ctx && canvas) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
}

function prepararFirmaBase64() {
    if (canvas) {
        // Verificar si el canvas no está completamente blanco
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        if (canvas.toDataURL() !== blank.toDataURL()) {
            document.getElementById('junta_firma_base64').value = canvas.toDataURL();
        }
    }
}

function abrirModalJunta() {
    document.getElementById('formJunta').reset();
    document.getElementById('junta_id').value = '';
    document.getElementById('junta_firma_base64').value = '';
    document.getElementById('previewFirmaExistente').classList.add('d-none');
    document.getElementById('modalJuntaTitle').innerText = 'Nueva Junta Consultiva';
    limpiarCanvasFirma();
    $('#modalJunta').modal('show');
}

function editarJunta(junta) {
    document.getElementById('junta_id').value = junta.id;
    document.getElementById('junta_nombre').value = junta.nombre;
    document.getElementById('junta_programa').value = junta.programa;
    document.getElementById('junta_presidente_nombre').value = junta.presidente_nombre;
    document.getElementById('junta_presidente_cargo').value = junta.presidente_cargo;
    document.getElementById('junta_descripcion').value = junta.descripcion || '';
    document.getElementById('junta_firma_base64').value = '';

    if (junta.firma_digital_url) {
        document.getElementById('imgFirmaExistente').src = junta.firma_digital_url;
        document.getElementById('previewFirmaExistente').classList.remove('d-none');
    } else {
        document.getElementById('previewFirmaExistente').classList.add('d-none');
    }

    document.getElementById('modalJuntaTitle').innerText = 'Configurar ' + junta.nombre;
    limpiarCanvasFirma();
    $('#modalJunta').modal('show');
}
</script>
@endsection
