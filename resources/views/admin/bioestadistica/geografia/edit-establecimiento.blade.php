@extends('layouts.master')
@section('title', 'Editar establecimiento — Bioestadística')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">edit_location</i> Editar establecimiento</h4>
        <p class="card-category">{{ $establecimiento->codigo }} — {{ $establecimiento->nombre }}</p>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <form method="POST" action="{{ route('bioestadistica.geografia.establecimientos.update', $establecimiento) }}">
            @csrf
            @method('PUT')
            @include('admin.bioestadistica.geografia._establecimiento-form', [
                'formId' => 'editar-establecimiento',
                'submitLabel' => 'Guardar cambios',
            ])
            <a href="{{ route('bioestadistica.geografia.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
