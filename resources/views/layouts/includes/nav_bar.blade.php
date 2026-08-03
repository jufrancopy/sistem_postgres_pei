<nav class="navbar navbar-expand-lg fixed-top shadow-md py-2" style="background: linear-gradient(135deg, #0f172a 0%, #0f766e 60%, #0284c7 100%); border-bottom: 1px solid rgba(255,255,255,0.1); z-index: 1030;">
  <div class="container-fluid px-3">
    
    <!-- Título de Sección y Nombre del Sistema -->
    <div class="navbar-wrapper d-flex align-items-center">
      <span class="badge badge-warning font-weight-bold mr-2 text-dark shadow-sm px-2 py-1" style="font-size: 0.85rem; letter-spacing: 0.8px; background: #ffd700; border-radius: 6px;">SIPLAN</span>
      <h4 class="font-weight-bold text-white mb-0 d-none d-lg-block" style="font-size: 1.05rem; letter-spacing: -0.2px; opacity: 0.95;">
        @yield('title', 'Planificación Estratégica')
      </h4>
    </div>

    <!-- Botón Hamburguesa Móvil -->
    <button class="navbar-toggler border-0 p-2" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar bg-white mb-1"></span>
      <span class="navbar-toggler-icon icon-bar bg-white mb-1"></span>
      <span class="navbar-toggler-icon icon-bar bg-white"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end">
      <form class="navbar-form"></form>
      <ul class="navbar-nav align-items-center" style="gap: 0.6rem;">

        @auth
        @php
            $userPts = app(\App\Services\GamificationService::class)->getUserTotalPoints(Auth::user());
        @endphp

        {{-- ── Notificaciones SIESS ── --}}
        <li class="nav-item dropdown">
          <a class="nav-link p-1 d-flex align-items-center justify-content-center" href="#" id="siessNotifBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notificaciones SIESS">
            <div class="icon-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 38px; height: 38px; border-radius: 50%; background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.25); transition: all 0.2s ease;">
                <i class="material-icons" style="font-size: 1.25rem; color: #ffffff;">notifications</i>
                <span id="siessNotifBadge" class="badge badge-danger badge-pill position-absolute" style="top: 2px; right: 2px; font-size: 0.55rem; display: none;">0</span>
            </div>
            <p class="d-lg-none mb-0 ml-2 font-weight-bold text-white">Notificaciones</p>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 mt-2" id="siessNotifMenu" style="width:350px; max-height:420px; overflow-y:auto; padding:0; border-radius:14px; border: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background: #f8fafc;">
              <strong style="font-size:.85rem; color:#1e293b;"><i class="fa fa-bell mr-1 text-primary"></i> Notificaciones SIESS</strong>
              <a href="javascript:void(0)" id="btnLeerTodas" class="text-muted small" style="font-size:.75rem">Marcar leídas</a>
            </div>
            <div id="siessNotifLista">
              <div class="text-center text-muted py-4" style="font-size:.8rem">
                <i class="fa fa-spinner fa-spin mr-1"></i> Cargando...
              </div>
            </div>
            <div class="border-top text-center py-2 bg-light">
              <a href="{{ route('siess.extractos.index') }}" class="font-weight-bold text-primary" style="font-size:.8rem">Ver todos los extractos</a>
            </div>
          </div>
        </li>

        {{-- ── Puntos de Gamificación (Escritorio) ── --}}
        <li class="nav-item d-none d-lg-flex align-items-center">
            <a href="{{ route('user.profile') }}" class="badge badge-pill py-2 px-3 font-weight-bold text-decoration-none shadow-sm transition-all" title="Tu reputación en el sistema" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #451a03; font-size: 0.82rem; border: 1px solid rgba(255,255,255,0.4); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);">
                ⭐ {{ number_format($userPts) }} pts
            </a>
        </li>

        {{-- ── Menú de Usuario (Cápsula de Lujo Glassmorphism) ── --}}
        <li class="nav-item dropdown">
          <a class="nav-link p-0 d-flex align-items-center" href="#" id="navbarDropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="btn border shadow-sm d-flex align-items-center py-1 px-3" style="border-radius: 50px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.2s ease;">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle border border-white mr-2 shadow-xs" style="width: 28px; height: 28px; object-fit: cover;">
                <span class="font-weight-bold d-none d-lg-inline text-white mr-2" style="font-size: 0.85rem; letter-spacing: 0.2px;">{{ Auth::user()->name ?? 'Usuario' }}</span>
                <i class="fa fa-chevron-down text-white-50 small d-none d-lg-inline" style="font-size: 0.65rem;"></i>
                
                <p class="d-lg-none mb-0 font-weight-bold text-white">
                    {{ Auth::user()->name }} <span class="badge badge-warning ml-1">⭐ {{ number_format($userPts) }} pts</span>
                </p>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 mt-2" aria-labelledby="navbarDropdownUser" style="border-radius: 14px; min-width: 230px; padding: 8px 0; border: 1px solid #e2e8f0;">
            <div class="dropdown-header text-uppercase font-weight-bold text-xs text-muted px-3 py-2 border-bottom mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                {{ Auth::user()->email }}
            </div>
            <a href="{{ route('user.profile') }}" class="dropdown-item px-3 py-2 d-flex align-items-center" style="font-size: 0.85rem; color: #334155;">
                <i class="fa fa-award text-warning mr-2" style="width: 18px;"></i>
                <span>Mi Perfil y Gamificación</span>
            </a>
            <a href="#" class="dropdown-item px-3 py-2 d-flex align-items-center" style="font-size: 0.85rem; color: #334155;">
                <i class="fa fa-cog text-secondary mr-2" style="width: 18px;"></i>
                <span>Configurar</span>
            </a>
            <div class="dropdown-divider my-1"></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item px-3 py-2 d-flex align-items-center text-danger font-weight-bold" style="font-size: 0.85rem;">
                <i class="fa fa-sign-out-alt text-danger mr-2" style="width: 18px;"></i>
                <span>Salir del Sistema</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                {{ csrf_field() }}
            </form>
          </div>
        </li>

        {{-- ── Botón Directo Cerrar Sesión (Móvil) ── --}}
        <li class="nav-item d-lg-none mt-2 border-top pt-2">
          <a class="nav-link text-danger font-weight-bold d-flex align-items-center" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form-mobile-direct').submit();">
            <i class="material-icons text-danger mr-2">exit_to_app</i>
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

