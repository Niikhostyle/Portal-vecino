<?php

namespace App\Services;

use App\Mail\SolicitudCreadaMail;
use App\Mail\SolicitudRespondidaMail;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SolicitudNotificacionService
{
    public static function enviarConfirmacionCreacion(Solicitud $solicitud): void
    {
        $solicitud->loadMissing(['tipo', 'vecino']);
        $email = $solicitud->emailNotificacionVecino();

        if (! $email) {
            Log::info('Sin correo para notificación de solicitud creada', ['folio' => $solicitud->folio]);

            return;
        }

        Mail::to($email)->send(new SolicitudCreadaMail($solicitud));
    }

    public static function enviarNotificacionRespuesta(Solicitud $solicitud): void
    {
        $solicitud->loadMissing(['tipo', 'vecino']);
        $email = $solicitud->emailNotificacionVecino();

        if (! $email) {
            Log::info('Sin correo para notificación de solicitud respondida', ['folio' => $solicitud->folio]);

            return;
        }

        Mail::to($email)->send(new SolicitudRespondidaMail($solicitud));
    }

    /** Envía correo sin interrumpir el flujo principal si falla el SMTP. */
    public static function enviarConfirmacionCreacionSeguro(Solicitud $solicitud): void
    {
        try {
            self::enviarConfirmacionCreacion($solicitud);
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar correo de solicitud creada', [
                'folio' => $solicitud->folio,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public static function enviarNotificacionRespuestaSeguro(Solicitud $solicitud): void
    {
        try {
            self::enviarNotificacionRespuesta($solicitud);
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar correo de solicitud respondida', [
                'folio' => $solicitud->folio,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
