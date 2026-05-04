@extends('layouts.master')
@section('title', 'Organigramas Institucionales')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-sitemap mr-2"></i>Organigramas Institucionales</h4>
        <p class="card-category">Gestión de estructuras organizacionales</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Globales</a></li>
            <li class="breadcrumb-item active">Organigramas</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Organigramas raíz</div>
                        <div class="h4 mb-0 font-weight-bold">{{ $dependencias->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total dependencias</div>
                        <div class="h4 mb-0 font-weight-bold">{{ \App\Admin\Globales\Organigrama::count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Con responsable asignado</div>
                        <div class="h4 mb-0 font-weight-bold">{{ \App\Admin\Globales\Organigrama::whereNotNull('user_id')->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body py-2 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nuevo organigrama</div>
                            <div class="text-muted" style="font-size:.8rem">Crear estructura raíz</div>
                        </div>
                        <a href="{{ route('globales.organigramas.create') }}" class="btn btn-primary btn-circle" title="Nuevo Organigrama">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Listado de organigramas ── --}}
        <div class="row">
            @foreach ($dependencias as $dependencia)
            @php
                $totalDesc = $dependencia->descendants()->count();
                $conUser   = $dependencia->descendants()->whereNotNull('user_id')->count() + ($dependencia->user_id ? 1 : 0);
                $esTest    = str_contains(strtolower($dependencia->dependency), '[test]');
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow h-100 {{ $esTest ? 'border border-warning' : '' }}">
                    <div class="card-header d-flex justify-content-between align-items-center
                        {{ $esTest ? 'bg-warning' : 'bg-info' }} text-white py-2">
                        <div class="d-flex align-items-center" style="min-width:0">
                            <i class="fa fa-sitemap mr-2 flex-shrink-0"></i>
                            <span class="font-weight-bold text-truncate" style="font-size:.9rem" title="{{ $dependencia->dependency }}">
                                {{ $dependencia->dependency }}
                            </span>
                        </div>
                        @if($esTest)
                            <span class="badge badge-dark ml-1 flex-shrink-0">TEST</span>
                        @endif
                    </div>

                    <div class="card-body py-3">
                        {{-- Info del responsable --}}
                        <div class="mb-2">
                            @if($dependencia->manager)
                            <div style="font-size:.85rem">
                                <i class="fa fa-user text-muted mr-1"></i>
                                {{ $dependencia->manager }}
                            </div>
                            @endif
                            @if($dependencia->email)
                            <div style="font-size:.8rem" class="text-muted">
                                <i class="fa fa-envelope mr-1"></i>{{ $dependencia->email }}
                            </div>
                            @endif
                        </div>

                        {{-- Métricas --}}
                        <div class="row text-center border-top pt-2 mt-2">
                            <div class="col-6">
                                <div class="font-weight-bold text-info">{{ $totalDesc }}</div>
                                <div class="text-muted" style="font-size:.75rem">Dependencias</div>
                            </div>
                            <div class="col-6">
                                <div class="font-weight-bold {{ $conUser > 0 ? 'text-success' : 'text-danger' }}">{{ $conUser }}</div>
                                <div class="text-muted" style="font-size:.75rem">Con responsable</div>
                            </div>
                        </div>

                        {{-- Hijos directos --}}
                        @if($dependencia->children->isNotEmpty())
                        <div class="mt-2 border-top pt-2">
                            <div class="text-muted mb-1" style="font-size:.75rem text-uppercase">Dependencias directas:</div>
                            @foreach($dependencia->children->take(4) as $hijo)
                            <span class="badge badge-light border mb-1" style="font-size:.75rem">
                                {{ $hijo->dependency }}
                            </span>
                            @endforeach
                            @if($dependencia->children->count() > 4)
                            <span class="badge badge-secondary mb-1">+{{ $dependencia->children->count() - 4 }} más</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="card-footer bg-light py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('globales.organigrama-gestionar', $dependencia->id) }}"
                                   class="btn btn-warning btn-circle" title="Gestionar estructura">
                                    <i class="fa fa-sitemap"></i>
                                </a>
                                <a href="{{ route('globales.organigramas.show', $dependencia->id) }}"
                                   class="btn btn-info btn-circle ml-1" title="Ver organigrama visual">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('globales.organigramas.edit', $dependencia->id) }}"
                                   class="btn btn-primary btn-circle ml-1" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                            <form action="{{ route('globales.organigramas.destroy', $dependencia->id) }}"
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar {{ addslashes($dependencia->dependency) }} y todas sus dependencias?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-circle" title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-2">
            {!! $dependencias->render() !!}
        </div>

    </div>
</div>
@stop