<style>
    .navbar .icon-circle:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        transform: translateY(-1px);
    }
    .navbar .btn:hover {
        background: rgba(255, 255, 255, 0.25) !important;
    }
</style>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    var jqInterval = setInterval(function() {
        if (typeof $ === 'undefined') return;
        clearInterval(jqInterval);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

        function cargarNotificaciones() {
            $.ajax({
                url: '{{ route('siess.notificaciones') }}',
                type: 'GET',
                success: function(res) {
                    var badge = $('#siessNotifBadge');
                    if (res.no_leidas > 0) {
                        badge.text(res.no_leidas).show();
                    } else {
                        badge.hide();
                    }

                    var lista = $('#siessNotifLista');
                    if (!res.notificaciones || res.notificaciones.length === 0) {
                        lista.html('<div class="text-center text-muted py-3" style="font-size:.8rem">Sin notificaciones</div>');
                        return;
                    }

                    var html = '';
                    res.notificaciones.forEach(function(n) {
                        html += '<div class="px-3 py-2 border-bottom ' + (n.leida ? '' : 'bg-light') + '" style="cursor:pointer" data-id="' + n.id + '">';
                        html += '<div class="d-flex align-items-start">';
                        html += '<i class="fa ' + n.icono + ' mr-2 mt-1 text-primary" style="font-size:.9rem"></i>';
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

        $('#siessNotifBtn').on('click', function() { cargarNotificaciones(); });

        $(document).on('click', '#siessNotifLista [data-id]', function() {
            var id = $(this).data('id');
            $.post('{{ url('siess/notificaciones') }}/' + id + '/leer', {}, function() {
                cargarNotificaciones();
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
            $.ajax({
                url: '{{ route('siess.notificaciones') }}',
                type: 'GET',
                success: function(res) {
                    var badge = $('#siessNotifBadge');
                    if (res.no_leidas > 0) {
                        badge.text(res.no_leidas).show();
                    } else {
                        badge.hide();
                    }
                },
                error: function() {}
            });
        }, 120000);

    }, 100);
});
</script>
@endauth