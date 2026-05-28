<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/imagenes/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/assets/css/material-dashboard.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/assets/demo/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/overrides.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/jstree/themes/default/style.min.css') }}">

    {{-- Require Google MAPS --}}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.1/purify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    @yield('css')
    @stack('styles')

    {{-- Fix global: Select2 en Material Dashboard móvil --}}
    <style>
    /* Neutralizar pseudo-elementos de BMD que bloquean pointer-events en Select2 */
    .select2-container *,
    .select2-container *::before,
    .select2-container *::after {
        pointer-events: auto !important;
    }

    /* Cuando el dropdown está abierto, desactivar las capas de animación de BMD */
    body.select2-open .bmd-form-group::after,
    body.select2-open .bmd-form-group::before {
        pointer-events: none !important;
    }

    /* El input de búsqueda del dropdown siempre interactivo */
    .select2-search--dropdown,
    .select2-search--dropdown .select2-search__field {
        pointer-events: auto !important;
        position: relative !important;
        z-index: 99999 !important;
        -webkit-user-select: text !important;
        user-select: text !important;
    }

    /* Dropdown siempre encima de todo */
    .select2-dropdown {
        z-index: 99999 !important;
    }

    /* En móvil: asegurar que el contenedor no bloquee */
    @media (max-width: 767px) {
        .select2-container {
            z-index: 99999 !important;
        }
        /* Desactivar animaciones BMD sobre selects cuando Select2 está activo */
        .select2-container--open ~ .bmd-form-group::after,
        .select2-container--open ~ .bmd-form-group::before,
        .bmd-form-group:has(.select2-container--open)::after,
        .bmd-form-group:has(.select2-container--open)::before {
            pointer-events: none !important;
            display: none !important;
        }
        /* El main-panel tiene overflow-x:hidden que bloquea en móvil */
        .main-panel {
            overflow-x: visible !important;
        }
    }
    </style>
</head>
