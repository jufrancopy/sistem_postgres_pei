@extends('layouts.master')
@section('title', 'Cronogramas')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="material-icons mr-2" style="vertical-align:middle">timeline</i>Cronogramas
        </h4>
        <p class="card-category">Carga y gestión de cronogramas desde JSON</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Cronogramas</li>
        </ol>
    </nav>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card card-body p-4">
                    <h5 class="font-weight-bold mb-3">Importar JSON de cronograma</h5>
                    <form action="{{ route('globales.cronogramas.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="small font-weight-bold">Archivo JSON</label>
                            <input type="file" name="file" class="form-control" accept="application/json,text/plain">
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="fa fa-upload mr-1"></i>Importar cronograma
                        </button>
                    </form>
                    <p class="text-muted small mt-3">Sube el archivo JSON con las dependencias, departamentos y actividades para crear un nuevo cronograma.</p>
                </div>
            </div>
        </div>

        <h5 class="mb-3 font-weight-bold">Cronogramas importados</h5>
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Periodo</th>
                        <th>Schedules</th>
                        <th>Última actualización</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                        <tr>
                            <td>{{ $period->id }}</td>
                            <td>{{ $period->name }}</td>
                            <td>{{ $period->schedules_count }}</td>
                            <td>{{ $period->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('globales.cronogramas.show', $period->id) }}" class="btn btn-sm btn-primary">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay cronogramas importados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
