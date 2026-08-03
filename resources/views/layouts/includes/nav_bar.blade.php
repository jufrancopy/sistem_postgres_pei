<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top" style="box-shadow: none !important;">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand font-weight-bold text-muted d-none d-lg-inline-block" href="javascript:void(0)" style="font-size: .85rem; letter-spacing: .05em; text-transform: uppercase;">
        <i class="fa fa-shield-alt text-info mr-1"></i> SIPLAN IPS
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

        {{-- ── Notificaciones SIESS ── --}}
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="siessNotifBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notificaciones SIESS">
            <i class="material-icons">notifications</i>
            <span id="siessNotifBadge" class="notification bg-danger" style="display:none">0</span>
            <p class="d-lg-none mb-0">Notificaciones</p>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" id="siessNotifMenu" style="width:340px; max-height:420px; overflow-y:auto; padding:0; border-radius:10px;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
              <strong style="font-size:.85rem"><i class="fa fa-bell mr-1 text-primary"></i> Notificaciones SIESS</strong>
              <a href="javascript:void(0)" id="btnLeerTodas" class="text-muted" style="font-size:.75rem">Marcar leídas</a>
            </div>
            <div id="siessNotifLista">
              <div class="text-center text-muted py-3" style="font-size:.8rem">
                <i class="fa fa-spinner fa-spin mr-1"></i> Cargando...
              </div>
            </div>
            <div class="border-top text-center py-2 bg-light">
              <a href="{{ route('siess.extractos.index') }}" class="font-weight-bold" style="font-size:.8rem">Ver extractos</a>
            </div>
          </div>
        </li>

        {{-- ── Puntos de Gamificación (Escritorio) ── --}}
        <li class="nav-item d-none d-lg-flex align-items-center mr-2">
            <a href="{{ route('user.profile') }}" class="badge badge-pill badge-warning py-2 px-3 font-weight-bold text-dark text-decoration-none shadow-sm" style="font-size: 0.8rem;">
                ⭐ {{ number_format($userPts) }} pts
            </a>
        </li>

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
            <a href="#" class="dropdown-item px-3 py-2">
                <i class="fa fa-cog text-secondary mr-2" style="width: 18px;"></i> Configurar
            </a>
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