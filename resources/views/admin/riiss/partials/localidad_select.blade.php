{{--
    Selector encadenado con Select2: Departamento → Distrito → Barrio/Localidad
    Uso: @include('admin.riiss.partials.localidad_select', ['prefix' => 'eval', 'required' => false])
    Leer valores en JS: evalGetLocalidad() → { departamento, distrito, barrio }
--}}
@php
    $prefix   = $prefix   ?? 'loc';
    $required = $required ?? false;
@endphp

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-uppercase text-muted">
            Departamento {!! $required ? '<span class="text-danger">*</span>' : '' !!}
        </label>
        <select id="{{ $prefix }}-depto" class="form-control" style="width:100%"></select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-uppercase text-muted">
            Ciudad / Distrito {!! $required ? '<span class="text-danger">*</span>' : '' !!}
        </label>
        <select id="{{ $prefix }}-dist" class="form-control" style="width:100%"></select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-uppercase text-muted">Barrio / Localidad</label>
        <select id="{{ $prefix }}-barrio" class="form-control" style="width:100%"></select>
    </div>
</div>

<script>
(function() {
    var P           = '{{ $prefix }}';
    var URL_DEPTOS  = '{{ route("riiss.localidades.departamentos") }}';
    var URL_DISTS   = '{{ route("riiss.localidades.distritos") }}';
    var URL_BARRIOS = '{{ route("riiss.localidades.barrios") }}';

    var S2_OPTS = {
        width: '100%',
        allowClear: true,
        language: {
            noResults:     function() { return 'Sin resultados'; },
            searching:     function() { return 'Buscando...'; },
            inputTooShort: function() { return 'Escribí para buscar'; },
        },
        dropdownParent: $('body'),
    };

    function templateUser(u) {
        if (u.loading) return u.text;
        return $('<span>' + u.text + '</span>');
    }

    $(document).ready(function() {

        // ── Departamento ──────────────────────────────────────────────────────
        $('#' + P + '-depto').select2($.extend({}, S2_OPTS, {
            placeholder: 'Seleccioná departamento',
            minimumInputLength: 0,
            ajax: {
                url: URL_DEPTOS,
                dataType: 'json',
                delay: 150,
                data: function(p) { return { q: p.term || '' }; },
                processResults: function(d) { return { results: d.results }; },
                cache: true,
            },
        }));

        // Precargar todos los departamentos
        $.get(URL_DEPTOS, { q: '' }, function(r) {
            if (!r.results) return;
            r.results.forEach(function(item) {
                $('#' + P + '-depto').append(new Option(item.text, item.id, false, false));
            });
        });

        // ── Al cambiar departamento → cargar distritos ────────────────────────
        $('#' + P + '-depto').on('change', function() {
            var codDepto = $(this).val();

            // Reset
            $('#' + P + '-dist').empty().append(new Option('', '', true, true)).trigger('change');
            $('#' + P + '-barrio').empty().append(new Option('', '', true, true)).trigger('change');

            if (!codDepto) return;

            // Inicializar select2 de distrito
            if (!$('#' + P + '-dist').data('select2')) {
                $('#' + P + '-dist').select2($.extend({}, S2_OPTS, {
                    placeholder: 'Seleccioná ciudad/distrito',
                    minimumInputLength: 0,
                    ajax: {
                        url: URL_DISTS,
                        dataType: 'json',
                        delay: 150,
                        data: function(p) {
                            return { q: p.term || '', cod_dpto: $('#' + P + '-depto').val() };
                        },
                        processResults: function(d) { return { results: d.results }; },
                        cache: false,
                    },
                }));
            }

            // Cargar distritos
            $.get(URL_DISTS, { cod_dpto: codDepto, q: '' }, function(r) {
                var $el = $('#' + P + '-dist');
                $el.empty().append(new Option('', '', true, true));
                r.results.forEach(function(item) {
                    $el.append(new Option(item.text, item.id, false, false));
                });
                $el.trigger('change.select2');
            });
        });

        // ── Al cambiar distrito → cargar barrios ──────────────────────────────
        $('#' + P + '-dist').on('change', function() {
            var codDist  = $(this).val();
            var codDepto = $('#' + P + '-depto').val();

            $('#' + P + '-barrio').empty().append(new Option('', '', true, true)).trigger('change');

            if (!codDist || !codDepto) return;

            if (!$('#' + P + '-barrio').data('select2')) {
                $('#' + P + '-barrio').select2($.extend({}, S2_OPTS, {
                    placeholder: 'Seleccioná barrio/localidad',
                    minimumInputLength: 0,
                    ajax: {
                        url: URL_BARRIOS,
                        dataType: 'json',
                        delay: 150,
                        data: function(p) {
                            return {
                                q:        p.term || '',
                                cod_dpto: $('#' + P + '-depto').val(),
                                cod_dist: $('#' + P + '-dist').val(),
                            };
                        },
                        processResults: function(d) { return { results: d.results }; },
                        cache: false,
                    },
                }));
            }

            $.get(URL_BARRIOS, { cod_dpto: codDepto, cod_dist: codDist, q: '' }, function(r) {
                var $el = $('#' + P + '-barrio');
                $el.empty().append(new Option('', '', true, true));
                r.results.forEach(function(item) {
                    $el.append(new Option(item.text, item.id, false, false));
                });
                $el.trigger('change.select2');
            });
        });

        // ── API pública ───────────────────────────────────────────────────────
        window[P + 'GetLocalidad'] = function() {
            return {
                departamento: {
                    cod:  $('#' + P + '-depto').val(),
                    text: $('#' + P + '-depto option:selected').text().trim(),
                },
                distrito: {
                    cod:  $('#' + P + '-dist').val(),
                    text: $('#' + P + '-dist option:selected').text().trim(),
                },
                barrio: {
                    id:   $('#' + P + '-barrio').val(),
                    text: $('#' + P + '-barrio option:selected').text().trim(),
                },
            };
        };

    });
})();
</script>
