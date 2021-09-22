@extends('layouts.master')
@section('content')
<!-- Contenido Principal -->
<div class="content">
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-info">
          <h4 class="card-title ">Estándares por Complejidad (E.P.C.)</h4>
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
                  <p class="card-category">Servicios Finales</p>
                  <h3 class='btn btn-danger btn-circle'>{{App\Admin\Proyecto\EPC\Servicio::where('type', 'final')->count()}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="{{route('servicios', 'final')}}">Ver todo</a>
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
                  <a href="{{route('servicios', 'de_apoyo')}}">
                    <p class="card-category">Servicios de Apoyo</p>
                    <h3 class='btn btn-danger btn-circle'>{{App\Admin\Proyecto\EPC\Servicio::where('type', 'de_apoyo')->count()}}</h3>
                  </a>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="{{route('servicios', 'final')}}">Ver todo</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="container-fluid">

            <div class="row">
              <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

                <div class="card" style="background-color:#d5a3f7;">
                  <div class="card-header" style="background-color:#cffc6f;">
                    <h3>Recursos</h3>
                  </div>
                  <p id="tree1" class="p-3 mb-2 text-white"></p>
                </div>

              </div>
              <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="card" style="background-color:#d5a3f7;">

                  <div class="card-header" style="background-color:#cffc6f;">
                    <h3>Generales</h3>
                  </div>
                  <p id="tree2" class="p-3 mb-2 text-white">

                  </p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  @section('scripts')
  <script>
    var column1 = [{
        name: "Talentos Humanos | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-tthh.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
          name: "<a class='text-white' href='{{route('proyectos-epc-tthh.index')}}'>Listar</a>"
        }, ]
      },

      {
        name: "Equipamientos | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-equipamientos.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
            name: "<a class='text-white' href='{{route('equipamientos', 'all')}}'>Listar </a>"
          },
          {
            name: "<a class='text-white'  href='{{route('equipamientos', 'equipo_informatico')}}'>Informático</a>"
          },
          {
            name: "<a class='text-white' href='{{route('equipamientos', 'equipo_biomedico')}}'>Biomédicos</a>"
          },
          {
            name: "<a class='text-white' href='{{route('equipamientos', 'equipo_mobiliario')}}'>Mobiliarios</a>"
          },


        ]
      },

      {
        name: "Medicamento y Productos Médicos | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-mds_ins.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
            name: "<a class='text-white' href='{{route('medicamentos-insumos', 'all')}}'>Listar</a>"
          },
          {
            name: "<a class='text-white' href='{{route('medicamentos-insumos', 'medicamento')}}'>Medicamentos</a>"
          },
          {
            name: "<a class='text-white' href='{{route('medicamentos-insumos', 'insumo')}}'>Insumos</a>"
          },
          {
            name: "<a class='text-white' href='{{route('medicamentos-insumos', 'util_de_laboratorio')}}'>Utiles de Laboratorio</a>"
          },
          {
            name: "<a class='text-white' href='{{route('medicamentos-insumos', 'material_quirurgico')}}'>Materiales Quirúrgicos</a>"
          },
          {
            name: "<a class='text-white' href='{{route('medicamentos-insumos', 'producto_quimico')}}'>Productos Químicos</a>"
          },
        ]
      },

      {
        name: "Infraestructura | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-infraestructuras.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a",
        children: [{
            name: "<a class='text-white' href='{{route('infraestructuras', 'all')}}'>Listar</a>"
          },
          {
            name: "<a class='text-white' href='{{route('infraestructuras', 'servicio')}}'>Servicio</a>"
          },
          {
            name: "<a class='text-white' href='{{route('infraestructuras', 'ambulatorio')}}'>Ambulatorio</a>"
          },
          {
            name: "<a class='text-white' href='{{route('infraestructuras', 'administrativo')}}'>Administrativo</a>"
          },
          {
            name: "<a class='text-white' href='{{route('infraestructuras', 'hospitalizacion')}}'>Hospitalización</a>"
          },
          {
            name: "<a class='text-white' href='{{route('infraestructuras', 'quirurgico')}}'>Quirúrgico</a>"
          },
        ]
      },

      {
        name: "Otros Servicios | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-otros_servs.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
            name: "<a class='text-white' href='{{route('otros-servicios', 'all')}}'>Listar</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'limpieza')}}'>Limpieza</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'seguridad')}}'>Seguridad</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'gastronomia')}}'>Gastronomía</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'ambulancia')}}'>Ambulancia</a>"
          },
        ]
      },

      {
        name: "Turnos | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-otros_servs.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
            name: "<a class='text-white' href='{{route('otros-servicios', 'all')}}'>Listar</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'limpieza')}}'>Limpieza</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'seguridad')}}'>Seguridad</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'gastronomia')}}'>Gastronomía</a>"
          },
          {
            name: "<a class='text-white' href='{{route('otros-servicios', 'ambulancia')}}'>Ambulancia</a>"
          },
        ]
      },

    ];

    var column2 = [{
        name: "Horarios | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-horarios.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
          name: "<a class='text-white' href='{{route('proyectos-epc-horarios.index')}}'>Listar </a>",          
        }, ],
      },

      {
        name: "Turnos | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-turnos.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
          name: "<a class='text-white' href='{{route('proyectos-epc-turnos.index')}}'>Listar</a>"
        }, ]
      },

      {
        name: "Prestaciones | <a class='btn btn-success btn-circle' href='{{route('proyectos-epc-prestaciones.create')}}'><i class='fa fa-plus' aria-hidden='true'></i></a>",
        children: [{
          name: "<a class='text-white' href='{{route('proyectos-epc-prestaciones.index')}}'>Listar</a>"
        }, ]
      },
    ];

    $('#tree1').tree({
      data: column1,
      autoEscape: false,
      saveState: true,
      closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
      openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
      autoOpen: true,
      openFolderDelay: 1000,
      dragAndDrop: true
    });

    $('#tree2').tree({
      data: column2,
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