@extends('layouts.master')
@section('content')
<!-- Contenido Principal -->
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header card-header-info">
        <h4 class="card-title ">Estándares por Complejidad</h4>
        <div class="pull-right">
          <a class="btn btn-warning" href="{{ route('home') }}"> Atras</a>
        </div>
      </div>



      <div class="card-body">
        <!-- Inicio Cabecera con iconos -->
        <div class="row">

          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">faces</i>
                </div>
                <p class="card-category">Servicios Médicos Finales</p>
                <h3 class="card-title">{{$totalUsuarios}}</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">search</i>
                  <a href="{{route('users.index')}}">Ver todo</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">account_balance</i>
                </div>
                <p class="card-category">Servicios Médicos de Apoyo</p>
                <h3 class="card-title">{{$totalDependencias}}</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">search</i>
                  <a href="{{route('users.index')}}">Ver todo</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!--Fin de Cabecera con iconos -->
        <p id="tree1"></p>
      </div>
    </div>

  </div>
</div>

@section('scripts')
<script>
  var data = [
        {
            name: 'Talentos Humanos | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-tthh.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
                { name: '<a href="{{route('proyectos-epc-tthh.index')}}">Listar</a>'},
            ]
        },    

        {
            name: 'Equipamientos | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-equipamientos.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
              { name: '<a href="{{route('proyectos-epc', 'all')}}">Listar </a>'},
                { name: '<a href="{{route('proyectos-epc', 'equipo_informatico')}}">Informático</a>'},
                { name: '<a href="{{route('proyectos-epc', 'equipo_biomedico')}}">Biomédicos</a>'},
                { name: '<a href="{{route('proyectos-epc', 'equipo_mobiliario')}}">Mobiliarios</a>'},
                
                
            ]
        },

        {
            name: 'Medicamento y Productos Médicos | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-mds_ins.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
                { name: '<a href="{{route('medicamentos-insumos', 'all')}}">Listar</a>'},
                { name: '<a href="{{route('medicamentos-insumos', 'medicamento')}}">Medicamentos</a>'},
                { name: '<a href="{{route('medicamentos-insumos', 'insumo')}}">Insumos</a>'},
                { name: '<a href="{{route('medicamentos-insumos', 'util_de_laboratorio')}}">Utiles de Laboratorio</a>'},
                { name: '<a href="{{route('medicamentos-insumos', 'material_quirurgico')}}">Materiales Quirúrgicos</a>'},
                { name: '<a href="{{route('medicamentos-insumos', 'producto_quimico')}}">Productos Químicos</a>'},
            ]
        },

        {
            name: 'Apoyo Administrativo | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-ap_admins.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
                { name: '<a href="{{route('apoyo_administrativos', 'all')}}">Listar</a>'},
                { name: '<a href="{{route('apoyo_administrativos', 'servicio_agendamiento')}}">Agendamiento</a>'},
                { name: '<a href="{{route('apoyo_administrativos', 'servicio_archivo_fichero')}}">Archivos - Ficheros</a>'},
                { name: '<a href="{{route('apoyo_administrativos', 'servicio_farmacia')}}">Farmacia</a>'},
            ]
        },

        {
            name: 'Infraestructura | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-infraestructuras.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
                { name: '<a href="{{route('infraestructuras', 'all')}}">Listar</a>'},
                { name: '<a href="{{route('infraestructuras', 'servicio')}}">Servicio</a>'},
                { name: '<a href="{{route('infraestructuras', 'ambulatorio')}}">Ambulatorio</a>'},
                { name: '<a href="{{route('infraestructuras', 'administrativo')}}">Administrativo</a>'},
                { name: '<a href="{{route('infraestructuras', 'hospitalizacion')}}">Hospitalización</a>'},
                { name: '<a href="{{route('infraestructuras', 'quirurgico')}}">Quirúrgico</a>'},
            ]
        },

        {
            name: 'Especialidades | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-especialidades.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
                { name: '<a href="{{route('especialidades', 'all')}}">Listar</a>'},
                { name: '<a href="{{route('especialidades', 'primer_nivel')}}">Primer Nivel</a>'},
                { name: '<a href="{{route('especialidades', 'segundo_nivel')}}">Segundo Nivel</a>'},
                { name: '<a href="{{route('especialidades', 'tercer_nivel')}}">Tercer Nivel</a>'},
            ]
        },

        {
            name: 'Otros Servicios | <a class="btn btn-success btn-circle" href="{{route('proyectos-epc-otros_servs.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>',
            children: [
                { name: '<a href="{{route('otros-servicios', 'all')}}">Listar</a>'},
                { name: '<a href="{{route('otros-servicios', 'limpieza')}}">Limpieza</a>'},
                { name: '<a href="{{route('otros-servicios', 'seguridad')}}">Seguridad</a>'},
                { name: '<a href="{{route('otros-servicios', 'gastronomia')}}">Gastronomía</a>'},
                { name: '<a href="{{route('otros-servicios', 'ambulancia')}}">Ambulancia</a>'},
            ]
        },
        ];

    $('#tree1').tree({
        data: data,
        autoEscape: false,
        saveState: true,
        closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
        openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
        autoOpen: true,
        openFolderDelay: 1000,
        dragAndDrop: true
    });
</script>
@endsection

@endsection