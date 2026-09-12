<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal de Regulación Farmacéutica RIISS — IPS Paraguay</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --brand-primary: #0d9488;
            --brand-dark: #0f172a;
            --brand-navy: #1e293b;
            --brand-accent: #14b8a6;
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

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.2) 0%, rgba(13, 148, 136, 0) 70%);
            top: -100px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
        }

        .auth-container {
            position: relative;
            z-index: 1;
            max-width: 540px;
            width: 100%;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .auth-header {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 60%, #115e59 100%);
            color: white;
            padding: 2.25rem 2rem 1.75rem;
            text-align: center;
            position: relative;
        }

        .auth-icon-badge {
            width: 68px;
            height: 68px;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .pin-input {
            letter-spacing: 6px;
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            border: 2px solid #cbd5e1;
            border-radius: 0.85rem;
            padding: 0.85rem;
            transition: all 0.2s ease;
        }

        .pin-input:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.18);
            outline: none;
        }

        .btn-auth {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: white;
            font-weight: 700;
            border-radius: 0.85rem;
            padding: 0.95rem;
            border: none;
            font-size: 1rem;
            box-shadow: 0 10px 20px -5px rgba(13, 148, 136, 0.4);
            transition: all 0.2s ease;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -5px rgba(13, 148, 136, 0.5);
            color: white;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon-badge">
                <i class="fa fa-pills text-white"></i>
            </div>
            <h4 class="font-weight-bold mb-1" style="font-size: 1.35rem;">Portal de Regulación Farmacéutica</h4>
            <p class="mb-0 text-white-50 small">
                Validación y Homologación de Medicamentos por Especialidad (Vademécum Oficial IPS)
            </p>
        </div>

        <div class="p-4 p-md-5">
            {{-- Datos del Analista --}}
            <div class="p-3 mb-4 rounded-lg bg-light border">
                <div class="d-flex align-items-center">
                    <div class="bg-teal text-white p-2 rounded-circle mr-3" style="background-color: #0d9488; width: 42px; height: 42px; display:flex; align-items:center; justify-content:center;">
                        <i class="fa fa-user-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 11px;">Profesional Asignado</div>
                        <div class="font-weight-bold text-dark" style="font-size: 1.05rem;">{{ $sesion->analista_nombre }}</div>
                        <small class="text-muted font-weight-500">{{ $sesion->analista_cargo ?? 'Unidad de Regulación Farmacéutica' }}</small>
                    </div>
                </div>
            </div>

            <form id="formVerificarPin">
                @csrf
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark small text-uppercase">
                        <i class="fa fa-key mr-1 text-teal" style="color: #0d9488;"></i> Ingrese su Código de Acceso (PIN)
                    </label>
                    <input type="text" 
                           id="inputCodigoPin" 
                           class="form-control pin-input" 
                           placeholder="VAL-FARM-XXXX" 
                           required 
                           autocomplete="off" 
                           autofocus>
                    <small class="text-muted text-center d-block mt-2">
                        Código alfanumérico provisto por la Dirección de Planificación.
                    </small>
                </div>

                <div id="alertError" class="alert alert-danger d-none py-2 small font-weight-bold mb-3" role="alert">
                    <i class="fa fa-exclamation-triangle mr-1"></i> <span id="errorText"></span>
                </div>

                <button type="submit" id="btnSubmitPin" class="btn btn-auth btn-block">
                    <i class="fa fa-sign-in-alt mr-2"></i> Ingresar al Portal Farmacéutico
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center">
                <small class="text-muted font-weight-bold" style="font-size: 11.5px;">
                    <i class="fa fa-shield-alt text-teal mr-1" style="color: #0d9488;"></i> 
                    Instituto de Previsión Social (IPS) — Redes Integradas e Integrales de Servicios de Salud (RIISS)
                </small>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#formVerificarPin').on('submit', function(e) {
        e.preventDefault();
        const codigo = $('#inputCodigoPin').val().trim();
        if (!codigo) return;

        const $btn = $('#btnSubmitPin');
        const $alert = $('#alertError');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i> Verificando...');
        $alert.addClass('d-none');

        $.ajax({
            url: '{{ route("riiss.portal-farmaceutico.verificar", $sesion->token) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codigo: codigo
            },
            success: function(res) {
                if (res.ok) {
                    window.location.href = res.redirect;
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-sign-in-alt mr-2"></i> Ingresar al Portal Farmacéutico');
                const msg = xhr.responseJSON?.message || 'Código incorrecto. Por favor verifique.';
                $('#errorText').text(msg);
                $alert.removeClass('d-none');
            }
        });
    });
});
</script>

</body>
</html>
