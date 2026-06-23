@extends('layouts.master')
@section('title', 'Cronograma: ' . $period->name)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="material-icons mr-2" style="vertical-align:middle">timeline</i>Cronograma: {{ $period->name }}
        </h4>
        <p class="card-category">Detalle del cronograma importado</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('globales.cronogramas.index') }}">Cronogramas</a></li>
            <li class="breadcrumb-item active">{{ $period->name }}</li>
        </ol>
    </nav>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="font-weight-bold mb-1">{{ $period->name }}</h5>
                <p class="text-muted mb-0">{{ $period->description ?? 'Sin descripción' }}</p>
            </div>
            <a href="{{ route('globales.cronogramas.index') }}" class="btn btn-secondary">Volver a lista</a>
        </div>

        @foreach($period->schedules as $schedule)
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $schedule->department }}</strong>
                            <div class="text-muted small">{{ $schedule->title }}</div>
                        </div>
                        <span class="badge badge-info">{{ $schedule->items->count() }} items</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Responsable</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedule->items as $item)
                                    <tr>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->responsible?->name ?? 'Sin responsable' }}</td>
                                        <td>{{ $item->start_date?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $item->end_date?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $item->status ?? 'Sin estado' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No hay ítems en este schedule.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        @if($period->schedules->isEmpty())
            <div class="text-center text-muted py-4">Este cronograma no contiene schedules/imports válidos.</div>
        @endif
    </div>
</div>
@endsection
