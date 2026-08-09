<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acta de Reunión MECIP - {{ $acta->numero_acta ?? 'IPS' }}</title>
    
    {{-- Google Fonts & Font Awesome --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --primary-dark: #0f172a;
            --accent: #10b981;
            --surface: #ffffff;
            --bg-page: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .header-institutional {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 2.5rem 1rem 3.5rem;
            position: relative;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.15);
        }

        .main-container {
            max-width: 900px;
            margin: -2rem auto 3rem;
            padding: 0 1rem;
        }

        .card-custom {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .section-header {
            border-left: 4px solid var(--primary-light);
            padding-left: 12px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-dark);
            margin: 0;
        }

        .content-box {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem;
            white-space: pre-wrap;
            font-size: 0.92rem;
            line-height: 1.7;
            color: #334155;
        }

        .form-floating-custom .form-control {
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            padding: 0.65rem 0.9rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-floating-custom .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .btn-register {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-weight: 700;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            border: none;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: all 0.25s ease;
            font-size: 1rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            color: white;
        }

        .participant-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: transform 0.2s ease;
        }

        .participant-item:hover {
            transform: translateX(4px);
            border-color: #cbd5e1;
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4338ca;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .badge-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            .header-institutional {
                padding: 2rem 1rem 3rem;
            }
            .main-container {
                margin-top: -1.5rem;
            }
            .card-body-custom {
                padding: 1.25rem !important;
            }
        }
    </style>
</head>
<body>

    {{-- ══ Encabezado Institucional Superior ══ --}}
    <header class="header-institutional text-center">
        <div class="container" style="max-width: 900px;">
            <div class="d-inline-flex align-items-center mb-2 px-3 py-1 rounded-pill" 
                 style="background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2);">
                <i class="fa fa-shield-alt text-warning mr-2" style="font-size: 0.85rem;"></i>
                <span class="small font-weight-bold tracking-wide" style="font-size: 0.75rem; letter-spacing: 1px;">
                    MECIP:2015 • CONTROL INTERNO INSTITUCIONAL
                </span>
            </div>

            <h5 class="font-weight-bold text-uppercase mb-1" style="letter-spacing: 0.5px; opacity: 0.9; font-size: 1.05rem;">
                {{ $acta->institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL' }}
            </h5>
            
            <p class="small text-white-50 mb-3 mx-auto" style="max-width: 650px; font-size: 0.85rem;">
                {{ $acta->dependencia ?? 'Centro de Enseñanza, Documentación y Estudios de la Seguridad Social - CEDESS' }}
            </p>

            <h2 class="font-weight-bolder mb-2 text-white" style="letter-spacing: -0.5px; font-size: 1.75rem;">
                {{ $acta->numero_acta ?? 'ACTA DE REUNIÓN' }}
            </h2>

            <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 10px;">
                <span class="badge badge-light px-3 py-1 font-weight-bold text-dark" style="border-radius: 20px;">
                    <i class="fa fa-calendar-alt text-primary mr-1"></i>
                    {{ $acta->fecha ? $acta->fecha->format('d/m/Y') : date('d/m/Y') }}
                </span>
                <span class="badge badge-light px-3 py-1 font-weight-bold text-dark" style="border-radius: 20px;">
                    <i class="fa fa-clock text-info mr-1"></i>
                    {{ $acta->hora_desde ?? '15:00' }} a {{ $acta->hora_hasta ?? '16:00' }} hs
                </span>
                <span class="badge {{ $acta->estado === 'finalizada' ? 'badge-success' : 'badge-warning text-dark' }} px-3 py-1 font-weight-bold" style="border-radius: 20px;">
                    <i class="fa {{ $acta->estado === 'finalizada' ? 'fa-check-circle' : 'fa-edit' }} mr-1"></i>
                    {{ $acta->estado === 'finalizada' ? 'Reunión Finalizada' : 'En Curso / Borrador' }}
                </span>
            </div>
        </div>
    </header>

    {{-- ══ Contenedor Principal ══ --}}
    <main class="main-container">

        {{-- ── 1. CARD REGISTRO DE ASISTENCIA / FIRMA RÁPIDA ── --}}
        <div class="card card-custom border-0" id="cardRegistroAsistencia" style="background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%); border: 2px solid #86efac !important;">
            <div class="card-body p-4 card-body-custom">
                
                <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                    <div style="width: 46px; height: 46px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="fa fa-user-check"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bolder text-dark mb-0" style="font-size: 1.25rem; letter-spacing: -0.3px;">
                            Registrar Mi Asistencia y Participación
                        </h4>
                        <p class="text-muted small mb-0">Completá tus datos para quedar registrado oficialmente en el acta de esta reunión.</p>
                    </div>
                </div>

                {{-- Alert de Confirmación / Éxito --}}
                <div id="alertSuccessRegistro" class="alert alert-success p-3 rounded-lg mb-3 shadow-sm" style="display: none; border-left: 5px solid #10b981; border-radius: 12px;">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <i class="fa fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong class="d-block text-success font-weight-bold" style="font-size: 0.95rem;" id="successMsgTitle">¡Asistencia confirmada exitosamente!</strong>
                            <span class="small text-muted" id="successMsgSubtitle">Tus datos han sido registrados en la lista de participantes.</span>
                        </div>
                    </div>
                </div>

                <form id="formPublicRegistro" autocomplete="on">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm form-floating-custom" 
                                   id="pub_nombre" name="nombre" required placeholder="Ingresá tu nombre"
                                   value="{{ Auth::user() ? explode(' ', Auth::user()->name)[0] : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm form-floating-custom" 
                                   id="pub_apellido" name="apellido" required placeholder="Ingresá tu apellido"
                                   value="{{ Auth::user() ? (explode(' ', Auth::user()->name)[1] ?? '') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Correo Electrónico</label>
                            <input type="email" class="form-control form-control-sm form-floating-custom" 
                                   id="pub_correo" name="correo" placeholder="tu.correo@ips.gov.py"
                                   value="{{ Auth::user()?->email ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Teléfono de Contacto</label>
                            <input type="tel" class="form-control form-control-sm form-floating-custom" 
                                   id="pub_telefono" name="telefono" placeholder="Ej: 0981 123456">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Dependencia / Dirección / Servicio</label>
                            <input type="text" class="form-control form-control-sm form-floating-custom" 
                                   id="pub_dependencia" name="dependencia" placeholder="Ej: Dirección de Planificación / CEDESS">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Cargo / Función</label>
                            <input type="text" class="form-control form-control-sm form-floating-custom" 
                                   id="pub_cargo" name="cargo" placeholder="Ej: Analista / Jefe de Departamento / Coordinador">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Firma Digital <span class="text-danger">*</span></label>
                            <div class="border rounded p-2 bg-light text-center" style="border-color: #cbd5e1 !important; touch-action: none;">
                                <canvas id="signature-pad" class="signature-pad w-100 bg-white rounded" style="border: 1px dashed #94a3b8; height: 160px; cursor: crosshair;"></canvas>
                                <div class="d-flex justify-content-between align-items-center mt-2 px-2">
                                    <span class="small text-muted"><i class="fa fa-info-circle mr-1"></i> Dibujá tu firma en el recuadro</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="btnClearSignature" style="font-size: 0.75rem; border-radius: 6px;">Limpiar</button>
                                </div>
                            </div>
                            <div class="mt-2 text-muted" style="font-size: 0.75rem; line-height: 1.3;">
                                <i class="fa fa-shield-alt text-success mr-1"></i>
                                Tu firma será sellada criptográficamente (Hash SHA-256) junto con los datos de la reunión para garantizar que tu participación no pueda ser alterada.
                            </div>
                            <input type="hidden" name="firma" id="firmaInput">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block btn-register mt-2" id="btnSubmitRegistro">
                        <i class="fa fa-check-circle mr-2"></i> Confirmar Mi Asistencia
                    </button>
                </form>

            </div>
        </div>

        {{-- ── 2. CARD LECTURA COMPLETA DEL ACTA MECIP ── --}}
        <div class="card card-custom">
            <div class="card-body p-4 p-md-5 card-body-custom">
                
                {{-- Encabezado interno del acta --}}
                <div class="text-center mb-4 pb-3 border-bottom">
                    <span class="badge badge-secondary px-3 py-1 mb-2 font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        DOCUMENTO OFICIAL MECIP
                    </span>
                    <h3 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -0.3px;">
                        {{ $acta->numero_acta ?? 'ACTA DE REUNIÓN' }}
                    </h3>
                    <p class="text-muted small mb-0 font-weight-medium">
                        {{ $task->title ?? 'Reunión de Coordinación' }}
                    </p>
                </div>

                {{-- Tabla de Metadatos --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0" style="font-size: 0.88rem; border-color: #cbd5e1;">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold bg-light" style="width: 22%; color: #334155;">LUGAR:</td>
                                <td colspan="3" class="font-weight-medium text-dark">{{ $acta->lugar ?? 'REUNIÓN VIRTUAL' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold bg-light" style="color: #334155;">FECHA:</td>
                                <td style="width: 30%;">{{ $acta->fecha ? $acta->fecha->format('d/m/Y') : '—' }}</td>
                                <td class="font-weight-bold bg-light text-center" style="width: 18%; color: #334155;">HORA:</td>
                                <td style="width: 30%;">{{ $acta->hora_desde ?? '15:00' }} a {{ $acta->hora_hasta ?? '16:00' }} hs</td>
                            </tr>
                            @if($acta->convocados_texto)
                            <tr>
                                <td class="font-weight-bold bg-light" style="vertical-align: top; color: #334155;">CONVOCADOS:</td>
                                <td colspan="3" style="white-space: pre-wrap;">{{ $acta->convocados_texto }}</td>
                            </tr>
                            @endif
                            @if($acta->temas_tratar)
                            <tr>
                                <td class="font-weight-bold bg-light" style="vertical-align: top; color: #334155;">TEMAS A TRATAR:</td>
                                <td colspan="3" style="white-space: pre-wrap;">{{ $acta->temas_tratar }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- OBJETIVO --}}
                <div class="mb-4">
                    <div class="section-header">
                        <h6 class="section-title">OBJETIVO</h6>
                    </div>
                    <div class="content-box">
                        {{ $acta->objetivo ?: 'No especificado.' }}
                    </div>
                </div>

                {{-- DESARROLLO --}}
                <div class="mb-4">
                    <div class="section-header">
                        <h6 class="section-title">DESARROLLO (PUNTOS TRATADOS)</h6>
                    </div>
                    <div class="content-box" style="background: #ffffff;">
                        {{ $acta->desarrollo ?: 'En proceso de redacción...' }}
                    </div>
                </div>

                {{-- ACUERDOS --}}
                <div class="mb-4">
                    <div class="section-header">
                        <h6 class="section-title">ACUERDOS</h6>
                    </div>
                    <div class="content-box">
                        {{ $acta->acuerdos ?: 'Sin acuerdos registrados hasta el momento.' }}
                    </div>
                </div>

                {{-- COMPROMISOS --}}
                @php
                    $compromisos = is_array($acta->compromisos) ? $acta->compromisos : (json_decode($acta->compromisos, true) ?? []);
                @endphp
                <div class="mb-4">
                    <div class="section-header">
                        <h6 class="section-title">COMPROMISOS ASUMIDOS</h6>
                    </div>
                    @if(count($compromisos) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" style="font-size: 0.85rem; border-color: #cbd5e1;">
                                <thead class="bg-light text-dark font-weight-bold">
                                    <tr>
                                        <th style="width: 35%;">RESPONSABLE</th>
                                        <th style="width: 65%;">COMPROMISO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($compromisos as $comp)
                                        <tr>
                                            <td class="font-weight-bold text-dark">{{ $comp['responsable'] ?? '—' }}</td>
                                            <td>{{ $comp['compromiso'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="content-box text-muted">
                            No se han cargado compromisos específicos.
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- ── 3. CARD PARTICIPANTES CONFIRMADOS ── --}}
        <div class="card card-custom">
            <div class="card-body p-4 card-body-custom">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="section-header mb-0">
                        <h6 class="section-title">PARTICIPANTES CONFIRMADOS (<span id="countParticipantesLive">{{ $acta->participantes->count() }}</span>)</h6>
                    </div>
                    <span class="badge badge-pill badge-success px-3 py-1 font-weight-bold" style="font-size: 0.75rem;">
                        <i class="fa fa-qrcode mr-1"></i> Asistencia Oficial
                    </span>
                </div>

                <div id="listParticipantesContainer">
                    @forelse($acta->participantes as $part)
                        @php
                            $inits = strtoupper(substr($part->nombre, 0, 1) . substr($part->apellido, 0, 1));
                        @endphp
                        <div class="participant-item">
                            <div class="avatar-circle">{{ $inits }}</div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                                    {{ $part->nombre_completo }}
                                </div>
                                <div class="text-muted small">
                                    @if($part->cargo) <span>{{ $part->cargo }}</span> @endif
                                    @if($part->cargo && $part->dependencia) <span>•</span> @endif
                                    @if($part->dependencia) <span>{{ $part->dependencia }}</span> @endif
                                </div>
                            </div>
                            <div>
                                <span class="badge badge-light border text-success font-weight-bold px-2 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                    <i class="fa fa-check mr-1"></i> Presente
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small" id="noParticipantsMsg">
                            <i class="fa fa-users mb-2 d-block text-muted" style="font-size: 2rem; opacity: 0.4;"></i>
                            Aún no hay participantes registrados. ¡Sé el primero completando el formulario superior!
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Footer informativo --}}
        <footer class="text-center text-muted small py-3">
            <div>{{ $acta->institucion ?? 'Instituto de Previsión Social' }} &copy; {{ date('Y') }}</div>
            <div style="font-size: 0.75rem; opacity: 0.7;">Sistema de Gestión Estratégica y Planificación Institucional</div>
        </footer>

    </main>

    {{-- Scripts JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            var submitUrl = "{{ route('actas.public.registrar', $acta->uuid) }}";
            var yaFirmoServer = {{ (isset($yaFirmo) && $yaFirmo) ? 'true' : 'false' }};
            var storageKey = 'acta_signed_{{ $acta->uuid }}';

            if (yaFirmoServer || localStorage.getItem(storageKey) === 'true') {
                $('#formPublicRegistro').hide();
                $('#formPublicRegistro').prevAll('.d-flex.align-items-center').hide();
                $('#alertSuccessRegistro').show();
                $('#successMsgTitle').text('Tu asistencia ya se encuentra registrada.');
                $('#successMsgSubtitle').text('Ya has firmado esta acta previamente. Abajo puedes ver la lista de participantes confirmados.');
            }

            // Inicializar Signature Pad
            var canvas = document.getElementById('signature-pad');
            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(15, 23, 42)'
            });

            function resizeCanvas() {
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear(); 
            }
            window.onresize = resizeCanvas;
            resizeCanvas();

            $('#btnClearSignature').on('click', function() {
                signaturePad.clear();
            });

            $('#formPublicRegistro').on('submit', function(e) {
                e.preventDefault();
                
                if (signaturePad.isEmpty()) {
                    alert("Por favor, dibuje su firma digital en el recuadro antes de confirmar su asistencia.");
                    return;
                }

                // Guardar firma en input oculto
                $('#firmaInput').val(signaturePad.toDataURL());

                var $btn = $('#btnSubmitRegistro');
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i>Registrando...');

                $.ajax({
                    url: submitUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.success) {
                            localStorage.setItem(storageKey, 'true');
                            
                            $('#formPublicRegistro').slideUp();
                            $('#formPublicRegistro').prevAll('.d-flex.align-items-center').slideUp();
                            $('#alertSuccessRegistro').slideDown();
                            $('#successMsgTitle').text(res.message);
                            
                            $('html, body').animate({
                                scrollTop: $("#alertSuccessRegistro").offset().top - 30
                            }, 500);

                            // Agregar a la lista de participantes en vivo
                            if (res.participante) {
                                var p = res.participante;
                                var inits = p.nombre_completo.split(' ').map(function(n){ return n.charAt(0); }).slice(0,2).join('').toUpperCase();
                                var meta = [];
                                if (p.cargo) meta.push(p.cargo);
                                if (p.dependencia) meta.push(p.dependencia);

                                var itemHtml = '<div class="participant-item" style="border-color: #86efac; background: #f0fdf4;">' +
                                    '<div class="avatar-circle" style="background:#10b981;color:white;">' + inits + '</div>' +
                                    '<div class="flex-grow-1">' +
                                        '<div class="font-weight-bold text-dark mb-0">' + p.nombre_completo + ' <span class="badge badge-success font-weight-normal ml-1" style="font-size:.68rem;">Tú</span></div>' +
                                        '<div class="text-muted small">' + meta.join(' • ') + '</div>' +
                                    '</div>' +
                                    '<div><span class="badge badge-success px-2 py-1" style="font-size:.72rem;"><i class="fa fa-check mr-1"></i>Presente</span></div>' +
                                '</div>';

                                $('#noParticipantsMsg').remove();
                                $('#listParticipantesContainer').prepend(itemHtml);
                                if (res.total_participantes) {
                                    $('#countParticipantesLive').text(res.total_participantes);
                                }
                            }
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html('<i class="fa fa-check-circle mr-2"></i> Confirmar Mi Asistencia');
                        var msg = 'Ocurrió un error al registrar tus datos.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        alert(msg);
                    }
                });
            });
        });
    </script>
</body>
</html>
