@if($record->isImported())
    <span class="badge {{ $record->origenCargaBadgeClass() }}" title="{{ $record->importProcedenciaLabel() }}">
        <i class="material-icons align-middle" style="font-size:14px;">upload_file</i>
        Importación
    </span>
    @if($record->importProcedenciaLabel())
        <small class="text-muted d-block">{{ $record->importProcedenciaLabel() }}</small>
    @endif
@else
    <span class="badge {{ $record->origenCargaBadgeClass() }}">Manual</span>
@endif
