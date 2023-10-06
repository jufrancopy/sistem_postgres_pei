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
            <a href="https://www.facebook.com/jucfra" target="_blank">JacEze</a>
        </div>
    </div>
</footer>
</div>
</div>
<!--   Core JS Files   -->
{{-- <script src="{{ asset('js/app.js') }}" type="text/javascript"></script> --}}

{{-- JavaScripts --}}
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/tree.jquery.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('master/assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('master/assets/js/core/bootstrap-material-design.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('master/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>

<!-- Toastr JS -->
<script src="{{ asset('assets/toastr/toastr.min.js') }}"></script>

<!-- Toastr JS -->
<script src="{{ asset('assets/sweetAlert2/sweetalert2.all.min.js') }}"></script>


<!-- Chartist JS -->
<script src="{{ asset('master/assets/js/plugins/chartist.min.js') }}" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="{{ asset('master/assets/js/plugins/bootstrap-notify.js') }}" type="text/javascript"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc  -->
<script src="{{ asset('master/assets/js/material-dashboard.min.js?v=2.1.0') }}" type="text/javascript"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('js/cursos.js') }}"></script>
<script src="{{ asset('assets/bootbox/bootbox.all.js') }}"></script>

<script src="{{ asset('assets/orgchart/demo/js/jquery.orgchart.js') }}" defer></script>
<script src="{{ asset('js/orgchart.js') }}"></script>
<script src="{{ asset('js/config.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script src="{{ asset('js/ckeditor.js') }}"></script>

<!--JQTree -->
{{-- <link href="{{ asset('js/jqtree.js') }}" rel="stylesheet"> --}}


<!-- Scripts personalizados-->
@yield('scripts')
<script>
    $(document).ready(function() {
        $('.js-example-responsive').select2();
    });
</script>
