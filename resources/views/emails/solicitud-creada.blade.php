<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud registrada</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #0f172a;">Su solicitud fue registrada correctamente</h1>

    <p>Estimado/a {{ $solicitud->vecino->name ?? 'vecino/vecina' }},</p>

    <p>Hemos recibido su solicitud en el Portal Ciudadano de la Municipalidad de Chanco. A continuación encontrará el detalle de seguimiento:</p>

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
            <td style="padding: 8px 0; font-weight: bold;">Estado:</td>
            <td style="padding: 8px 0;">Enviada / En revisión</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-weight: bold;">Fecha:</td>
            <td style="padding: 8px 0;">{{ $solicitud->created_at?->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <p>Conserve el folio <strong>{{ $solicitud->folio }}</strong> para futuras consultas. Le notificaremos por correo cuando su solicitud sea respondida.</p>

    <p style="margin-top: 32px; font-size: 13px; color: #64748b;">
        Ilustre Municipalidad de Chanco · Portal Ciudadano<br>
        Este es un mensaje automático, por favor no responda a este correo.
    </p>
</body>
</html>
