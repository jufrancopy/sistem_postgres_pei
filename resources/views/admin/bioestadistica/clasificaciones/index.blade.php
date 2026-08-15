@extends('layouts.master')
@section('title', 'Bioestadística — Clasificaciones')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">category</i> Clasificación de establecimientos</h4>
        <p class="card-category">Microredes, tipos, grados de complejidad y áreas de gestión</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="row">
            @foreach([
                ['microredes', 'Microredes', $microredes, 'nombre'],
                ['tipos-establecimiento', 'Tipos de establecimiento', $tipos, 'nombre'],
                ['areas-gestion', 'Áreas de gestión', $areas, 'nombre'],
            ] as [$slug, $title, $items, $attribute])
                <div class="col-md-4">
                    <h5>{{ $title }}</h5>
                    <form method="POST" action="{{ route('bioestadistica.clasificaciones.store', $slug) }}" class="input-group mb-3">
                        @csrf
                        <input class="form-control" name="nombre" placeholder="Nombre" required>
                        <div class="input-group-append"><button class="btn btn-info btn-sm">+</button></div>
                    </form>
                    <ul class="list-group mb-4">
                        @foreach($items as $item)<li class="list-group-item py-2">{{ $item->{$attribute} }}</li>@endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <h5>Grados de complejidad</h5>
        <form method="POST" action="{{ route('bioestadistica.clasificaciones.store', 'grados-complejidad') }}" class="form-row mb-3">
            @csrf
            <div class="col-md-2"><input class="form-control" name="codigo" placeholder="Grado" required></div>
            <div class="col-md-8"><input class="form-control" name="descripcion" placeholder="Descripción" required></div>
            <div class="col-md-2"><button class="btn btn-info btn-sm">Agregar</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-sm"><thead><tr><th>Código</th><th>Descripción</th></tr></thead>
                <tbody>@foreach($grados as $grado)<tr><td>{{ $grado->codigo }}</td><td>{{ $grado->descripcion }}</td></tr>@endforeach</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
