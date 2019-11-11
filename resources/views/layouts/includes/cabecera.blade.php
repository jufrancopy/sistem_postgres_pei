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

    <!-- JQTree -->
    <link href="http://www.japurahei.com/css/jqtree.css" rel="stylesheet">

    <title>{{ config('app.name', 'PEI') }}</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

    <!-- CSS
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />-->

    <!-- Estilos CSS agregados -->
    <link href="{{ asset('css/fontawesome-all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('master/assets/css/material-dashboard.css?v=2.1.0') }}" rel="stylesheet">
    <link href="{{ asset('master/assets/css/personalizaciones.css') }}" rel="stylesheet">

    <!-- CSS Source -->
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Estilo personalizado en celdas de Datatables -->
    <link href="{{ asset('vendor/orgchart/demo/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/orgchart/demo/css/jquery.orgchart.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/orgchart/demo/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/orgchart/demo/css/orgchart.css') }}" rel="stylesheet">
    
    <!--JQTree -->
    <link href="{{ asset('css/jqtree.js') }}" rel="stylesheet">
     
    <!--JQuery Modal
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script> -->
    <script src="{{ asset('master/assets/js/core/popper.min.js') }}" type="text/javascript"></script>    
    
<style>
    .data-table td:nth-child(3),
    .data-table th:nth-child(3) {
        /* text-align : center; */
        font-weight: bold;
    }

    /* Botones circulares */
    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }
    .btn-circle.btn-lg{
        width: 50px;
        height: 50px;
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 25px;
    }    
    
    .btn-circle.btn-lx{
        width: 570px;
        height: 70px;
        padding: 10px 16px;
        font-size: 24px;
        line-height: 1.33;
        border-radius: 35px;
    }   
</style>

</head>