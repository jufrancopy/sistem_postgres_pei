@extends('layouts.master')
@section('title', 'Variables Globales del Sistema')

@section('content')
<div class="container-fluid py-3">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white shadow-xs rounded px-3 py-2">
            <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Globales</a></li>
            <li class="breadcrumb-item active" aria-current="page">Variables Globales del Sistema</li>
        </ol>
    </nav>

    {{-- Encabezado --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap mb-4 pb-2 border-bottom">
        <div>
            <h3 class="font-weight-bold text-dark mb-1">
                <i class="fa fa-cog text-warning mr-2"></i>Variables Globales del Sistema
            </h3>
            <p class="text-muted small mb-0">
                Configuración centralizada de la identidad oficial, contacto, horarios, logo y pie de página institucional.
            </p>
        </div>
        <div>
            <a href="{{ route('globales.dashboard') }}" class="btn btn-secondary btn-sm px-3" style="border-radius: 20px;">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded mb-4" role="alert" style="background:#dcfce7; color:#14532d;">
        <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <form action="{{ route('globales.configuracion-sistema.update') }}" method="POST">
        @csrf
        
        <div class="row">
            {{-- Columna Izquierda --}}
            <div class="col-lg-7">
                
                {{-- 1. Identidad Institucional --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <span class="font-weight-bold text-uppercase text-dark" style="font-size: 0.82rem; letter-spacing: 0.05em;">
                            <i class="fa fa-globe text-info mr-2"></i>Identidad Oficial & Nombre del Sitio
                        </span>
                        <span class="badge badge-info px-2 py-0.5" style="font-size: 0.65rem; border-radius: 10px;">Identidad</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Nombre Oficial del Sitio / Aplicación <span class="text-danger">*</span></label>
                            <input type="text" name="site_name" class="form-control form-control-alternative" 
                                   value="{{ old('site_name', $config->site_name ?? 'Sistema PEI & Gestión Estratégica — IPS') }}" 
                                   placeholder="ej. Sistema PEI & Gestión Estratégica — IPS" required>
                            <small class="text-muted">Aparece en el título del navegador, membretes y reportes institucionales.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark small mb-1">URL del Logo Institucional Oficial</label>
                            <input type="text" name="logo_url" id="input_logo_url" class="form-control form-control-alternative" 
                                   value="{{ old('logo_url', $config->logo_url) }}" 
                                   placeholder="https://... o /img/logo-ips.png">
                            <small class="text-muted">Ruta o enlace a la imagen del logo en PNG/SVG para encabezados de PDF e informes.</small>
                        </div>

                        @if($config->logo_url)
                        <div class="mt-3 p-3 bg-light rounded text-center border">
                            <small class="text-muted d-block mb-2 font-weight-bold">Vista Previa del Logo Actual:</small>
                            <img src="{{ $config->logo_url }}" alt="Logo Institucional" style="max-height: 70px; object-fit: contain;">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- 2. Contacto & Atención --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <span class="font-weight-bold text-uppercase text-dark" style="font-size: 0.82rem; letter-spacing: 0.05em;">
                            <i class="fa fa-phone-alt text-success mr-2"></i>Contacto Institucional & Horarios
                        </span>
                        <span class="badge badge-success px-2 py-0.5" style="font-size: 0.65rem; border-radius: 10px;">Contacto</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small mb-1">Correo Electrónico Oficial</label>
                                    <input type="email" name="contact_email" class="form-control form-control-alternative" 
                                           value="{{ old('contact_email', $config->contact_email ?? 'planificacion@ips.gov.py') }}" 
                                           placeholder="planificacion@ips.gov.py">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small mb-1">Teléfono de Contacto</label>
                                    <input type="text" name="contact_phone" class="form-control form-control-alternative" 
                                           value="{{ old('contact_phone', $config->contact_phone ?? '+595 21 219 7000') }}" 
                                           placeholder="+595 21 219 7000">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark small mb-1">Horarios de Atención</label>
                            <input type="text" name="opening_hours" class="form-control form-control-alternative" 
                                   value="{{ old('opening_hours', $config->opening_hours ?? 'Lunes a Viernes de 07:00 a 15:00 hs') }}" 
                                   placeholder="Lunes a Viernes de 07:00 a 15:00 hs">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Columna Derecha --}}
            <div class="col-lg-5">
                
                {{-- 3. Ubicación & Pie de Página --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <span class="font-weight-bold text-uppercase text-dark" style="font-size: 0.82rem; letter-spacing: 0.05em;">
                            <i class="fa fa-map-marker-alt text-warning mr-2"></i>Ubicación & Pie de Página
                        </span>
                        <span class="badge badge-warning text-dark px-2 py-0.5" style="font-size: 0.65rem; border-radius: 10px;">Pie de Página</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Dirección Institucional</label>
                            <textarea name="address" class="form-control form-control-alternative" rows="2" 
                                      placeholder="Constitución e/ Herrera y Pettirossi, Asunción - Paraguay">{{ old('address', $config->address ?? 'Constitución e/ Herrera y Pettirossi, Asunción - Paraguay') }}</textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark small mb-1">Texto del Pie de Página (Copyright)</label>
                            <textarea name="footer_text" class="form-control form-control-alternative" rows="3" 
                                      placeholder="© 2026 Instituto de Previsión Social (IPS)...">{{ old('footer_text', $config->footer_text ?? '© 2026 Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Guardar Cambios --}}
                <div class="card border-0 shadow-sm mb-4 bg-white p-4 text-center" style="border-radius: 14px;">
                    <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow-xs py-2.5" style="border-radius: 10px;">
                        <i class="fa fa-save mr-2"></i>Guardar Variables Globales
                    </button>
                </div>

                {{-- 4. Guía de Referencia en Código --}}
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff;">
                    <div class="card-body p-4">
                        <small class="text-uppercase font-weight-bold text-warning d-block mb-2" style="letter-spacing: .06em; font-size: .7rem;">
                            <i class="fa fa-code mr-1"></i> Referencia para Desarrolladores & Plantillas
                        </small>
                        <p class="small text-white-50 mb-3" style="line-height: 1.45;">
                            Podés acceder a cualquiera de estas variables en cualquier vista Blade con el helper:
                        </p>
                        <div class="bg-black-50 p-2.5 rounded border border-secondary font-mono small text-warning" style="font-family: monospace; font-size: .78rem;">
                            \App\Models\HomeConfiguration::getSetting('site_name')<br>
                            \App\Models\HomeConfiguration::getSetting('contact_email')<br>
                            \App\Models\HomeConfiguration::getSetting('logo_url')
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection
