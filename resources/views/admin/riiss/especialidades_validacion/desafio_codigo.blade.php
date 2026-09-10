<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal de Validación RIISS — Verificación de Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --brand-primary: #0284c7;
            --brand-dark: #0f172a;
            --brand-navy: #1e293b;
            --brand-accent: #38bdf8;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #090e17 0%, #0f172a 40%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient background glow */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.15) 0%, rgba(2, 132, 199, 0) 70%);
            top: -100px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.1) 0%, rgba(56, 189, 248, 0) 70%);
            bottom: -80px;
            left: -80px;
            z-index: 0;
            pointer-events: none;
        }

        .auth-container {
            position: relative;
            z-index: 1;
            max-width: 520px;
            width: 100%;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: transform 0.3s ease;
        }

        .auth-header {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
            color: white;
            padding: 2.25rem 2rem 1.75rem;
            text-align: center;
            position: relative;
        }

        .logo-box {
            background: white;
            padding: 8px 16px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            margin-bottom: 1.25rem;
            max-height: 55px;
        }

        .logo-box img {
            max-height: 38px;
            max-width: 150px;
            object-fit: contain;
        }

        .validador-badge-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--brand-primary);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .code-input-container {
            position: relative;
            margin: 1.5rem 0 1rem;
        }

        .code-input {
            width: 100%;
            height: 64px;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-align: center;
            text-transform: uppercase;
            border: 2px solid #cbd5e1;
            border-radius: 1rem;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0 1rem;
            font-family: 'Courier New', Courier, monospace;
        }

        .code-input:focus {
            border-color: var(--brand-primary);
            background: #ffffff;
            box-shadow: 0 0 0 5px rgba(2, 132, 199, 0.2);
            outline: none;
            transform: translateY(-2px);
        }

        .code-input::placeholder {
            color: #94a3b8;
            font-size: 1.25rem;
            letter-spacing: 0.15em;
            font-weight: 600;
        }

        .btn-access {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            border-radius: 0.95rem;
            padding: 1rem 1.75rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
            width: 100%;
            box-shadow: 0 8px 20px 0 rgba(2, 132, 199, 0.35);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-access:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px 0 rgba(2, 132, 199, 0.45);
            color: white;
        }

        .btn-access:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .info-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 700;
            background: rgba(2, 132, 199, 0.1);
            color: #0284c7;
        }

        .footer-note {
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
            margin-top: 1.25rem;
            line-height: 1.4;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        {{-- Encabezado Institucional --}}
        <div class="auth-header">
            @if(!empty($logoInstitucional) || !empty($sysLogoUrl))
                <div class="logo-box">
                    <img src="{{ $logoInstitucional ?: $sysLogoUrl }}" alt="Logo Oficial">
                </div>
            @endif

            <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #bae6fd; margin-bottom: 0.3rem;">
                <i class="fas fa-shield-alt mr-1"></i> Autenticación de Validador Oficial
            </div>
            
            <h4 style="font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                Portal de Validación de Especialidades
            </h4>

            <div style="font-size: 0.86rem; color: rgba(255,255,255,0.92);">
                {{ $dependencia ?? 'Dirección de Hospitales' }}
            </div>
            <div style="font-size: 0.78rem; color: #bae6fd; margin-top: 2px;">
                {{ $institucion ?? 'Instituto de Previsión Social (IPS)' }}
            </div>
        </div>

        {{-- Cuerpo de la Tarjeta --}}
        <div class="p-4 p-md-5">
            {{-- Datos del Validador Asignado --}}
            <div class="validador-badge-box">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #64748b; letter-spacing: 0.05em;">
                        <i class="fas fa-user-check mr-1 text-primary"></i> Validador Asignado
                    </span>
                    <span class="info-pill">
                        <i class="fas fa-network-wired mr-1"></i> {{ $sesion->area_gestion ?? 'ÁREA INTERIOR' }}
                    </span>
                </div>

                <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem; line-height: 1.25;">
                    {{ $sesion->analista_nombre }}
                </div>

                <div style="font-size: 0.82rem; color: #64748b; margin-top: 3px;">
                    {{ $sesion->analista_cargo ?? 'Analista Técnico Validador' }}
                    @if($sesion->departamento_filtro)
                        · <i class="fas fa-map-marker-alt text-danger ml-1"></i> <strong>{{ $sesion->departamento_filtro }}</strong>
                    @endif
                </div>
            </div>

            {{-- Instrucción --}}
            <div class="text-center">
                <h5 style="font-weight: 800; color: #1e293b; margin-bottom: 0.35rem; font-size: 1.15rem;">
                    Ingrese su Código de Acceso
                </h5>
                <p style="font-size: 0.86rem; color: #64748b; margin-bottom: 1.25rem;">
                    Por favor ingrese el código de seguridad (ej: <strong>{{ $sesion->codigo_acceso }}</strong>) que le fue remitido vía WhatsApp o correo oficial para desbloquear su sesión.
                </p>
            </div>

            {{-- Alerta de Error --}}
            <div id="codigoAlert" class="alert alert-danger py-2 px-3 small font-weight-bold" style="display: none; border-radius: 10px; border: 1px solid #f87171;"></div>

            {{-- Formulario de Código --}}
            <form id="formCodigoAuth" autocomplete="off">
                <div class="code-input-container">
                    <input type="text" 
                           id="codigoInput" 
                           name="codigo" 
                           class="code-input" 
                           placeholder="VAL-XXXXXX" 
                           maxlength="20" 
                           autofocus 
                           required>
                </div>

                <button type="submit" id="btnVerificarCodigo" class="btn btn-access mt-3" disabled>
                    <span id="btnText"><i class="fas fa-unlock-alt mr-1"></i> Ingresar al Portal</span>
                    <span id="btnSpinner" style="display: none;"><i class="fas fa-spinner fa-spin mr-1"></i> Verificando Acceso...</span>
                </button>
            </form>

            {{-- Footer Institucional --}}
            <div class="footer-note pt-3 mt-4 border-top">
                <div class="mb-1" style="font-weight: 600; color: #475569;">
                    <i class="fas fa-balance-scale text-info mr-1"></i> En el marco de la <strong>Política Nacional RIISS</strong>
                </div>
                <div>
                    Acceso protegido y confidencial • Instituto de Previsión Social (IPS)
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    var $input = $('#codigoInput');
    var $btn = $('#btnVerificarCodigo');
    var $alert = $('#codigoAlert');
    var token = '{{ $token }}';

    function checkInput() {
        var val = $.trim($input.val());
        $btn.prop('disabled', val.length < 3);
    }

    $input.on('input', function() {
        var val = $(this).val().toUpperCase();
        $(this).val(val);
        $alert.hide();
        checkInput();
    });

    // Soporte para pegar código completo
    $input.on('paste', function() {
        setTimeout(function() {
            var val = $input.val().toUpperCase();
            $input.val(val);
            checkInput();
        }, 50);
    });

    $('#formCodigoAuth').on('submit', function(e) {
        e.preventDefault();
        var codigo = $.trim($input.val().toUpperCase());
        if (codigo.length < 3) return;

        $alert.hide();
        $btn.prop('disabled', true);
        $('#btnText').hide();
        $('#btnSpinner').show();

        $.ajax({
            url: '/riiss/portal-validador/' + token + '/verificar-codigo',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                codigo: codigo
            },
            success: function(resp) {
                if (resp.ok && resp.redirect) {
                    window.location.href = resp.redirect;
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false);
                $('#btnText').show();
                $('#btnSpinner').hide();
                
                var msg = 'El Código de Acceso es incorrecto. Verifique e intente nuevamente.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $alert.text(msg).fadeIn();
                $input.select().focus();
            }
        });
    });
});
</script>

</body>
</html>
