<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      <form class="navbar-form"></form>
      <ul class="navbar-nav">

        {{-- ── Notificaciones SIESS ── --}}
        @auth
        <li class="nav-item dropdown mr-2">
          <a class="nav-link" href="#" id="siessNotifBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notificaciones SIESS">
            <i class="material-icons">notifications</i>
            <span id="siessNotifBadge" class="badge badge-danger badge-pill"
                  style="position:absolute;top:6px;right:4px;font-size:.6rem;display:none">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow" id="siessNotifMenu"
               style="width:360px;max-height:420px;overflow-y:auto;padding:0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
              <strong style="font-size:.85rem"><i class="fa fa-bell mr-1"></i> Notificaciones SIESS</strong>
              <a href="javascript:void(0)" id="btnLeerTodas" class="text-muted" style="font-size:.75rem">
                Marcar todas como leídas
              </a>
            </div>
            <div id="siessNotifLista">
              <div class="text-center text-muted py-3" style="font-size:.8rem">
                <i class="fa fa-spinner fa-spin mr-1"></i> Cargando...
              </div>
            </div>
            <div class="border-top text-center py-2">
              <a href="{{ route('siess.extractos.index') }}" style="font-size:.8rem">
                Ver todos los extractos
              </a>
            </div>
          </div>
        </li>
        @endauth

        <li class="nav-item">
          @guest
          <a class="nav-link" href="#pablo">
            <i class="material-icons">dashboard</i>
          </a>
          @else
        </li>
        <li class="nav-item dropdown">
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @endguest
          </div>
        </li>

        <!-- Usuario y Gamificación -->
        @auth
        @php
            $userPts = app(\App\Services\GamificationService::class)->getUserTotalPoints(Auth::user());
        @endphp
        <div class="btn-group dropleft align-items-center">
            <a href="{{ route('user.profile') }}" class="badge badge-pill badge-warning py-2 px-3 mr-2 font-weight-bold text-dark text-decoration-none shadow-sm" title="Tu reputación en el sistema">
                ⭐ {{ number_format($userPts) }} pts
            </a>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                {{ Auth::user()->name ?? 'Usuario' }}
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('user.profile') }}" class="dropdown-item">
                    <i class="fa fa-award text-warning mr-1"></i> Mi Perfil y Gamificación
                </a>
                <a href="#" class="dropdown-item">Configurar</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="dropdown-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                        {{ csrf_field() }}
                    </form>
                    Salir
                </a>
            </div>
        </div>
        @endauth

      </ul>
    </div>
  </div>
</nav>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que jQuery esté disponible
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
                        html += '<i class="fa ' + n.icono + ' mr-2 mt-1" style="font-size:.9rem"></i>';
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

        // Carga inicial del badge
        cargarNotificaciones();

        // Polling cada 2 minutos
        setInterval(function() {
            $.ajax({
                url: '{{ route('siess.notificaciones') }}',
                type: 'GET',
                success: function(res) {
                    var badge = $('#siessNotifBadge');
                    res.no_leidas > 0 ? badge.text(res.no_leidas).show() : badge.hide();
                },
                error: function() {}
            });
        }, 120000);

    }, 100); // revisar cada 100ms hasta que jQuery esté disponible
});
</script>
@endauth