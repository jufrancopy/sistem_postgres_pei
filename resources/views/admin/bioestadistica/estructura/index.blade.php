@extends('layouts.master')
@section('title', 'Bioestadística — Departamentos y servicios por establecimiento')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Deptos. y servicios']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">account_tree</i> Departamentos y servicios por establecimiento</h4>
        <p class="card-category">Asocie los cortes IPS que se podrán elegir al cargar un SP. Un establecimiento puede tener varios pares.</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.geo.create')
        <form method="POST" action="{{ route('bioestadistica.estructura.store') }}" class="bio-filters">
            @csrf
            <div class="form-row align-items-end">
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1">Establecimiento</label>
                    <select class="form-control bio-select2" name="establecimiento_id" data-placeholder="Establecimiento" required>
                        <option value="">Establecimiento</option>
                        @foreach($establecimientosLista as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Departamento</label>
                    <select class="form-control bio-select2 bio-estructura-departamento" name="departamento_id" data-target="estructura-servicio" data-placeholder="Departamento" required>
                        <option value="">Departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Servicio</label>
                    <select class="form-control" id="estructura-servicio" name="servicio_id" required>
                        <option value="">Seleccione departamento</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2"><button class="btn btn-success btn-sm btn-block">Asociar</button></div>
            </div>
        </form>
        @endcan

        <form method="GET" class="bio-filters">
            <div class="form-row align-items-end">
                <div class="col-md-8 mb-2">
                    <label class="small text-muted mb-1">Buscar establecimiento</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Nombre o código">
                </div>
                <div class="col-md-4 mb-2"><button class="btn btn-primary btn-sm btn-block">Filtrar</button></div>
            </div>
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
                                <form method="POST" action="{{ route('bioestadistica.estructura.destroy', $unidad) }}" class="bio-confirm-form" data-confirm="¿Quitar esta asociación?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Quitar</button>
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

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bio-estructura-departamento').forEach(function (departmentSelect) {
        const serviceSelect = document.getElementById(departmentSelect.dataset.target);
        const onChange = function () {
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
        };
        if (window.jQuery) {
            window.jQuery(departmentSelect).on('change', onChange);
        } else {
            departmentSelect.addEventListener('change', onChange);
        }
    });
});
</script>
@endsection
