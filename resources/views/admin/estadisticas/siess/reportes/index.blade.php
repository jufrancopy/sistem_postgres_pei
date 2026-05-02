@extends('layouts.master')
@section('title', 'SIESS — Reportes Gerenciales')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-file-pdf mr-2"></i>Reportes Gerenciales</h4>
        <p class="card-category">Informes ejecutivos a un click — Res. 266/2022</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Reportes</li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="row">

            {{-- ── Informe Gerencial PDF ── --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="fa fa-file-pdf mr-2"></i>Informe Gerencial Consolidado</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Genera un PDF ejecutivo con los KPIs de todos los módulos aprobados para el período seleccionado.
                            Incluye: Trabajadores Activos, Jubilaciones, Ejecución Presupuestaria y alertas.
                        </p>
                        <form action="{{ route('siess.reportes.pdf') }}" method="GET" target="_blank">
                            <div class="form-group">
                                <label class="font-weight-bold">Período <span class="text-danger">*</span></label>
                                <select name="periodo_id" class="form-control" required>
                                    <option value="">Seleccionar período...</option>
                                    @foreach($periodos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fa fa-file-pdf mr-2"></i> Generar PDF Gerencial
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-muted" style="font-size:.8rem">
                        <i class="fa fa-info-circle mr-1"></i>
                        Solo incluye datos con estado <strong>Aprobado</strong> o <strong>Aprobado por Silencio</strong>.
                    </div>
                </div>
            </div>

            {{-- ── Exportación CSV/Excel ── --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fa fa-file-csv mr-2"></i>Exportación CSV para Tableau/BI</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Exporta los datos estructurados de un módulo en formato CSV compatible con Excel y Tableau.
                            Los datos exportados son únicamente los aprobados (Art. 7 Res. 266/2022).
                        </p>
                        <form action="{{ route('siess.reportes.csv') }}" method="GET">
                            <div class="form-group">
                                <label class="font-weight-bold">Módulo <span class="text-danger">*</span></label>
                                <select name="modulo" class="form-control" required>
                                    <option value="">Seleccionar módulo...</option>
                                    <option value="aop_trabajadores">AOP5 — Trabajadores Activos</option>
                                    <option value="ju_beneficiarios">JU1 — Beneficiarios de Jubilaciones</option>
                                    <option value="dcp_presupuesto">DCP1 — Ejecución Presupuestaria</option>
                                    <option value="rl_subsidios">RL1 — Subsidios</option>
                                    <option value="rh_nomina">RH1 — Nómina de Funcionarios</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Período <span class="text-danger">*</span></label>
                                <select name="periodo_id" class="form-control" required>
                                    <option value="">Seleccionar período...</option>
                                    @foreach($periodos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-download mr-2"></i> Exportar CSV
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-muted" style="font-size:.8rem">
                        <i class="fa fa-info-circle mr-1"></i>
                        Formato UTF-8 con BOM, separador punto y coma. Compatible con Excel y Tableau.
                    </div>
                </div>
            </div>

            {{-- ── Accesos rápidos a módulos ── --}}
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-chart-bar mr-1"></i> Acceso Rápido a Módulos</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('siess.modulos.aop') }}" class="btn btn-outline-info btn-block">
                                    <i class="fa fa-users mr-2"></i>
                                    <strong>AOP</strong> — Aportes y Trabajadores
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('siess.modulos.ju') }}" class="btn btn-outline-success btn-block">
                                    <i class="fa fa-hand-holding-usd mr-2"></i>
                                    <strong>JU</strong> — Jubilaciones y Pensiones
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('siess.modulos.dt') }}" class="btn btn-outline-warning btn-block">
                                    <i class="fa fa-balance-scale mr-2"></i>
                                    <strong>DT</strong> — Tesorería y Contabilidad
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop
