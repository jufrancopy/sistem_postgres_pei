@extends('layouts.master')

@section('title', 'Control de Cambios & Sincronización MECIP (IPS)')

@section('content')
<div class="content-wrapper p-3 p-md-4 bg-light">
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title text-white font-weight-bold mb-0">
                <i class="fas fa-network-wired mr-2"></i> Control de Cambios & Sincronización MECIP (IPS)
            </h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Control de Cambios MECIP</li>
            </ol>
        </nav>

        {{-- Tarjetas de Estadísticas --}}
        @php
            $totalCasos = $casos->count();
            $pendientesLider = $casos->where('estado_flujo', 'remitido_lider')->count();
            $resueltosLider = $casos->where('estado_flujo', 'resuelto_lider')->count();
            $cerradosAdmin = $casos->where('estado_flujo', 'cerrado_admin')->count();
        @endphp

        <div class="row px-3 mb-3">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #94a3b8 !important;">Total Expedientes</span>
                            <h2 class="font-weight-bold mb-0 text-warning" style="font-size: 1.8rem; color: #fbbf24 !important;">{{ number_format($totalCasos) }}</h2>
                        </div>
                        <i class="fas fa-folder-open fa-2x" style="color: #64748b !important;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #b45309 0%, #92400e 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #fde047 !important;">En Revisión Líder MECIP</span>
                            <h2 class="font-weight-bold mb-0 text-white" style="font-size: 1.8rem; color: #ffffff !important;">{{ number_format($pendientesLider) }}</h2>
                        </div>
                        <i class="fas fa-user-clock fa-2x" style="color: #facc15 !important;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #7dd3fc !important;">Resueltos con Justificación</span>
                            <h2 class="font-weight-bold mb-0 text-white" style="font-size: 1.8rem; color: #ffffff !important;">{{ number_format($resueltosLider) }}</h2>
                        </div>
                        <i class="fas fa-check-double fa-2x" style="color: #38bdf8 !important;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #15803d 0%, #166534 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #86efac !important;">Aprobados y Cerrados</span>
                            <h2 class="font-weight-bold mb-0 text-white" style="font-size: 1.8rem; color: #ffffff !important;">{{ number_format($cerradosAdmin) }}</h2>
                        </div>
                        <i class="fas fa-file-signature fa-2x" style="color: #4ade80 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row px-3">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between py-3 border-bottom" style="gap: 12px;">
                        <form action="{{ route('admin.mecip.control.index') }}" method="GET" class="form-inline flex-wrap" style="gap: 12px;">
                            <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                                <label class="font-weight-bold mr-2 mb-0 text-dark small"><i class="fa fa-filter text-info mr-1"></i> Estado:</label>
                                <select name="estado" id="selectEstadoFiltro" class="form-control form-control-sm select2" style="width: 200px;" onchange="this.form.submit()">
                                    <option value="TODOS" {{ $estado === 'TODOS' ? 'selected' : '' }}>-- Todos los Estados --</option>
                                    <option value="borrador" {{ $estado === 'borrador' ? 'selected' : '' }}>Borrador / Carga Inicial</option>
                                    <option value="remitido_lider" {{ $estado === 'remitido_lider' ? 'selected' : '' }}>Remitido a Líder MECIP</option>
                                    <option value="resuelto_lider" {{ $estado === 'resuelto_lider' ? 'selected' : '' }}>Resuelto con Justificación</option>
                                    <option value="cerrado_admin" {{ $estado === 'cerrado_admin' ? 'selected' : '' }}>Aprobado y Cerrado</option>
                                </select>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <button type="button" class="btn btn-dark text-white font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalSyncBpmScraper">
                                <i class="fas fa-network-wired text-info mr-1"></i> Sincronizar BPM IPS (Scraping)
                            </button>
                            <button type="button" class="btn btn-warning text-dark font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalBotonReplicadorIps">
                                <i class="fas fa-bolt mr-1"></i> Botón Replicador IPS (1-Clic)
                            </button>
                            <button type="button" class="btn btn-outline-info font-weight-bold" data-toggle="modal" data-target="#modalImportarJsonMecip">
                                <i class="fas fa-file-import mr-1"></i> Importar Caso JSON
                            </button>
                            <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalNuevoCasoMecip">
                                <i class="fas fa-plus-circle mr-1"></i> Capturar Nuevo Caso IPS
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover data-table text-dark" id="tblCasosMecip" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 130px;" class="text-center">Nº Caso / Código</th>
                                        <th>Macroproceso & Subproceso</th>
                                        <th style="width: 70px;" class="text-center">Versión</th>
                                        <th style="width: 170px;">Líder MECIP</th>
                                        <th style="width: 160px;" class="text-center">Estado Flujo</th>
                                        <th style="width: 120px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($casos as $c)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-dark font-mono font-weight-bold px-2 py-1 mb-1 d-block" style="font-family: monospace; font-size: 0.82rem;">
                                                    Caso #{{ $c->numero_caso }}
                                                </span>
                                                <span class="badge badge-light border text-primary font-weight-bold px-2 py-0.5" style="font-size: 0.72rem;">
                                                    {{ $c->codigo_subproceso }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <strong class="text-dark d-block" style="font-size: 0.92rem; line-height: 1.3;">{{ $c->subproceso }}</strong>
                                                <small class="text-muted d-block" style="font-size: 0.78rem;">
                                                    <i class="fas fa-sitemap text-info mr-1"></i> {{ $c->macroproceso }} &bull; {{ $c->proceso }}
                                                </small>
                                            </td>
                                            <td class="align-middle text-center font-weight-bold text-dark">
                                                v{{ $c->version }}
                                            </td>
                                            <td class="align-middle">
                                                @if($c->liderMecip)
                                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                                        <div class="rounded-circle bg-info text-white font-weight-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                            {{ strtoupper(substr($c->liderMecip->name, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <strong class="text-dark d-block small" style="line-height: 1.2;">{{ $c->liderMecip->name }}</strong>
                                                            <small class="text-muted" style="font-size: 0.7rem;">Líder Asignado</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted font-italic small"><i class="fas fa-exclamation-circle text-warning mr-1"></i> Sin Líder Asignado</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge {{ $c->estado_badge }} font-weight-bold px-2.5 py-1" style="font-size: 0.78rem;">
                                                    {{ $c->estado_label }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('admin.mecip.control.show', $c->id) }}" class="btn btn-sm btn-info font-weight-bold px-3 shadow-sm" style="border-radius: 8px;" title="Ver Expediente y Diagrama de Nodos">
                                                    <i class="fas fa-eye mr-1"></i> Expediente
                                                </a>
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
    </div>
</div>

{{-- Modal Capturar Nuevo Caso MECIP --}}
<div class="modal fade" id="modalNuevoCasoMecip" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.mecip.control.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-primary text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fas fa-plus-circle mr-2"></i> Capturar Nuevo Caso MECIP IPS
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small"><i class="fas fa-hashtag text-info mr-1"></i> Nº de Caso IPS (*)</label>
                        <input type="text" name="numero_caso" class="form-control form-control-sm font-weight-bold text-dark" placeholder="Ej. 3782431" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small"><i class="fas fa-code text-info mr-1"></i> Código de Subproceso (*)</label>
                        <input type="text" name="codigo_subproceso" class="form-control form-control-sm font-weight-bold text-dark" placeholder="Ej. GES_002_01" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold text-dark small">Macroproceso (*)</label>
                        <input type="text" name="macroproceso" class="form-control form-control-sm text-dark" placeholder="Ej. GESTIÓN ESTRATÉGICA E INSTITUCIONAL" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold text-dark small">Proceso (*)</label>
                        <input type="text" name="proceso" class="form-control form-control-sm text-dark" placeholder="Ej. Gestión de Calidad y Procesos" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold text-dark small">Subproceso (*)</label>
                        <input type="text" name="subproceso" class="form-control form-control-sm text-dark" placeholder="Ej. Modelado de Procedimientos" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small">Versión (*)</label>
                        <input type="text" name="version" class="form-control form-control-sm text-dark" value="1.0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small">Fecha Elaboración</label>
                        <input type="date" name="fecha_elaboracion" class="form-control form-control-sm text-dark" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="font-weight-bold text-dark small"><i class="fas fa-user-shield text-info mr-1"></i> Asignar Líder MECIP (* Select2)</label>
                        <select name="lider_mecip_id" id="selectLiderMecipModal" class="form-control select2" style="width: 100%;">
                            <option value="">-- Asignar Líder MECIP para revisión --</option>
                            @foreach($lideresMecip as $lider)
                                <option value="{{ $lider->id }}">{{ $lider->name }} ({{ $lider->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-save mr-1"></i> Registrar Caso MECIP
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Importar JSON Estructurado --}}
<div class="modal fade" id="modalImportarJsonMecip" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formImportarJsonMecip" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-info text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fas fa-file-import mr-2"></i> Importación Rápida Estructurada JSON (IPS)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-2">
                    Pegá aquí la estructura copiada o parseada del expediente IPS para replicar automáticamente las actividades, tareas, insumos y productos en el sistema.
                </p>
                <textarea name="json_data" id="json_data_input" class="form-control font-mono" rows="10" placeholder='{
  "numero_caso": "3782431",
  "codigo_subproceso": "GES_002_01",
  "macroproceso": "GESTIÓN INSTITUCIONAL",
  "proceso": "Gestión de Procesos",
  "subproceso": "Modelado de Procedimientos",
  "actividades": [
    { "codigo": "ACT_01", "nombre": "Recepción del caso en bandeja", "responsable": "Analista IPS", "tareas": [ { "descripcion": "Validar metadatos", "tiempo_minutos": 15 } ] }
  ]
}' style="font-family: monospace; font-size: 0.82rem; background: #0f172a; color: #38bdf8;" required></textarea>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="ejecutarImportacionJson()" class="btn btn-info font-weight-bold px-4 shadow-sm" id="btnEjecutarImportacion">
                    <i class="fas fa-magic mr-1"></i> Replicar Expediente
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Botón Marcador Replicador de Pantalla IPS (1-Clic) --}}
<div class="modal fade" id="modalBotonReplicadorIps" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            <div class="modal-header bg-warning text-dark p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-bolt mr-2"></i> Botón Replicador de Pantalla IPS (1-Clic)
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-dark">
                <div class="alert alert-info border-0 shadow-xs mb-3" style="border-left: 5px solid #0284c7 !important; background: #e0f2fe; color: #0369a1;">
                    <h6 class="font-weight-bold mb-1"><i class="fas fa-magic mr-1"></i> ¿Cómo funciona el Botón Replicador de Pantalla?</h6>
                    <p class="mb-0 small" style="line-height: 1.4;">
                        Podés guardar este botón especial en tu <strong>Barra de Marcadores/Favoritos</strong> del navegador. Cuando estés logueado en la web del IPS viendo el expediente/tabla del caso, hacés <strong>un solo clic en tu marcador</strong> y el sistema extraerá la pantalla y la guardará automáticamente en tu SIPLAN.
                    </p>
                </div>

                <div class="p-4 bg-light rounded border text-center my-3" style="border-radius: 12px !important;">
                    <h6 class="font-weight-bold text-muted uppercase small mb-2">Instrucciones: Arrastrá este botón a tu barra de Marcadores de Chrome/Edge:</h6>
                    
                    @php
                        $importJsonUrl = route('admin.mecip.control.importarJson');
                        $targetRedirectUrl = route('admin.mecip.control.index');
                        $bookmarkletJs = "javascript:(function(){var win=window.open('about:blank','_blank');if(win){win.document.write('<h3>⚡ Replicando expediente IPS a SIPLAN...</h3>');}function cleanTxt(s){if(!s)return'';s=s.trim();if(s.includes('Replicar')||s.includes('SIPLAN')||s.includes('Marcador')||s.includes('Mostrando')||s.includes('Acciones')||s.includes('Volver al graficador')||s.includes('Despliegue'))return'';return s;}function getCellTxt(td){if(!td)return'';var divs=td.querySelectorAll('div');if(divs&&divs.length>1){var arr=[];divs.forEach(function(d){var t=cleanTxt(d.innerText);if(t)arr.push(t);});return arr.join('\\n');}return cleanTxt(td.innerText);}try{var url=window.location.href;var pageText=document.body.innerText||'';var casoMatch=url.match(/(?:m_process_id|searchclient_process_id)=(\\d+)/i)||pageText.match(/(?:caso|expediente|nº|no\\.)\\s*[:#]?\\s*(\\d{5,10})/i)||[0,'3782431'];var numeroCaso=casoMatch[1]||'3782431';var macroproceso='PROCESO DE GOBERNANZA IPS';var proceso='GESTIÓN ESTRATÉGICA E INSTITUCIONAL';var subproceso='Modelado y Gestión de Procesos MECIP IPS';var codigoSubproceso='GES-BPM-'+numeroCaso;var version='1.0';var macroMatch=pageText.match(/Macrop?oroceso:\\s*([^\\n\\r\\t<]+)/i);if(macroMatch&&cleanTxt(macroMatch[1]))macroproceso=cleanTxt(macroMatch[1]);var procMatch=pageText.match(/Proceso:\\s*([^\\n\\r\\t<]+)/i);if(procMatch&&cleanTxt(procMatch[1])&&cleanTxt(procMatch[1])!=='Nº Caso / Código')proceso=cleanTxt(procMatch[1]);var subMatch=pageText.match(/Subproceso(?:\\/Procedimiento)?:\\s*([^\\n\\r\\t<]+)/i)||pageText.match(/(?:Subproceso|Asunto|Procedimiento):\\s*([^\\n\\r\\t<]+)/i);if(subMatch&&cleanTxt(subMatch[1])&&cleanTxt(subMatch[1])!=='Nº Caso / Código')subproceso=cleanTxt(subMatch[1]);var verMatch=pageText.match(/Versi[oó]n:\\s*([^\\n\\r\\t<]+)/i);if(verMatch&&cleanTxt(verMatch[1]))version=cleanTxt(verMatch[1]);var productos=[];var insumos=[];var actividades=[];var tareasMap={};if(window.angular){var allNodes=document.querySelectorAll('cy-vista-formatos, [data-ng-controller], .ng-scope, [ng-repeat]');for(var i=0;i<allNodes.length;i++){try{var ngEl=window.angular.element(allNodes[i]);var s=(ngEl.isolateScope&&ngEl.isolateScope())||(ngEl.scope&&ngEl.scope());var c=(ngEl.controller&&ngEl.controller())||(s&&s['\x24ctrl'])||s;if(c&&c.formatos&&Array.isArray(c.formatos)&&c.formatos.length>0){c.formatos.forEach(function(fr){if(fr.numero==46&&fr.datos_formato){fr.datos_formato.forEach(function(fp){var car=Array.isArray(fp.caracteristicas)?fp.caracteristicas.map(function(item){return typeof item==='object'?(item.descripcion||item.nombre||''):item;}).join('\\n'):(fp.caracteristicas||'');productos.push({codigo:fp.codigo||'',nombre:fp.producto||fp.nombre||fp.codigo||'Producto IPS',descripcion:car,cliente:fp.cliente||fp.clientes||'Consejo de Administración'});});}if(fr.numero==47&&fr.datos_formato){fr.datos_formato.forEach(function(fp){var car=Array.isArray(fp.caracteristicas)?fp.caracteristicas.map(function(item){return typeof item==='object'?(item.descripcion||item.nombre||''):item;}).join('\\n'):(fp.caracteristicas||'');insumos.push({codigo:fp.codigo||'',nombre:fp.insumo||fp.nombre||fp.codigo||'Insumo IPS',descripcion:car,proveedor:fp.proveedor||fp.proveedores||'Todas las direcciones'});});}if(fr.numero==48&&fr.datos_formato){fr.datos_formato.forEach(function(fp){actividades.push({codigo:fp.codigo||'',nombre:fp.actividad||fp.nombre||fp.codigo,objetivo:fp.objetivo||'',responsable:fp.cargo||fp.responsable||'Analista IPS',tareas:[]});});}});break;}}catch(errNode){}}}if(productos.length===0||insumos.length===0||actividades.length===0){var tables=document.querySelectorAll('table');tables.forEach(function(tbl){var txt=(tbl.innerText||'').toLowerCase();var rows=tbl.querySelectorAll('tbody tr');if(!rows.length)rows=tbl.querySelectorAll('tr');if(productos.length===0&&(txt.includes('producto')||txt.includes('cliente')||txt.includes('46'))){rows.forEach(function(r){var tds=r.querySelectorAll('td');if(tds.length>=2){var cod=cleanTxt(tds[0]?tds[0].innerText:'');var nom=cleanTxt(tds[1]?tds[1].innerText:'');if(nom&&nom!=='Volver al graficador'&&!nom.includes('graficador')){productos.push({codigo:cod,nombre:nom,descripcion:getCellTxt(tds[2]),cliente:cleanTxt(tds[3]?tds[3].innerText:'')||'Consejo de Administración'});}}});}if(insumos.length===0&&(txt.includes('insumo')||txt.includes('proveedor')||txt.includes('47'))){rows.forEach(function(r){var tds=r.querySelectorAll('td');if(tds.length>=2){var cod=cleanTxt(tds[0]?tds[0].innerText:'');var nom=cleanTxt(tds[1]?tds[1].innerText:'');if(nom&&nom!=='Volver al graficador'&&!nom.includes('graficador')){insumos.push({codigo:cod,nombre:nom,descripcion:getCellTxt(tds[2]),proveedor:cleanTxt(tds[3]?tds[3].innerText:'')||'Todas las direcciones'});}}});}if(actividades.length===0&&txt.includes('actividad')&&(txt.includes('objetivo')||txt.includes('responsable')||txt.includes('48'))){rows.forEach(function(r){var tds=r.querySelectorAll('td');if(tds.length>=2){var cod=cleanTxt(tds[0]?tds[0].innerText:'');var nom=cleanTxt(tds[1]?tds[1].innerText:'');if(nom&&nom!=='Volver al graficador'&&!nom.includes('graficador')){actividades.push({codigo:cod,nombre:nom,objetivo:cleanTxt(tds[2]?tds[2].innerText:''),responsable:cleanTxt(tds[3]?tds[3].innerText:'')||'Analista IPS',tareas:[]});}}});}if(txt.includes('tarea')&&txt.includes('tiempo')){var currAct='';rows.forEach(function(r){var tds=r.querySelectorAll('td');if(tds.length>=4){currAct=cleanTxt(tds[1]?tds[1].innerText:'');var tDesc=cleanTxt(tds[2]?tds[2].innerText:'');var tTime=cleanTxt(tds[3]?tds[3].innerText:'');if(currAct&&tDesc){if(!tareasMap[currAct])tareasMap[currAct]=[];tareasMap[currAct].push({descripcion:tDesc,tiempo:tTime});}}else if(tds.length===2&&currAct){var tDesc2=cleanTxt(tds[0]?tds[0].innerText:'');var tTime2=cleanTxt(tds[1]?tds[1].innerText:'');if(tDesc2){if(!tareasMap[currAct])tareasMap[currAct]=[];tareasMap[currAct].push({descripcion:tDesc2,tiempo:tTime2});}}});}});}if(actividades.length>0){actividades.forEach(function(act){var nom=act.nombre.toLowerCase();var matched=[];for(var key in tareasMap){if(key.toLowerCase().includes(nom.substring(0,25))||nom.includes(key.toLowerCase().substring(0,25))){matched=tareasMap[key];break;}}if(matched.length>0){act.tareas=matched.map(function(t){var timeMin=30;if(/(\\d+)\\s*d[ií]as?/i.test(t.tiempo)){timeMin=parseInt(t.tiempo.match(/(\\d+)/)[1])*480;}else if(/(\\d+)\\s*horas?/i.test(t.tiempo)){timeMin=parseInt(t.tiempo.match(/(\\d+)/)[1])*60;}else if(/(\\d+)\\s*min/i.test(t.tiempo)){timeMin=parseInt(t.tiempo.match(/(\\d+)/)[1]);}return{descripcion:t.descripcion,tiempo_minutos:timeMin};});}else{act.tareas=[{descripcion:act.objetivo||'Ejecución del procedimiento',tiempo_minutos:30}];}});}if(actividades.length===0){if(productos.length>0){productos.forEach(function(p,i){actividades.push({codigo:p.codigo||('ACT_0'+(i+1)),nombre:p.nombre||('Actividad de '+subproceso),responsable:'Analista IPS',tareas:[{descripcion:p.descripcion||'Ejecución del procedimiento',tiempo_minutos:30}]});});}else{actividades.push({codigo:'ACT_01',nombre:subproceso,responsable:'Analista IPS',tareas:[{descripcion:'Modelado y revisión del procedimiento MECIP IPS',tiempo_minutos:45}]});}}var payload={numero_caso:numeroCaso,codigo_subproceso:codigoSubproceso,macroproceso:macroproceso,proceso:proceso,subproceso:subproceso,version:version,productos:productos,insumos:insumos,actividades:actividades};var jsonStr=JSON.stringify(payload);var targetUrl='".$targetRedirectUrl."?import_json='+encodeURIComponent(jsonStr);if(win){win.location.href=targetUrl;}else{window.location.href=targetUrl;}}catch(err){if(win)win.close();alert('Error al extraer expediente: '+err.message);}})();";
                    @endphp

                    <a href="{!! $bookmarkletJs !!}" onclick="alert('¡Arrastrá este botón hasta tu barra de Marcadores de arriba!'); return false;" class="btn btn-lg btn-warning text-dark font-weight-bold px-4 py-3 shadow-md border" style="border-radius: 30px; font-size: 1.1rem; cursor: grab;">
                        <i class="fas fa-bolt mr-2 text-danger"></i> ⚡ Replicar Caso IPS a SIPLAN
                    </a>
                </div>

                <div class="p-3 bg-white border rounded" style="font-size: 0.84rem;">
                    <strong class="text-dark d-block mb-1"><i class="fas fa-list-ol text-info mr-1"></i> Pasos Simples:</strong>
                    <ol class="pl-3 mb-0 text-muted" style="line-height: 1.5;">
                        <li>Hacé clic sostenido sobre el botón amarillo <strong>"⚡ Replicar Caso IPS a SIPLAN"</strong> de arriba y arrastralo hasta tu barra de marcadores del navegador (debajo de la URL).</li>
                        <li>Ingresá al sistema del IPS en tu computadora y entrá al expediente o tabla del subproceso que querés guardar.</li>
                        <li>Presioná el marcador <strong>"⚡ Replicar Caso IPS a SIPLAN"</strong> en tu navegador. ¡Listo! Se abrirá automáticamente tu local SIPLAN con todos los datos replicados.</li>
                    </ol>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
<!-- Modal Sincronizador BPM Scraping -->
<div class="modal fade" id="modalSyncBpmScraper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px !important; overflow: hidden;">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-network-wired text-info mr-2"></i> Sincronización Automática Cytera BPM IPS
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <ul class="nav nav-pills nav-justified mb-3" id="pills-tab-bpm" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="tab-remote-sync" data-toggle="pill" href="#pane-remote-sync" role="tab">
                            <i class="fas fa-key mr-1"></i> Extracción HTTP Remota Autenticada
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="tab-html-paste" data-toggle="pill" href="#pane-html-paste" role="tab">
                            <i class="fas fa-code mr-1"></i> Pegar Formulario HTML Directo
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent-bpm">
                    {{-- Pestaña 1: Extracción HTTP Autenticada --}}
                    <div class="tab-pane fade show active p-3 bg-white border rounded shadow-xs" id="pane-remote-sync" role="tabpanel">
                        <form id="formSyncBpmScraper">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark small"><i class="fas fa-hashtag text-info mr-1"></i> ID de Proceso (m_process_id):</label>
                                    <input type="text" class="form-control" id="sync_process_id" value="101" placeholder="Ej. 101, 3782431">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark small"><i class="fas fa-folder text-info mr-1"></i> Número de Caso Local:</label>
                                    <input type="text" class="form-control" id="sync_numero_caso" value="CASO-BPM-101" placeholder="Ej. CASO-BPM-101">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark small"><i class="fas fa-user text-info mr-1"></i> Usuario IPS (m_btn_user):</label>
                                    <input type="text" class="form-control" id="sync_user" placeholder="Ej. jufranco">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark small"><i class="fas fa-lock text-info mr-1"></i> Contraseña IPS (m_btn_password):</label>
                                    <input type="password" class="form-control" id="sync_password" placeholder="Tu contraseña del IPS">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="font-weight-bold text-dark small"><i class="fas fa-link text-info mr-1"></i> URL del Servlet Cytera BPM:</label>
                                <input type="text" class="form-control" id="sync_url" value="https://servicios.ips.gov.py/ips/servlet/WSNavigatorPlus">
                            </div>
                            <button type="button" class="btn btn-dark btn-block font-weight-bold py-2.5 shadow-sm" id="btnRunSyncBpm" onclick="ejecutarSyncBpmScraper()">
                                <i class="fas fa-rocket text-warning mr-2"></i> 🚀 Iniciar Extracción Autenticada Remota
                            </button>
                        </form>
                    </div>

                    {{-- Pestaña 2: Pegar Formulario HTML Directo --}}
                    <div class="tab-pane fade p-3 bg-white border rounded shadow-xs" id="pane-html-paste" role="tabpanel">
                        <form id="formParseHtmlPayload">
                            <div class="mb-3">
                                <label class="font-weight-bold text-dark small"><i class="fas fa-folder text-info mr-1"></i> Número de Caso Asignado:</label>
                                <input type="text" class="form-control" id="parse_numero_caso" value="CASO-BPM-HTML-001">
                            </div>
                            <div class="mb-3">
                                <label class="font-weight-bold text-dark small"><i class="fas fa-code text-info mr-1"></i> Código Fuente HTML Pegado del Formulario BPM:</label>
                                <textarea class="form-control font-monospace" id="parse_html_content" rows="7" placeholder="Pegá aquí el código HTML (Ver Código Fuente de la Página o Inspeccionar Elemento en Chrome/Edge)..." style="font-size: 0.8rem;"></textarea>
                            </div>
                            <button type="button" class="btn btn-info btn-block font-weight-bold py-2.5 shadow-sm" id="btnRunParseHtml" onclick="ejecutarParseHtmlPayload()">
                                <i class="fas fa-magic mr-2"></i> ⚡ Parsear e Importar HTML Directo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2 en todos los selectores
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        dropdownParent: $('#modalNuevoCasoMecip').length ? $('#modalNuevoCasoMecip') : null
    });

    // Inicializar DataTables
    if ($.fn.DataTable) {
        $('#tblCasosMecip').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            order: [],
            language: {
                search: "Buscar caso/subproceso:",
                searchPlaceholder: "Filtrar por Nº de caso o subproceso...",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ casos MECIP",
                infoEmpty: "No hay registros",
                infoFiltered: "(filtrado de _MAX_ casos totales)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }

    // Escuchar evento Redis de notificaciones en tiempo real
    if (window.Echo) {
        window.Echo.channel('mecip-notificaciones')
            .listen('.mecip.notificacion', (e) => {
                toastr.info(e.mensaje, '🚀 Notificación MECIP IPS (Redis)');
                setTimeout(() => location.reload(), 2000);
            });
    }

    // Auto-importar datos si provienen de la réplica 1-Clic del Marcador
    const urlParams = new URLSearchParams(window.location.search);
    const importJsonParam = urlParams.get('import_json');
    if (importJsonParam) {
        $('#json_data_input').val(importJsonParam);
        $('#modalImportarJsonMecip').modal('show');
        toastr.info('⚡ Importando expediente replicado desde IPS...', 'SIPLAN MECIP');
        setTimeout(() => ejecutarImportacionJson(), 600);
    }
});

function ejecutarSyncBpmScraper() {
    const btn = document.getElementById('btnRunSyncBpm');
    const processId = document.getElementById('sync_process_id').value;
    const numeroCaso = document.getElementById('sync_numero_caso').value;
    const user = document.getElementById('sync_user').value;
    const password = document.getElementById('sync_password').value;
    const url = document.getElementById('sync_url').value;

    if (!processId.trim()) {
        toastr.warning('Ingresá un ID de proceso m_process_id válido.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Autenticando y Extrayendo en BPM Servlets...';

    $.ajax({
        url: "{{ route('admin.mecip.control.syncFromBpm') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            process_id: processId,
            numero_caso: numeroCaso,
            user: user,
            password: password,
            url: url
        },
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-rocket text-warning mr-2"></i> 🚀 Iniciar Extracción Autenticada Remota';
            if (res.success) {
                toastr.success(res.message);
                $('#modalSyncBpmScraper').modal('hide');
                if (res.redirect) {
                    window.location.href = res.redirect;
                } else {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                toastr.error(res.message);
            }
        },
        error: function(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-rocket text-warning mr-2"></i> 🚀 Iniciar Extracción Autenticada Remota';
            toastr.error('Error al sincronizar con BPM Servlets: ' + (err.responseJSON?.message || 'Error del servidor'));
        }
    });
}

function ejecutarParseHtmlPayload() {
    const btn = document.getElementById('btnRunParseHtml');
    const htmlVal = document.getElementById('parse_html_content').value;
    const numeroCaso = document.getElementById('parse_numero_caso').value;

    if (!htmlVal.trim()) {
        toastr.warning('Por favor pegá el código HTML del formulario BPM.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Parseando Tablas HTML...';

    $.ajax({
        url: "{{ route('admin.mecip.control.parseHtmlPayload') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            html_content: htmlVal,
            numero_caso: numeroCaso
        },
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-2"></i> ⚡ Parsear e Importar HTML Directo';
            if (res.success) {
                toastr.success(res.message);
                $('#modalSyncBpmScraper').modal('hide');
                if (res.redirect) {
                    window.location.href = res.redirect;
                } else {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                toastr.error(res.message);
            }
        },
        error: function(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-2"></i> ⚡ Parsear e Importar HTML Directo';
            toastr.error('Error al parsear el HTML: ' + (err.responseJSON?.message || 'Error del servidor'));
        }
    });
}

function ejecutarImportacionJson() {
    const btn = document.getElementById('btnEjecutarImportacion');
    const jsonVal = document.getElementById('json_data_input').value;

    if (!jsonVal.trim()) {
        toastr.warning('Por favor pegá una estructura JSON válida.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Replicando...';

    $.ajax({
        url: "{{ route('admin.mecip.control.importarJson') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            json_data: jsonVal
        },
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Replicar Expediente';
            if (res.success) {
                toastr.success(res.message);
                window.location.href = res.redirect;
            } else {
                toastr.error(res.message);
            }
        },
        error: function(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Replicar Expediente';
            toastr.error('Error al importar el JSON: ' + (err.responseJSON?.message || 'Error del servidor'));
        }
    });
}
</script>
@endpush
@endsection
