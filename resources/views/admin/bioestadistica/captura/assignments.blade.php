@extends('layouts.master')
@section('title', 'Asignación de digitadores — Bioestadística')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">assignment_ind</i> Alcance de Digitadores</h4>
        <p class="card-category">Cada Digitador solo puede crear y editar registros de los establecimientos asignados.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($users->isEmpty())<div class="alert alert-warning">No hay usuarios con el rol Digitador Bioestadística.</div>@endif
        @foreach($users as $user)
            <form method="POST" action="{{ route('bioestadistica.captura.assignments.update', $user) }}" class="card border mb-3">
                @csrf @method('PUT')
                <div class="card-header bg-light"><strong>{{ $user->name }}</strong> <small>{{ $user->email }}</small></div>
                <div class="card-body">
                    <select class="form-control" name="establecimiento_ids[]" multiple size="10">
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}" @selected(in_array($establecimiento->id, $assignments[$user->id] ?? []))>
                                {{ $establecimiento->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Use Ctrl para seleccionar varios establecimientos.</small>
                    <button class="btn btn-success btn-sm mt-2">Guardar asignaciones</button>
                </div>
            </form>
        @endforeach
    </div>
</div>
@endsection
