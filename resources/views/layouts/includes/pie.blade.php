<footer class="footer">
  <div class="container-fluid">
    <nav class="float-left">
      <ul>
        <li>
          <a href="#">
            Politicas de Uso
          </a>
        </li>
      </ul>
    </nav>
    <div class="copyright float-right">
      &copy;
      <script>
      document.write(new Date().getFullYear())
      </script>, Hecho con <i class="material-icons">favorite</i> por
      <a href="https://www.facebook.com/jucfra" target="_blank">Jacobito.</a>
    </div>
  </div>
</footer>
</div>
</div>
<!--   Core JS Files   -->
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/tree.jquery.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('master/assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('master/assets/js/core/bootstrap-material-design.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('master/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>

<!-- Chartist JS -->
<script src="{{ asset('master/assets/js/plugins/chartist.min.js') }}" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="{{ asset('master/assets/js/plugins/bootstrap-notify.js') }}" type="text/javascript"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc  -->
<script src="{{ asset('master/assets/js/material-dashboard.min.js?v=2.1.0') }}" type="text/javascript"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('js/cursos.js') }}"></script>
<script src="{{ asset('vendor/bootbox/bootbox.all.js') }}"></script>

<script src="{{ asset('vendor/orgchart/demo/js/jquery.orgchart.js') }}" defer></script>
<script src="{{ asset('js/orgchart.js') }}"></script>



<!-- Scripts personalizados-->
@yield('scripts')
<script>
  $(document).ready(function() {
    $('.js-example-responsive').select2();
});

</script>