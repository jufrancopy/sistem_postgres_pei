<div class="modal fade" id="modal-nueva-carga" tabindex="-1" role="dialog" aria-labelledby="modal-nueva-carga-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-nueva-carga" method="POST" action="{{ route('bioestadistica.captura.store') }}">
                @csrf
                <div class="modal-header card-header-info py-3">
                    <h5 class="modal-title text-white mb-0" id="modal-nueva-carga-label">
                        <i class="material-icons align-middle" style="font-size:20px">add_chart</i>
                        Iniciar carga estadística
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Elija el establecimiento, el período y, si corresponde, el departamento y servicio donde se informa la variable.
                    </p>
                    <div id="nueva-carga-errors" class="alert alert-danger d-none">
                        <ul class="mb-0" id="nueva-carga-errors-list"></ul>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Establecimiento *</label>
                            <select class="form-control bio-select2-modal" name="establecimiento_id" id="captura-establecimiento" data-placeholder="Seleccione" required>
                                <option value="">Seleccione</option>
                                @foreach($establecimientos as $establecimiento)
                                    <option value="{{ $establecimiento->id }}" @selected((string) $selectedEstablecimientoId === (string) $establecimiento->id)>
                                        {{ $establecimiento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Formulario (SP) *</label>
                            <select class="form-control bio-select2-modal" name="formulario_id" data-placeholder="Seleccione" required>
                                <option value="">Seleccione</option>
                                @foreach($formularios as $formulario)
                                    <option value="{{ $formulario->id }}" @selected((string) $selectedFormularioId === (string) $formulario->id)>
                                        {{ $formulario->codigo }} — {{ $formulario->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" id="captura-corte-group" style="display:none">
                            <label>Departamento / servicio *</label>
                            <select class="form-control" name="estructura_servicio_id" id="captura-corte">
                                <option value="">Seleccione establecimiento</option>
                            </select>
                            <small class="text-muted">Obligatorio cuando el establecimiento tiene más de un departamento o servicio.</small>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Año del período *</label>
                            <input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ $selectedAnio }}" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Mes del período *</label>
                            <select class="form-control bio-select2-modal" name="periodo_mes" data-placeholder="Seleccione" required>
                                <option value="">Seleccione</option>
                                @foreach($months as $number => $month)
                                    <option value="{{ $number }}" @selected((int) $selectedMes === (int) $number)>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-crear-carga">
                        <span class="btn-label">Crear borrador</span>
                        <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm" role="status"></span> Creando…</span>
                        <span class="btn-redirect d-none"><span class="spinner-border spinner-border-sm" role="status"></span> Abriendo carga…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
