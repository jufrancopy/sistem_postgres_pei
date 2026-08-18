<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top" style="box-shadow: none !important; background: transparent !important;">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand text-dark font-weight-bold" href="{{ route('home') }}" style="font-size: 0.98rem; letter-spacing: -0.2px;">
        <span>{{ \App\Models\HomeConfiguration::getSetting('site_name', 'SIPLAN') }}</span>
      </a>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end">
      <form class="navbar-form"></form>
      <ul class="navbar-nav">

        @auth
        @php
            $userPts = app(\App\Services\GamificationService::class)->getUserTotalPoints(Auth::user());
        @endphp

        {{-- ── Inspiración Diaria / Código de Ética ── --}}
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" id="btnOpenReflexion" title="Inspiración Diaria & Código de Ética IPS">
            <i class="material-icons text-warning" style="font-size: 22px;">lightbulb</i>
            <p class="d-lg-none mb-0">Reflexión del Día</p>
          </a>
        </li>

        {{-- ── Notificaciones SIESS ── --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="siessNotifBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notificaciones SIESS" style="cursor: pointer;">
            <i class="material-icons" style="pointer-events: none;">notifications</i>
            <span id="siessNotifBadge" class="notification bg-danger" style="display:none; pointer-events: none;">0</span>
            <p class="d-lg-none mb-0" style="pointer-events: none;">Notificaciones</p>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" id="siessNotifMenu" style="width:340px; max-height:420px; overflow-y:auto; padding:0; border-radius:10px;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
              <strong style="font-size:.85rem"><i class="fa fa-bell mr-1 text-primary"></i> Notificaciones</strong>
              <a href="javascript:void(0)" id="btnLeerTodas" class="text-muted" style="font-size:.75rem">Marcar leídas</a>
            </div>
            <div id="siessNotifLista">
              <div class="text-center text-muted py-3" style="font-size:.8rem">
                <i class="fa fa-spinner fa-spin mr-1"></i> Cargando...
              </div>
            </div>
            <div class="border-top text-center py-2 bg-light">
              <a href="{{ route('user.profile') }}" class="font-weight-bold" style="font-size:.8rem">Ver mi perfil</a>
            </div>
          </div>
        </li>

        {{-- ── Puntos de Gamificación (Escritorio) ── --}}
        <li class="nav-item d-none d-lg-flex align-items-center mr-2">
            <a href="{{ route('user.profile') }}" class="badge badge-pill badge-warning py-2 px-3 font-weight-bold text-dark text-decoration-none shadow-sm" style="font-size: 0.8rem;">
                ⭐ {{ number_format($userPts) }} pts
            </a>
        </li>

        {{-- ── Botón Diagnóstico Servidor (Escritorio) ── --}}
        @hasanyrole('Administrador|Super Admin|Coordinador de Planificación|Coordinación de Planificación|Analista PEI|Analista de Planificación')
        <li class="nav-item d-none d-md-flex align-items-center mr-2">
            <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold px-2 py-1 btn-trigger-diagnostico-global" style="border-radius:20px; font-size:11px;" title="Ejecutar Diagnóstico del Servidor y Respaldo DB">
                <i class="fa fa-heartbeat mr-1"></i> Diagnóstico Servidor
            </button>
        </li>
        @endhasanyrole

        {{-- ── Menú de Usuario (Material Pattern) ── --}}
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle border border-light mr-2" style="width: 28px; height: 28px; object-fit: cover;">
            <span class="font-weight-bold d-none d-lg-inline">{{ Auth::user()->name ?? 'Usuario' }}</span>
            <p class="d-lg-none mb-0 font-weight-bold">
                {{ Auth::user()->name }} <span class="badge badge-warning ml-1">⭐ {{ number_format($userPts) }} pts</span>
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" aria-labelledby="navbarDropdownUser" style="border-radius: 10px; min-width: 220px; padding: 6px 0;">
            <div class="dropdown-header text-uppercase font-weight-bold text-xs text-muted px-3 py-2 border-bottom mb-1" style="font-size: 0.7rem;">
                {{ Auth::user()->email }}
            </div>
            <a href="{{ route('user.profile') }}" class="dropdown-item px-3 py-2">
                <i class="fa fa-award text-warning mr-2" style="width: 18px;"></i> Mi Perfil y Gamificación
            </a>
            @hasrole('Administrador')
            <a href="#" class="dropdown-item px-3 py-2 text-primary font-weight-bold" data-toggle="modal" data-target="#modalSimuladorRoles">
                <i class="fa fa-user-secret text-primary mr-2" style="width: 18px;"></i> Ver como otro Usuario / Rol
            </a>
            @endhasrole
            @hasanyrole('Administrador|Super Admin|Coordinador de Planificación|Coordinación de Planificación|Analista PEI|Analista de Planificación')
            <a href="#" class="dropdown-item px-3 py-2 text-danger font-weight-bold btn-trigger-diagnostico-global" onclick="event.preventDefault();">
                <i class="fa fa-heartbeat text-danger mr-2" style="width: 18px;"></i> Respaldo & Diagnóstico DB
            </a>
            @endhasanyrole
            <div class="dropdown-divider my-1"></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item px-3 py-2 text-danger font-weight-bold">
                <i class="fa fa-sign-out-alt text-danger mr-2" style="width: 18px;"></i> Salir del Sistema
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                {{ csrf_field() }}
            </form>
          </div>
        </li>

        {{-- ── Botón Directo Cerrar Sesión (Móvil) ── --}}
        <li class="nav-item d-lg-none mt-2 border-top pt-2">
          <a class="nav-link text-danger font-weight-bold" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form-mobile-direct').submit();">
            <i class="material-icons text-danger">exit_to_app</i>
            <p class="text-danger font-weight-bold mb-0">Cerrar Sesión</p>
          </a>
          <form id="logout-form-mobile-direct" action="{{ route('logout') }}" method="POST" style="display:none">
              {{ csrf_field() }}
          </form>
        </li>
        @endauth

      </ul>
    </div>
  </div>
</nav>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    var jqInterval = setInterval(function() {
        if (typeof $ === 'undefined') return;
        clearInterval(jqInterval);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

        $(document).ajaxError(function(event, xhr, settings) {
            if (xhr.status === 419 || xhr.status === 401) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('Tu sesión ha expirado por inactividad. Redireccionando al inicio de sesión...');
                }
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}";
                }, 1200);
            }
        });

        var ultimasNoLeidasCount = 0;

        function cargarNotificaciones() {
            $.ajax({
                url: '{{ route('siess.notificaciones') }}',
                type: 'GET',
                success: function(res) {
                    var badge = $('#siessNotifBadge');
                    if (res.no_leidas > 0) {
                        if (res.no_leidas > ultimasNoLeidasCount && typeof toastr !== 'undefined') {
                            toastr.info('Tenés nuevas notificaciones en el sistema.', '🔔 Notificación de Asesoría', { timeOut: 6000 });
                        }
                        badge.text(res.no_leidas).show();
                    } else {
                        badge.hide();
                    }
                    ultimasNoLeidasCount = res.no_leidas;

                    var lista = $('#siessNotifLista');
                    if (!res.notificaciones || res.notificaciones.length === 0) {
                        lista.html('<div class="text-center text-muted py-3" style="font-size:.8rem">Sin notificaciones</div>');
                        return;
                    }

                    var html = '';
                    res.notificaciones.forEach(function(n) {
                        var cursor = n.url ? 'cursor:pointer' : '';
                        html += '<div class="px-3 py-2 border-bottom ' + (n.leida ? '' : 'bg-light') + '" style="' + cursor + '" data-id="' + n.id + '" data-url="' + (n.url || '') + '">';
                        html += '<div class="d-flex align-items-start">';
                        html += '<i class="fa ' + (n.icono || 'fa-bell text-info') + ' mr-2 mt-1" style="font-size:.9rem"></i>';
                        html += '<div style="flex:1">';
                        html += '<div style="font-size:.8rem;font-weight:' + (n.leida ? 'normal' : 'bold') + '">' + n.titulo + '</div>';
                        html += '<div style="font-size:.75rem;color:#6c757d">' + n.mensaje + '</div>';
                        html += '<div style="font-size:.7rem;color:#adb5bd;margin-top:2px">' + n.fecha + '</div>';
                        html += '</div></div></div>';
                    });
                    lista.html(html);
                },
                error: function() {
                    $('#siessNotifLista').html('<div class="text-center text-muted py-3" style="font-size:.8rem"><i class="fa fa-exclamation-circle mr-1"></i>No se pudieron cargar</div>');
                }
            });
        }

        $('#siessNotifBtn').closest('.dropdown').on('show.bs.dropdown', function() { cargarNotificaciones(); });
        $(document).on('click', '#siessNotifBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).dropdown('toggle');
        });

        $(document).on('click', '#siessNotifLista [data-id]', function() {
            var id  = $(this).data('id');
            var url = $(this).data('url');
            $.post('{{ url('siess/notificaciones') }}/' + id + '/leer', {}, function() {
                cargarNotificaciones();
                if (url) window.location.href = url;
            });
        });

        $('#btnLeerTodas').on('click', function(e) {
            e.stopPropagation();
            $.post('{{ route('siess.notificaciones.leer-todas') }}', {}, function() {
                cargarNotificaciones();
            });
        });

        cargarNotificaciones();

        setInterval(function() {
            cargarNotificaciones();
        }, 10000);

        // Click Handler para Diagnóstico Servidor en cualquier vista
        $(document).on('click', '.btn-trigger-diagnostico-global', function(e) {
            e.preventDefault();
            $('#diagnosticoGlobalLoading').show();
            $('#diagnosticoGlobalResultado').hide();
            $('#diagnosticoGlobalError').hide();
            $('#modalDiagnosticoGlobal').modal('show');

            $.ajax({
                url: '{{ route('planificacion-dashboard.ejecutar-diagnostico') }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    $('#diagnosticoGlobalLoading').hide();
                    if (res.success) {
                        $('#diagnosticoGlobalOutputText').text(res.output || 'Respaldo de PostgreSQL generado con éxito. Reporte enviado por correo.');
                        $('#diagnosticoGlobalResultado').fadeIn();
                    } else {
                        $('#diagnosticoGlobalErrorMessage').text(res.message);
                        $('#diagnosticoGlobalError').fadeIn();
                    }
                },
                error: function(xhr) {
                    $('#diagnosticoGlobalLoading').hide();
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Ocurrió un error al ejecutar el diagnóstico en el servidor.';
                    $('#diagnosticoGlobalErrorMessage').text(msg);
                    $('#diagnosticoGlobalError').fadeIn();
                }
            });
        });

    }, 100);
});
</script>

