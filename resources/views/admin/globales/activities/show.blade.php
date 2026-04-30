@extends('layouts.master')
@section('title', 'Tablero - ' . $activity->name)

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title">
                <i class="fas fa-columns"></i> {{ $activity->name }}
                <span class="badge badge-light ml-2" style="font-size:11px;">
                    {{ $activity->type == 'scrum' ? 'Scrum' : 'Kanban' }}
                </span>
            </h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('globales.activities.index') }}">Actividades</a></li>
                <li class="breadcrumb-item active">{{ $activity->name }}</li>
            </ol>
        </nav>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-8">
                    @if ($activity->description)
                        <p class="text-muted mb-1">{{ $activity->description }}</p>
                    @endif
                </div>
                <div class="col-md-4 text-right">
                    @if ($activity->date_start)
                        <small class="text-muted">
                            <i class="fa fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($activity->date_start)->format('d/m/Y') }} —
                            {{ \Carbon\Carbon::parse($activity->date_end)->format('d/m/Y') }}
                        </small><br>
                    @endif
                    <small class="text-muted">
                        <i class="fa fa-users"></i>
                        {{ $activity->responsibles->pluck('name')->implode(', ') ?: 'Sin responsables' }}
                    </small>
                </div>
            </div>

            <div class="text-right mb-3">
                <button class="btn btn-success btn-sm" id="btnNuevaTarea">
                    <i class="fa fa-plus"></i> Nueva Tarea
                </button>
            </div>

            {{-- Tablero --}}
            <div class="row">
                @php
                    $columnas = [
                        0 => ['label' => 'Pendiente', 'color' => '#e74c3c', 'text' => '#fff'],
                        1 => ['label' => 'En Progreso', 'color' => '#2980b9', 'text' => '#fff'],
                        2 => ['label' => 'Completado', 'color' => '#27ae60', 'text' => '#fff'],
                    ];
                @endphp

                @foreach ($columnas as $status => $col)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header py-2"
                                style="background-color:{{ $col['color'] }}; color:{{ $col['text'] }};">
                                <strong>{{ $col['label'] }}</strong>
                                <span class="badge badge-light float-right">
                                    {{ $activity->tasks->where('status', $status)->count() }}
                                </span>
                            </div>
                            <div class="card-body p-2" style="min-height:200px;">
                                @foreach ($activity->tasks->where('status', $status)->sortByDesc('created_at') as $task)
                                    <div class="card mb-2 shadow-sm task-card" data-id="{{ $task->id }}"
                                        data-status="{{ $task->status }}">
                                        <div class="card-body py-2 px-3">

                                            <p class="mb-1 font-weight-bold" style="font-size:13px;">
                                                @if ($task->status == 2)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @endif
                                                {{ $task->title }}
                                            </p>

                                            @if ($task->details)
                                                <p class="mb-1 text-muted" style="font-size:11px;">{{ $task->details }}</p>
                                            @endif

                                            @if ($task->assignedTo)
                                                <span class="badge badge-secondary" style="font-size:10px;">
                                                    <i class="fa fa-user"></i> {{ $task->assignedTo->name }}
                                                </span>
                                            @endif

                                            {{-- Nota de cierre --}}
                                            @if ($task->status == 2 && $task->completion_note)
                                                <div class="mt-1 p-1 rounded"
                                                    style="background:#f0fff4; border-left:3px solid #27ae60; font-size:10px;">
                                                    <i class="fas fa-comment-alt text-success"></i>
                                                    <em>{{ $task->completion_note }}</em><br>
                                                    <small class="text-muted">
                                                        {{ $task->completedBy->name ?? '' }} —
                                                        {{ \Carbon\Carbon::parse($task->completed_at)->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            @endif

                                            {{-- Evidencias --}}
                                            @if ($task->evidences->count())
                                                <div class="mt-1">
                                                    @foreach ($task->evidences as $ev)
                                                        <div class="d-flex align-items-center mb-1" style="font-size:10px;">
                                                            @if ($ev->type === 'url')
                                                                <i class="fas fa-link text-primary mr-1"></i>
                                                                <a href="{{ $ev->value }}" target="_blank"
                                                                    class="text-truncate"
                                                                    style="max-width:160px;">{{ $ev->label }}</a>
                                                            @elseif($ev->type === 'image')
                                                                <a href="{{ asset('storage/' . $ev->value) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $ev->value) }}"
                                                                        style="height:32px; width:32px; object-fit:cover; border-radius:3px;"
                                                                        class="mr-1">
                                                                </a>
                                                                <span class="text-truncate"
                                                                    style="max-width:130px;">{{ $ev->label }}</span>
                                                            @else
                                                                <i class="fas fa-file-alt text-warning mr-1"></i>
                                                                <a href="{{ asset('storage/' . $ev->value) }}" target="_blank"
                                                                    class="text-truncate"
                                                                    style="max-width:160px;">{{ $ev->label }}</a>
                                                            @endif
                                                            <a href="javascript:void(0)"
                                                                class="ml-auto text-danger btn-delete-evidence"
                                                                data-id="{{ $ev->id }}">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Acciones --}}
                                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if ($status > 0)
                                                        <button class="btn btn-xs btn-outline-secondary btn-move-left"
                                                            data-id="{{ $task->id }}"
                                                            data-status="{{ $status }}" title="Retroceder">
                                                            <i class="fas fa-arrow-left"></i>
                                                        </button>
                                                    @endif
                                                    @if ($status < 2)
                                                        <button class="btn btn-xs btn-outline-primary btn-move-right"
                                                            data-id="{{ $task->id }}"
                                                            data-status="{{ $status }}" title="Avanzar">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-xs btn-outline-secondary btn-add-evidence"
                                                        data-id="{{ $task->id }}" title="Agregar evidencia">
                                                        <i class="fas fa-paperclip"></i>
                                                    </button>
                                                </div>
                                                <button class="btn btn-xs btn-outline-danger btn-delete-task"
                                                    data-id="{{ $task->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- Modal Nueva/Editar Tarea --}}
    <div class="modal fade" id="tareaModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="card-header card-header-info">
                    <h4 class="modal-title" id="tareaHeading">Nueva Tarea</h4>
                </div>
                <div class="modal-body">
                    <form id="tareaForm">
                        {{ csrf_field() }}
                        <input type="hidden" id="task_id" name="task_id">
                        <div class="form-group">
                            <label>Título <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="task_title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="details" id="task_details" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Responsable</label>
                            <select name="assigned_to" id="task_assigned_to" style="width:100%"></select>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="status" id="task_status" class="form-control">
                                <option value="0">Pendiente</option>
                                <option value="1">En Progreso</option>
                                <option value="2">Completado</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" id="saveTareaBtn">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Nota de Cierre --}}
    <div class="modal fade" id="completionModal" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="card-header card-header-success">
                    <h4 class="modal-title"><i class="fas fa-check-circle"></i> Completar Tarea</h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size:12px;">Ingrese un comentario de cierre describiendo cómo se
                        completó la tarea.</p>
                    <input type="hidden" id="completion_task_id">
                    <input type="hidden" id="completion_new_status">
                    <div class="form-group">
                        <label>Comentario <span class="text-danger">*</span></label>
                        <textarea id="completion_note" class="form-control" rows="3"
                            placeholder="Ej: Se envió el informe al director el día..."></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-sm" id="btnConfirmComplete">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Evidencia --}}
    <div class="modal fade" id="evidenceModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="card-header card-header-info">
                    <h4 class="modal-title">Agregar Evidencia</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="evidence_task_id">

                    <div class="form-group">
                        <label>Tipo de evidencia</label><br>
                        <div class="d-flex flex-wrap w-100" style="gap:0.4rem;">
                            <button type="button" class="btn btn-outline-primary btn-sm ev-type-btn active flex-fill" data-type="url"
                                style="min-width:140px;flex:1 1 150px;"
                                onclick="toggleEvidenceSection('url')">
                                &#128279; Enlace / URL
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm ev-type-btn flex-fill" data-type="image"
                                style="min-width:140px;flex:1 1 150px;"
                                onclick="toggleEvidenceSection('image')">
                                &#128444; Imagen (m&aacute;x. 2MB)
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm ev-type-btn flex-fill" data-type="document"
                                style="min-width:140px;flex:1 1 150px;"
                                onclick="toggleEvidenceSection('document')">
                                &#128196; Documento (m&aacute;x. 5MB)
                            </button>
                        </div>
                        <input type="hidden" id="evidence_type" value="url">
                    </div>

                    {{-- URL --}}
                    <div id="ev_url_section">
                        <div class="form-group">
                            <label>Descripción del enlace</label>
                            <input type="text" id="ev_label" class="form-control"
                                placeholder="Ej: Carpeta compartida Dirección">
                        </div>
                        <div class="form-group">
                            <label>URL</label>
                            <input type="url" id="ev_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>

                    {{-- Archivo --}}
                    <div id="ev_file_section" style="display:none;">
                        <div class="form-group">
                            <label>Archivo</label>
                            <label for="ev_file" id="ev_file_label"
                                style="
            display: flex;
            align-items: center;
            gap: 10px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 14px 16px;
            cursor: pointer;
            margin-top: 6px;
            transition: border-color 0.2s;
        ">
                                <i class="fas fa-upload" style="font-size:18px; color:#9c27b0;"></i>
                                <span id="ev_file_name" style="font-size:13px; color:#555;">Haga clic para seleccionar un
                                    archivo</span>
                                <input type="file" id="ev_file" style="display:none;">
                            </label>
                            <small id="ev_file_hint" class="text-muted d-block mt-1"></small>
                        </div>
                        <div id="ev_size_warning" class="alert alert-warning mt-2" style="display:none; font-size:12px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            El archivo supera el límite permitido. Suba el documento a su carpeta compartida y registre el
                            enlace.
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="btnSaveEvidence">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function toggleEvidenceSection(type) {
            // Actualizar el hidden input
            document.getElementById('evidence_type').value = type;

            // Marcar botón activo
            document.querySelectorAll('.ev-type-btn').forEach(function(btn) {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            var activeBtn = document.querySelector('.ev-type-btn[data-type="' + type + '"]');
            if (activeBtn) {
                activeBtn.classList.remove('btn-outline-primary');
                activeBtn.classList.add('active', 'btn-primary');
            }

            if (type === 'url') {
                document.getElementById('ev_url_section').style.display = 'block';
                document.getElementById('ev_file_section').style.display = 'none';
            } else {
                document.getElementById('ev_url_section').style.display = 'none';
                document.getElementById('ev_file_section').style.display = 'block';
                document.getElementById('ev_file_hint').textContent = type === 'image' ?
                    'Máximo 2MB. Formatos: jpg, png, gif, webp' :
                    'Máximo 5MB. Formatos: pdf, doc, docx, xls, xlsx';
                document.getElementById('ev_size_warning').style.display = 'none';
            }
        }

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var activityId = {{ $activity->id }};
            var storeUrl = "{{ route('globales.activities.tareas.store', $activity->id) }}";
            var statusBase = "{{ url('admin/globales/activities/tareas') }}";
            var evidenceBase = "{{ url('admin/globales/activities/tareas') }}";
            var getUsersUrl = "{{ route('globales.get-users') }}";

            // ── Select2 responsable ──────────────────────────────────────
            function initResponsableSelect(selectedId, selectedText) {
                $('#task_assigned_to').empty().select2({
                    placeholder: 'Seleccione responsable',
                    allowClear: true,
                    dropdownParent: $('#tareaModal'),
                    ajax: {
                        url: getUsersUrl,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(u) {
                                    return {
                                        id: u.id,
                                        text: u.name
                                    };
                                })
                            };
                        }
                    }
                });
                if (selectedId) {
                    $('#task_assigned_to').append(new Option(selectedText, selectedId, true, true)).trigger(
                        'change');
                }
            }

            // ── Nueva tarea ──────────────────────────────────────────────
            $('#btnNuevaTarea').click(function() {
                $('#tareaHeading').text('Nueva Tarea');
                $('#tareaForm')[0].reset();
                $('#task_id').val('');
                initResponsableSelect(null, null);
                $('#tareaModal').modal('show');
            });

            $('#tareaForm').submit(function(e) {
                e.preventDefault();
                $('#saveTareaBtn').text('Guardando...');
                $.ajax({
                    url: storeUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#tareaModal').modal('hide');
                        toastr.success(res.success);
                        location.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) $.each(errors, function(k, v) {
                            toastr.error(v);
                        });
                        $('#saveTareaBtn').text('Guardar');
                    }
                });
            });

            // ── Mover tarea ──────────────────────────────────────────────
            $('body').on('click', '.btn-move-right', function() {
                var taskId = $(this).data('id');
                var newStatus = parseInt($(this).data('status')) + 1;
                if (newStatus === 2) {
                    // Pedir nota de cierre
                    $('#completion_task_id').val(taskId);
                    $('#completion_new_status').val(newStatus);
                    $('#completion_note').val('');
                    $('#completionModal').modal('show');
                } else {
                    doMoveTask(taskId, newStatus, null);
                }
            });

            $('body').on('click', '.btn-move-left', function() {
                var taskId = $(this).data('id');
                var newStatus = parseInt($(this).data('status')) - 1;
                doMoveTask(taskId, newStatus, null);
            });

            $('#btnConfirmComplete').click(function() {
                var note = $('#completion_note').val().trim();
                if (!note) {
                    toastr.error('El comentario de cierre es obligatorio');
                    return;
                }
                var taskId = $('#completion_task_id').val();
                var newStatus = $('#completion_new_status').val();
                doMoveTask(taskId, newStatus, note);
                $('#completionModal').modal('hide');
            });

            function doMoveTask(taskId, newStatus, note) {
                $.ajax({
                    url: statusBase + '/' + taskId + '/status',
                    type: 'PATCH',
                    data: {
                        status: newStatus,
                        completion_note: note
                    },
                    success: function() {
                        location.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) $.each(errors, function(k, v) {
                            toastr.error(v);
                        });
                        else toastr.error('Error al actualizar el estado');
                    }
                });
            }

            // ── Eliminar tarea ───────────────────────────────────────────
            $('body').on('click', '.btn-delete-task', function() {
                var taskId = $(this).data('id');
                Swal.fire({
                    title: '¿Eliminar tarea?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: statusBase + '/' + taskId,
                            type: 'DELETE',
                            success: function() {
                                location.reload();
                            },
                            error: function() {
                                toastr.error('Error al eliminar');
                            }
                        });
                    }
                });
            });

            // ── Evidencias ───────────────────────────────────────────────
            // ── Evidencias ───────────────────────────────────────────────
            function toggleEvidenceSection(type) {
                $('#evidence_type').val(type);

                // Actualizar botones activos
                $('.ev-type-btn').removeClass('active');
                $('.ev-type-btn[data-type="' + type + '"]').addClass('active');

                if (type === 'url') {
                    $('#ev_url_section').show();
                    $('#ev_file_section').hide();
                } else {
                    $('#ev_url_section').hide();
                    $('#ev_file_section').show();
                    $('#ev_file_hint').text(
                        type === 'image' ?
                        'Máximo 2MB. Formatos: jpg, png, gif, webp' :
                        'Máximo 5MB. Formatos: pdf, doc, docx, xls, xlsx'
                    );
                    $('#ev_size_warning').hide();
                    $('#ev_file').val('');
                }
            }

            $('body').on('click', '.btn-add-evidence', function() {
                $('#evidence_task_id').val($(this).data('id'));
                $('#ev_label').val('');
                $('#ev_url').val('');
                $('#ev_file').val('');
                $('#evidenceModal').modal('show');
            });

            $('#evidenceModal').on('shown.bs.modal', function() {
                toggleEvidenceSection('url');
                $('#btnSaveEvidence').text('Guardar');
            });

            $('body').on('change', '#ev_file', function() {
                var type = $('#evidence_type').val();
                var maxMB = type === 'image' ? 2 : 5;
                var file = this.files[0];

                if (file && file.size > maxMB * 1024 * 1024) {
                    $('#ev_size_warning').show();
                    $('#ev_file_name').text('Haga clic para seleccionar un archivo');
                    $(this).val('');
                } else {
                    $('#ev_size_warning').hide();
                    $('#ev_file_name').text(file ? file.name : 'Haga clic para seleccionar un archivo');
                }
            });

            $('#btnSaveEvidence').click(function() {
                var taskId = $('#evidence_task_id').val();
                var type = $('#evidence_type').val();
                var url = evidenceBase + '/' + taskId + '/evidencias';
                var formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('type', type);

                if (type === 'url') {
                    formData.append('label', $('#ev_label').val());
                    formData.append('value', $('#ev_url').val());
                } else {
                    var file = $('#ev_file')[0].files[0];
                    if (!file) {
                        toastr.error('Seleccione un archivo');
                        return;
                    }
                    formData.append('file', file);
                }

                $(this).text('Guardando...');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#evidenceModal').modal('hide');
                        toastr.success(res.success);
                        location.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) $.each(errors, function(k, v) {
                            toastr.error(v);
                        });
                        else toastr.error('Error al guardar la evidencia');
                        $('#btnSaveEvidence').text('Guardar');
                    }
                });
            });

            // ── Eliminar evidencia ───────────────────────────────────────
            $('body').on('click', '.btn-delete-evidence', function() {
                var evId = $(this).data('id');
                Swal.fire({
                    title: '¿Eliminar evidencia?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'Cancelar'
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: evidenceBase + '/evidencias/' + evId,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function() {
                                location.reload();
                            },
                            error: function() {
                                toastr.error('Error al eliminar');
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
