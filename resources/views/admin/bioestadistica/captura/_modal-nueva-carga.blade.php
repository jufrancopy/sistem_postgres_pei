<div class="modal fade" id="modal-nueva-carga" tabindex="-1" role="dialog" aria-labelledby="modal-nueva-carga-label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
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
                        Elija el establecimiento, el período y, si corresponde, la dependencia donde se informa la variable.
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
                        <div class="form-group col-md-6">
                            <label>Año del período *</label>
                            @include('admin.bioestadistica._periodo-anio-select', [
                                'name' => 'periodo_anio',
                                'value' => $selectedAnio,
                                'required' => true,
                                'selectClass' => 'bio-select2-modal',
                            ])
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mes del período *</label>
                            <select class="form-control bio-select2-modal" name="periodo_mes" data-placeholder="Seleccione" required>
                                <option value="">Seleccione</option>
                                @foreach($months as $number => $month)
                                    <option value="{{ $number }}" @selected((int) $selectedMes === (int) $number)>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @include('admin.bioestadistica.captura._organo-corte-select', [
                        'cortes' => collect(),
                        'organoSelected' => (int) ($selectedOrganoId ?? 0),
                        'selectId' => 'captura-organo',
                        'selectClass' => 'bio-select2-modal',
                        'wrapperClass' => 'mb-0',
                        'alwaysEnabled' => true,
                        'corteRequired' => false,
                    ])
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
