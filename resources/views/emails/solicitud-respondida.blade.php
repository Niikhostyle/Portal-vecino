<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud respondida</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #0f172a;">Su solicitud ha sido respondida</h1>

    <p>Estimado/a {{ $solicitud->vecino->name ?? 'vecino/vecina' }},</p>

    <p>Le informamos que su solicitud en el Portal Ciudadano de la Municipalidad de Chanco ha recibido una respuesta oficial.</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr>
            <td style="padding: 8px 0; font-weight: bold; width: 140px;">Folio:</td>
            <td style="padding: 8px 0;">{{ $solicitud->folio }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-weight: bold;">Trámite:</td>
            <td style="padding: 8px 0;">{{ $solicitud->tipo->titulo ?? '—' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-weight: bold;">Fecha respuesta:</td>
            <td style="padding: 8px 0;">{{ $solicitud->fecha_respuesta?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    @if($solicitud->respuesta)
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin: 16px 0;">
        <p style="margin: 0 0 8px; font-weight: bold; font-size: 14px;">Respuesta:</p>
        <p style="margin: 0; white-space: pre-wrap;">{{ $solicitud->respuesta }}</p>
    </div>
    @endif

    <p>Puede revisar el detalle completo ingresando al Portal Ciudadano con su ClaveÚnica.</p>

    <p style="margin-top: 32px; font-size: 13px; color: #64748b;">
        Ilustre Municipalidad de Chanco · Portal Ciudadano<br>
        Este es un mensaje automático, por favor no responda a este correo.
    </p>
</body>
</html>
