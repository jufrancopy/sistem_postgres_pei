@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Talentos Humanos</h4>
                    <a class="btn btn-success" href="{{ route('proyectos-epc-tthh.create') }}">Agregar</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('proyectos-epc-dashboard') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Nombre</th>
                                <th>Horas</th>
                                <th>Costo</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tthhs as $key => $tthh)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $tthh->item }}</td>
                                <td>{{ $tthh->hours }}</td>
                                <td>{{ $tthh->cost }}</td>
                                <td>
                                    <a class="btn btn-primary btn-circle"
                                        href="{{ route('proyectos-epc-tthh.edit', $tthh->id) }}">
                                        <i class="far fa-edit"></i>
                                    </a>

                                    <a href="#" onclick="deleteConfirm('{{$tthh->id}}')">
                                        <button class="btn btn-danger btn-circle">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </a>

                                    {!! Form::open([
                                    'route' => ['proyectos-epc-tthh.destroy',$tthh->id],
                                    'method' => 'DELETE',
                                    'id'=> $tthh->id,
                                    'style'=>'display:inline']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            // 'processing': true,
            'serverSide': true,
            'ajax': '{{ url('api/tthh') }}',
            'columns': [
                {data:'id'},
                {data:'item'},
                {data:'type'},
                {data:'cost'},

            ]
        });
    } );

    window.deleteConfirm = function (formId) {
        Swal.fire({
            icon: 'warning',
            text: 'Quieres borrar esto?',
            showCancelButton: true,
            confirmButtonText: 'Borrar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e3342f',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit()
            }
        });
    }
</script>
@endsection