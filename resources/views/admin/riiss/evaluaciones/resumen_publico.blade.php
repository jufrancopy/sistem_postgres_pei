<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Técnica: {{ $est->nombre_oficial }} — RIISS IPS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background-color: #f1f5f9;
            color: #1e293b;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
        }
        .public-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 14px 24px;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .main-container {
            max-width: 1140px;
            margin: 30px auto;
            padding: 0 15px;
        }
        @media print {
            .public-navbar, .rt-header-actions, .no-print {
                display: none !important;
            }
            body {
                background: #ffffff;
            }
            .main-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            .rt-section-card {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- Topbar pública --}}
    <div class="public-navbar">
        <div class="container d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-white text-decoration-none mr-3">
                    @if(!empty($logoInstitucional))
                        <img src="{{ $logoInstitucional }}" alt="Logo Institucional" style="max-height: 42px; width: auto; max-width: 130px; margin-right: 14px; object-fit: contain; background: #ffffff; padding: 3px 8px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                    @else
                        <span style="width:36px; height:36px; border-radius:8px; background:linear-gradient(135deg, #0284c7, #2563eb); display:grid; place-items:center; font-weight:900; font-size:16px; margin-right:12px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                            GO
                        </span>
                    @endif
                    <div>
                        <div style="font-family:'Outfit', sans-serif; font-weight:800; font-size:16px; line-height:1.2;">SIPLAN <span style="color:#38bdf8;">RIISS</span></div>
                        <small style="font-size:10px; color:#94a3b8; font-weight:600; text-transform: uppercase;">{{ $institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL' }}</small>
                    </div>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <button type="button" class="btn btn-sm btn-outline-light font-weight-bold mr-2" onclick="window.print()" style="border-radius:8px;">
                    <i class="fa fa-print mr-1"></i> Imprimir Ficha
                </button>
                <a href="{{ url('/') }}" class="btn btn-sm btn-light font-weight-bold text-dark" style="border-radius:8px;">
                    <i class="fa fa-home mr-1"></i> Portada Principal
                </a>
            </div>
        </div>
    </div>

    <div class="main-container">
        @include('admin.riiss.evaluaciones.partials.resumen_tecnico_partial')
    </div>

    {{-- Modal Lightbox de Foto Ampliada --}}
    <div class="modal fade" id="modalLightboxFoto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius:18px; overflow:hidden; background:#0f172a;">
                <div class="modal-header border-0 py-3 px-4" style="background:#0f172a; color:#fff;">
                    <h6 class="modal-title font-weight-bold text-white mb-0" id="lightboxTitle">Evidencia Fotográfica</h6>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity:0.8; text-shadow:none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0 text-center" style="background:#020617;">
                    <img id="lightboxImg" src="" alt="Foto" style="max-height:75vh; max-width:100%; object-fit:contain;">
                </div>
                <div class="modal-footer border-0 py-3 px-4 d-flex justify-content-between align-items-center" style="background:#0f172a; color:#fff;">
                    <div id="lightboxDesc" class="text-left text-white small font-weight-600" style="max-width:80%;"></div>
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold px-3" data-dismiss="modal" style="border-radius:8px;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function verFotoLightbox(url, desc, fecha, titulo) {
            $('#lightboxTitle').text(titulo || 'Evidencia Fotográfica');
            $('#lightboxImg').attr('src', url);
            $('#lightboxDesc').html(desc ? `<i class="fa fa-info-circle mr-1 text-info"></i> ${desc}` : '');
            $('#modalLightboxFoto').modal('show');
        }

        function copiarUrlFicha(url) {
            navigator.clipboard.writeText(url).then(function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Enlace copiado al portapapeles 📋',
                    showConfirmButton: false,
                    timer: 2000
                });
            }).catch(function() {
                prompt('Copie el enlace permanente:', url);
            });
        }
    </script>
</body>
</html>
