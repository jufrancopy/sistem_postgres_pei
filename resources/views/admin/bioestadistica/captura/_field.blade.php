@php
    $stored = $valuesByField->get($field->id);
    $value = match($field->type) {
        'integer', 'decimal' => $stored?->value_num,
        'date' => $stored?->value_date?->format('Y-m-d'),
        'boolean' => $stored?->value_bool,
        'multiselect', 'tabla', 'subtabla', 'matriz' => $stored?->value_json,
        default => $stored?->value_text,
    };
    $editable = $record->isEditable() && auth()->user()->can('bio.record.update');
    $isBlock = in_array($field->type, ['textarea', 'tabla', 'subtabla', 'matriz'], true);
@endphp
<div class="bio-capture-field mb-3 {{ $isBlock ? 'bio-capture-field--block' : '' }}">
    <div class="bio-capture-field__head mb-2">
        <label class="bio-capture-field__label mb-0" for="field-{{ $field->id }}">
            {{ $field->label }}
            @if($field->required)<span class="text-danger">*</span>@endif
        </label>
        @if($field->help_text)
            <small class="bio-capture-field__help text-muted d-block mt-1">{{ $field->help_text }}</small>
        @endif
    </div>

    @if($field->type === 'textarea')
        <textarea class="form-control" id="field-{{ $field->id }}" name="values[{{ $field->code }}]" rows="3" @disabled(!$editable)>{{ old("values.{$field->code}", $value) }}</textarea>
    @elseif(in_array($field->type, ['integer', 'decimal']))
        <input class="form-control" id="field-{{ $field->id }}" type="number" step="{{ $field->type === 'integer' ? '1' : '0.0001' }}" name="values[{{ $field->code }}]" value="{{ old("values.{$field->code}", $value) }}" min="{{ $field->min_value }}" max="{{ $field->max_value }}" @disabled(!$editable)>
    @elseif($field->type === 'date')
        <input class="form-control" id="field-{{ $field->id }}" type="date" name="values[{{ $field->code }}]" value="{{ old("values.{$field->code}", $value) }}" @disabled(!$editable)>
    @elseif($field->type === 'time')
        <input class="form-control" id="field-{{ $field->id }}" type="time" name="values[{{ $field->code }}]" value="{{ old("values.{$field->code}", $value) }}" @disabled(!$editable)>
    @elseif($field->type === 'boolean')
        <select class="form-control" id="field-{{ $field->id }}" name="values[{{ $field->code }}]" @disabled(!$editable)><option value="">Seleccione</option><option value="1" @selected(old("values.{$field->code}", $value) === true || old("values.{$field->code}", $value) === '1')>Sí</option><option value="0" @selected(old("values.{$field->code}", $value) === false || old("values.{$field->code}", $value) === '0')>No</option></select>
    @elseif(in_array($field->type, ['select', 'radio']))
        <select class="form-control" id="field-{{ $field->id }}" name="values[{{ $field->code }}]" @disabled(!$editable)><option value="">Seleccione</option>@foreach($field->rowItems() as $item)<option value="{{ $item->id }}" @selected((string) old("values.{$field->code}", $value) === (string) $item->id)>{{ $item->label }}</option>@endforeach</select>
    @elseif($field->type === 'multiselect')
        <select class="form-control" id="field-{{ $field->id }}" name="values[{{ $field->code }}][]" multiple @disabled(!$editable)>@foreach($field->rowItems() as $item)<option value="{{ $item->id }}" @selected(in_array($item->id, old("values.{$field->code}", $value ?? [])))>{{ $item->label }}</option>@endforeach</select>
    @elseif($field->type === 'tabla')
        @php
            $columns = $field->config['columns'] ?? [];
            $rows = $field->rowItems();
            $storedRows = $value['rows'] ?? [];
        @endphp
        @if($columns === [] || $rows->isEmpty())
            <div class="alert alert-warning mb-0">El campo no tiene columnas configuradas o su diccionario/catálogo está vacío.</div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-bordered bio-tabla mb-0" data-totals="{{ ($field->config['totals'] ?? false) ? '1' : '0' }}">
                    <thead class="thead-light">
                        <tr>
                            <th style="min-width:260px">{{ $field->config['row_label'] ?? 'Ítem' }}</th>
                            @foreach($columns as $column)
                                <th class="text-right" style="width:160px">{{ $column['label'] ?? $column['code'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $item)
                        <tr>
                            <td>{{ $item->label }}</td>
                            @foreach($columns as $column)
                                @php $columnCode = $column['code']; @endphp
                                <td>
                                    <input
                                        class="form-control form-control-sm text-right bio-tabla-input"
                                        type="number"
                                        step="{{ ($column['type'] ?? 'integer') === 'integer' ? '1' : '0.0001' }}"
                                        @isset($column['min']) min="{{ $column['min'] }}" @endisset
                                        @isset($column['max']) max="{{ $column['max'] }}" @endisset
                                        data-column="{{ $columnCode }}"
                                        name="values[{{ $field->code }}][rows][{{ $item->id }}][{{ $columnCode }}]"
                                        value="{{ old("values.{$field->code}.rows.{$item->id}.{$columnCode}", $storedRows[$item->id][$columnCode] ?? null) }}"
                                        @disabled(!$editable)>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                    @if($field->config['totals'] ?? false)
                        <tfoot>
                            <tr>
                                <th>Total general</th>
                                @foreach($columns as $column)
                                    <th class="text-right" data-total="{{ $column['code'] }}">0</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        @endif
    @elseif($field->type === 'matriz')
        @php
            $days = \Carbon\Carbon::create($record->periodo_anio, $record->periodo_mes, 1)->daysInMonth;
            $rowCodes = $field->config['rows'] ?? array_keys(\App\Application\Bioestadistica\Sp11Matrix::ROWS);
            $rowLabels = $field->config['row_labels'] ?? array_values(\App\Application\Bioestadistica\Sp11Matrix::ROWS);
            $storedRows = is_array($value) ? ($value['rows'] ?? $value) : [];
        @endphp
        <div class="table-responsive">
            <table class="table table-sm table-bordered bio-matriz mb-0" data-days="{{ $days }}">
                <thead class="thead-light">
                    <tr>
                        <th style="min-width:160px">Indicador</th>
                        @for($day = 1; $day <= $days; $day++)
                            <th class="text-center" style="width:42px">{{ $day }}</th>
                        @endfor
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rowCodes as $index => $rowCode)
                    <tr>
                        <td>{{ $rowLabels[$index] ?? $rowCode }}</td>
                        @for($day = 1; $day <= $days; $day++)
                            <td>
                                <input
                                    class="form-control form-control-sm text-right bio-matriz-input"
                                    type="number"
                                    min="0"
                                    step="1"
                                    data-row="{{ $rowCode }}"
                                    name="values[{{ $field->code }}][rows][{{ $rowCode }}][{{ $day }}]"
                                    value="{{ old("values.{$field->code}.rows.{$rowCode}.{$day}", $storedRows[$rowCode][$day] ?? $storedRows[$rowCode][(string)$day] ?? null) }}"
                                    @disabled(!$editable)>
                            </td>
                        @endfor
                        <th class="text-right" data-row-total="{{ $rowCode }}">{{ $storedRows[$rowCode]['total'] ?? 0 }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <small class="text-muted d-block mt-2">El mes {{ $record->periodo_mes }}/{{ $record->periodo_anio }} tiene {{ $days }} días. Los totales se calculan al guardar.</small>
    @elseif(in_array($field->type, ['subtabla']))
    @else
        <input class="form-control" id="field-{{ $field->id }}" type="text" name="values[{{ $field->code }}]" value="{{ old("values.{$field->code}", $value) }}" pattern="{{ $field->validation_regex }}" @disabled(!$editable)>
    @endif
    @error("values.{$field->code}")<span class="text-danger small d-block mt-1">{{ $message }}</span>@enderror
</div>
