<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal de Auditoría RIISS - Verificación de Seguridad</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #1e293b;
        }
        .pin-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .pin-card-header {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            padding: 2rem 2rem 1.75rem;
            text-align: center;
            position: relative;
        }
        .pin-input-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 1.75rem 0;
        }
        .pin-digit {
            width: 52px;
            height: 64px;
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            border: 2px solid #cbd5e1;
            border-radius: 0.75rem;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pin-digit:focus {
            border-color: #0284c7;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.18);
            outline: none;
            transform: translateY(-2px);
        }
        .btn-access {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            border-radius: 0.85rem;
            padding: 0.9rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            width: 100%;
            box-shadow: 0 4px 14px 0 rgba(2, 132, 199, 0.39);
            transition: all 0.2s;
        }
        .btn-access:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(2, 132, 199, 0.45);
            color: white;
        }
        .btn-access:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="pin-card">
    <div class="pin-card-header">
        <div style="font-size: 0.82rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #bae6fd; margin-bottom: 0.35rem;">
            <i class="fas fa-shield-alt mr-1"></i> Acceso Seguro de Auditoría
        </div>
        <h4 style="font-weight: 800; margin-bottom: 0.25rem;">Portal de Verificación RIISS</h4>
        <div style="font-size: 0.88rem; opacity: 0.9;">
            Instituto de Previsión Social (IPS)
        </div>
    </div>

    <div class="p-4 p-md-5">
        @if($est)
            <div class="p-3 mb-4 rounded-lg" style="background-color: #f1f5f9; border-left: 4px solid #0284c7;">
                <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #64748b;">Establecimiento a Auditar</div>
                <div style="font-weight: 700; color: #0f172a; font-size: 1rem; line-height: 1.3;">{{ $est->nombre_oficial }}</div>
                <div style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $est->departamento ?? 'Paraguay' }} • <span class="badge badge-info">{{ $est->complejidad ?? 'RIISS' }}</span>
                </div>
            </div>
        @endif

        <div class="text-center">
            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 0.35rem;">Ingrese el Código PIN</h5>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                Por favor ingrese el código de 6 dígitos que le fue proporcionado por WhatsApp o correo para validar su acceso.
            </p>
        </div>

        <div id="pinAlert" class="alert alert-danger py-2 px-3 small" style="display: none; border-radius: 8px;"></div>

        <form id="formPinAuth">
            <div class="pin-input-group">
                <input type="text" maxlength="1" class="pin-digit" inputmode="numeric" pattern="[0-9]*" autofocus>
                <input type="text" maxlength="1" class="pin-digit" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="pin-digit" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="pin-digit" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="pin-digit" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="pin-digit" inputmode="numeric" pattern="[0-9]*">
            </div>
            <input type="hidden" id="fullPin" name="pin">

            <button type="submit" id="btnVerificarPin" class="btn btn-access mt-2" disabled>
                <span id="btnText"><i class="fas fa-unlock-alt mr-2"></i> Ingresar al Portal</span>
                <span id="btnSpinner" style="display: none;"><i class="fas fa-spinner fa-spin mr-2"></i> Validando...</span>
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center" style="font-size: 0.78rem; color: #94a3b8;">
            <i class="fas fa-clock mr-1"></i> Enlace con validez temporal ({{ $tokenRecord->duracion_horas }} horas).<br>
            Expira el: <strong>{{ $tokenRecord->expira_en->format('d/m/Y H:i') }} hs</strong>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    var $inputs = $('.pin-digit');
    var $btn = $('#btnVerificarPin');
    var $fullPin = $('#fullPin');
    var token = '{{ $token }}';

    function updatePinValue() {
        var pin = '';
        $inputs.each(function() {
            pin += $(this).val();
        });
        $fullPin.val(pin);
        $btn.prop('disabled', pin.length !== 6);
    }

    $inputs.on('input', function(e) {
        var $this = $(this);
        var val = $this.val().replace(/[^0-9]/g, '');
        $this.val(val);

        if (val.length === 1) {
            $this.next('.pin-digit').focus();
        }
        updatePinValue();
    });

    $inputs.on('keydown', function(e) {
        var $this = $(this);
        if (e.key === 'Backspace' && !$this.val()) {
            $this.prev('.pin-digit').focus();
        }
    });

    // Soporte para pegar código completo (Paste)
    $inputs.first().on('paste', function(e) {
        var pasted = (e.originalEvent.clipboardData || window.clipboardData).getData('text').trim();
        if (/^\d{6}$/.test(pasted)) {
            e.preventDefault();
            for (var i = 0; i < 6; i++) {
                $inputs.eq(i).val(pasted[i]);
            }
            $inputs.last().focus();
            updatePinValue();
            $('#formPinAuth').submit();
        }
    });

    $('#formPinAuth').on('submit', function(e) {
        e.preventDefault();
        var pin = $fullPin.val();
        if (pin.length !== 6) return;

        $('#pinAlert').hide();
        $btn.prop('disabled', true);
        $('#btnText').hide();
        $('#btnSpinner').show();

        $.ajax({
            url: '/riiss/portal-auditor/' + token + '/verificar-pin',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                pin: pin
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
                var msg = 'El código PIN es incorrecto.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $('#pinAlert').text(msg).fadeIn();
                $inputs.val('');
                $inputs.first().focus();
                updatePinValue();
            }
        });
    });
});
</script>

</body>
</html>