<!-- Modal Diagnóstico y Respaldo DB Global -->
<div class="modal fade" id="modalDiagnosticoGlobal" tabindex="-1" role="dialog" aria-labelledby="modalDiagnosticoGlobalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white p-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <h5 class="modal-title font-weight-bold text-white d-flex align-items-center mb-0" id="modalDiagnosticoGlobalTitle">
                    <i class="fa fa-heartbeat text-danger mr-2"></i> Diagnóstico de Salud & Respaldo PostgreSQL
                </h5>
                <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Loading State -->
                <div id="diagnosticoGlobalLoading" class="text-center py-4">
                    <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Procesando...</span>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">Ejecutando Diagnóstico del Servidor (Artisan)</h5>
                    <p class="text-muted small mb-0">Generando copia comprimida de PostgreSQL y enviando reporte a <strong>jucfra23@gmail.com</strong>...</p>
                </div>

                <!-- Results Content -->
                <div id="diagnosticoGlobalResultado" style="display: none;">
                    <div class="alert alert-success d-flex align-items-center border-0 shadow-sm mb-4" style="border-radius: 10px; background: #e6f4ea; color: #137333;">
                        <i class="fa fa-check-circle fa-2x mr-3"></i>
                        <div>
                            <div class="font-weight-bold" style="font-size: 0.95rem;">¡Diagnóstico completado con éxito!</div>
                            <div style="font-size: 0.82rem;">El reporte de salud y respaldo de base de datos fue enviado correctamente a <strong>jucfra23@gmail.com</strong>.</div>
                        </div>
                    </div>

                    <div class="card border-0 bg-light p-3 mb-3" style="border-radius: 10px;">
                        <h6 class="font-weight-bold text-uppercase text-muted small mb-2"><i class="fa fa-terminal mr-1"></i> Resumen de Ejecución Artisan (`php artisan siplan:health-and-backup`)</h6>
                        <pre id="diagnosticoGlobalOutputText" class="mb-0 bg-dark text-success p-3 rounded small" style="max-height: 250px; overflow-y: auto; font-family: monospace; font-size: 0.8rem; border-radius: 8px;"></pre>
                    </div>
                </div>

                <!-- Error State -->
                <div id="diagnosticoGlobalError" style="display: none;">
                    <div class="alert alert-danger d-flex align-items-center border-0 shadow-sm" style="border-radius: 10px;">
                        <i class="fa fa-exclamation-triangle fa-2x mr-3"></i>
                        <div>
                            <div class="font-weight-bold">Error en la ejecución</div>
                            <div id="diagnosticoGlobalErrorMessage" style="font-size: 0.85rem;"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-secondary px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@hasrole('Administrador')
{{-- ── Modal Simulador de Roles / Impersonación ── --}}
<div class="modal fade" id="modalSimuladorRoles" tabindex="-1" role="dialog" aria-labelledby="modalSimuladorRolesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 540px;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #1e293b, #334155);">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalSimuladorRolesLabel" style="font-size: 1.05rem;">
                    <i class="fa fa-user-secret text-warning mr-2"></i> Modo Vista Previa por Rol / Usuario
                </h5>
                <button type="button" class="close text-white opacity-80" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="alert alert-info border-0 shadow-sm mb-3 text-dark small" style="border-radius: 10px; background:#eff6ff;">
                    <i class="fa fa-info-circle text-primary mr-1"></i> Seleccioná cualquier usuario para simular su interfaz, permisos y menús en tiempo real. Podrás volver a tu cuenta de Administrador en cualquier momento con un clic.
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark mb-1">Buscar Usuario por Nombre, Email o Rol</label>
                    <select id="selectUserImpersonate" class="form-control" style="width: 100%;"></select>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-secondary btn-round px-3" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info btn-round px-4 font-weight-bold" id="btnIniciarSimulacion">
                    <i class="fa fa-eye mr-1"></i> Iniciar Vista Previa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkJq = setInterval(function() {
        if (typeof $ === 'undefined') return;
        clearInterval(checkJq);

        $('#modalSimuladorRoles').on('shown.bs.modal', function () {
            if ($('#selectUserImpersonate').data('select2')) return;
            $('#selectUserImpersonate').select2({
                placeholder: 'Escribí un nombre, correo o rol...',
                allowClear: true,
                dropdownParent: $('#modalSimuladorRoles'),
                ajax: {
                    url: "{{ route('impersonate.list-users') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) { return { q: params.term }; },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(u) {
                                return {
                                    id: u.id,
                                    text: u.name + ' — (' + u.roles + ')'
                                };
                            })
                        };
                    }
                }
            });
        });

        $('#btnIniciarSimulacion').click(function() {
            var userId = $('#selectUserImpersonate').val();
            if (!userId) {
                if (typeof toastr !== 'undefined') toastr.warning('Seleccioná un usuario para simular vista.');
                return;
            }
            window.location.href = "{{ url('impersonate/take') }}/" + userId;
        });
    }, 100);
});
</script>
@endhasrole
@endauth