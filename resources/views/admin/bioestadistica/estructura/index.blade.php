@extends('layouts.master')
@section('title', 'Bioestadística — Departamentos y servicios por establecimiento')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">account_tree</i> Departamentos y servicios por establecimiento</h4>
        <p class="card-category">Asocie los cortes IPS que se podrán elegir al cargar un SP. Un establecimiento puede tener varios pares.</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.geo.create')
        <form method="POST" action="{{ route('bioestadistica.estructura.store') }}" class="form-row bg-light p-3 rounded mb-4">
            @csrf
            <div class="col-md-4">
                <select class="form-control" name="establecimiento_id" required>
                    <option value="">Establecimiento</option>
                    @foreach($establecimientosLista as $item)
                        <option value="{{ $item->id }}">{{ $item->codigo }} — {{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control bio-estructura-departamento" name="departamento_id" data-target="estructura-servicio" required>
                    <option value="">Departamento</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="estructura-servicio" name="servicio_id" required>
                    <option value="">Seleccione departamento</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-success btn-sm">Asociar</button></div>
        </form>
        @endcan

        <form method="GET" class="form-row mb-3">
            <div class="col-md-6"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Buscar establecimiento"></div>
            <div class="col-md-2"><button class="btn btn-primary btn-sm">Filtrar</button></div>
        </form>

        @forelse($establecimientos as $establecimiento)
            <div class="mb-3">
                <h6 class="mb-1">{{ $establecimiento->codigo }} — {{ $establecimiento->nombre }}
                    <small class="text-muted">{{ $establecimiento->distrito?->departamento?->nombre }}</small>
                </h6>
                <ul class="list-group">
                    @forelse($establecimiento->unidades as $unidad)
                        <li class="list-group-item py-2 d-flex justify-content-between align-items-center">
                            <span>{{ $unidad->etiqueta() }}</span>
                            @can('bio.geo.delete')
                                <form method="POST" action="{{ route('bioestadistica.estructura.destroy', $unidad) }}" onsubmit="return confirm('¿Quitar esta asociación?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger p-0" type="submit">Quitar</button>
                                </form>
                            @endcan
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Sin departamento/servicio. En captura no se pedirá este corte.</li>
                    @endforelse
                </ul>
            </div>
        @empty
            <p class="text-muted">Sin establecimientos.</p>
        @endforelse

        {{ $establecimientos->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bio-estructura-departamento').forEach(function (departmentSelect) {
        const serviceSelect = document.getElementById(departmentSelect.dataset.target);
        departmentSelect.addEventListener('change', function () {
            const departmentId = departmentSelect.value;
            serviceSelect.innerHTML = '<option value="">Cargando...</option>';
            if (!departmentId) {
                serviceSelect.innerHTML = '<option value="">Seleccione departamento</option>';
                return;
            }
            fetch('{{ route('bioestadistica.clasificaciones.servicios') }}?departamento_id=' + encodeURIComponent(departmentId), {
                headers: { 'Accept': 'application/json' }
            })
                .then(function (response) { return response.json(); })
                .then(function (payload) {
                    serviceSelect.innerHTML = '<option value="">Servicio</option>';
                    payload.data.forEach(function (service) {
                        const option = document.createElement('option');
                        option.value = service.id;
                        option.textContent = service.nombre;
                        serviceSelect.appendChild(option);
                    });
                });
        });
    });
});
</script>
@endpush
