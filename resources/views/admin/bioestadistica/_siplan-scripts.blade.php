{{-- Scripts SIPLAN-lite: DataTables, Select2 y SweetAlert2 (assets ya globales) --}}
<script>
(function ($) {
    if (!$) return;

    var bioDtLanguage = {
        search: 'Buscar:',
        lengthMenu: 'Mostrar _MENU_ registros',
        info: 'Mostrando _START_ a _END_ de _TOTAL_',
        infoEmpty: 'Sin registros',
        infoFiltered: '(filtrado de _MAX_ en total)',
        zeroRecords: 'No se encontraron resultados',
        emptyTable: 'No hay datos para mostrar',
        processing: 'Procesando...',
        paginate: { first: 'Primero', last: 'Último', previous: '‹', next: '›' }
    };

    function orderFalseTargets($table) {
        return ($table.data('order-false') || '')
            .toString()
            .split(',')
            .map(function (v) { return parseInt(v, 10); })
            .filter(function (v) { return !isNaN(v); });
    }

    function initBioDataTables() {
        if (!$.fn.DataTable) return;
        $('.bio-data-table').each(function () {
            var $table = $(this);
            if ($.fn.DataTable.isDataTable($table)) return;
            if ($table.find('tbody tr').length === 0) return;

            var options = {
                pageLength: parseInt($table.data('page-length'), 10) || 25,
                order: [],
                language: bioDtLanguage,
                autoWidth: false
            };
            var orderableFalse = orderFalseTargets($table);
            if (orderableFalse.length) {
                options.columnDefs = [{ targets: orderableFalse, orderable: false, searchable: false }];
            }
            $table.DataTable(options);
        });
    }

    function initBioAjaxDataTables() {
        if (!$.fn.DataTable) return;
        $('.bio-data-table-ajax').each(function () {
            var $table = $(this);
            if ($.fn.DataTable.isDataTable($table)) return;
            var url = $table.data('url');
            var columns = $table.data('columns');
            if (!url || !columns) return;
            if (typeof columns === 'string') {
                try { columns = JSON.parse(columns); } catch (e) { return; }
            }

            var filterSelector = $table.data('filter-form');
            var orderableFalse = orderFalseTargets($table);
            var options = {
                processing: true,
                serverSide: true,
                pageLength: parseInt($table.data('page-length'), 10) || 25,
                order: [],
                language: bioDtLanguage,
                autoWidth: false,
                ajax: {
                    url: url,
                    data: function (d) {
                        if (!filterSelector) return;
                        $(filterSelector).serializeArray().forEach(function (field) {
                            if (field.name && field.value !== '') {
                                d[field.name] = field.value;
                            }
                        });
                    }
                },
                columns: columns.map(function (col) {
                    return {
                        data: col.data,
                        name: col.name || col.data,
                        orderable: col.orderable !== false,
                        searchable: col.searchable !== false,
                        className: col.className || '',
                        render: col.html ? function (data) { return data; } : undefined
                    };
                })
            };
            if (orderableFalse.length) {
                options.columnDefs = [{ targets: orderableFalse, orderable: false, searchable: false }];
            }

            var dt = $table.DataTable(options);
            $table.data('bioDt', dt);

            if (filterSelector) {
                $(filterSelector).on('submit', function (e) {
                    e.preventDefault();
                    dt.ajax.reload();
                });
            }
        });
    }

    function initBioSelect2() {
        if (!$.fn.select2) return;
        $('.bio-select2').each(function () {
            var $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) return;
            $el.select2({
                width: '100%',
                placeholder: $el.data('placeholder') || $el.find('option:first').text() || 'Seleccione',
                allowClear: !!$el.data('allow-clear')
            });
        });
    }

    function bindBioConfirmForms() {
        $(document).on('submit', 'form.bio-confirm-form', function (e) {
            var form = this;
            if (form.dataset.bioConfirmed === '1') return true;
            e.preventDefault();
            var message = form.getAttribute('data-confirm') || '¿Confirmar esta acción?';
            if (typeof Swal === 'undefined') {
                if (window.confirm(message)) {
                    form.dataset.bioConfirmed = '1';
                    form.submit();
                }
                return;
            }
            Swal.fire({
                title: 'Confirmar',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d32f2f',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.dataset.bioConfirmed = '1';
                    form.submit();
                }
            });
        });
    }

    $(function () {
        initBioSelect2();
        initBioDataTables();
        initBioAjaxDataTables();
        bindBioConfirmForms();
    });
})(window.jQuery);
</script>
