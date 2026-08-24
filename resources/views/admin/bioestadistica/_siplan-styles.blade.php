{{-- Estilos SIPLAN-lite para listados/ABM de Bioestadística --}}
<style>
    .bio-siplan .card-header .card-title { margin-bottom: 0.15rem; }
    .bio-siplan .bio-toolbar { gap: 0.5rem; flex-wrap: wrap; }
    .bio-siplan .bio-filters {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        margin-bottom: 1rem;
    }
    .bio-siplan .bio-filters .form-control,
    .bio-siplan .bio-filters .select2-container .select2-selection--single {
        min-height: 38px;
    }
    .bio-siplan table.bio-data-table {
        width: 100% !important;
        border-color: #e2e8f0;
    }
    .bio-siplan table.bio-data-table thead th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        font-size: 0.8rem;
        white-space: nowrap;
        border-bottom-width: 1px;
        vertical-align: middle;
    }
    .bio-siplan table.bio-data-table td {
        vertical-align: middle;
        font-size: 0.875rem;
    }
    .bio-siplan .bio-actions {
        display: inline-flex;
        gap: 0.35rem;
        white-space: nowrap;
    }
    .bio-siplan .bio-actions .btn {
        margin: 0;
        padding: 0.25rem 0.55rem;
        line-height: 1.2;
    }
    .bio-siplan .bio-actions .material-icons {
        font-size: 16px;
        line-height: 1;
        vertical-align: middle;
    }
    .bio-siplan .dataTables_wrapper .dataTables_filter input,
    .bio-siplan .dataTables_wrapper .dataTables_length select {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.25rem 0.5rem;
    }
    .bio-siplan .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0288d1 !important;
        color: #fff !important;
        border-color: #0288d1 !important;
    }
    .bio-siplan .select2-container { width: 100% !important; }
    .bio-breadcrumb .breadcrumb {
        background: transparent;
        padding: 0.35rem 0 0.85rem;
        margin-bottom: 0;
        font-size: 0.85rem;
    }
    .bio-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #94a3b8;
    }
    .bio-breadcrumb .breadcrumb-item a { color: #0288d1; }
    .bio-breadcrumb .breadcrumb-item.active { color: #64748b; }
</style>
