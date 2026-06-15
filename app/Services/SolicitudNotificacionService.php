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
            Log::warning('Sin correo para notificación de solicitud creada', [
                'folio' => $solicitud->folio,
                'vecino_id' => $solicitud->vecino_id,
            ]);

            return;
        }

        Mail::to($email)->send(new SolicitudCreadaMail($solicitud));

        Log::info('Correo de solicitud creada enviado', [
            'folio' => $solicitud->folio,
            'destino' => $email,
        ]);
    }

    public static function enviarNotificacionRespuesta(Solicitud $solicitud): void
    {
        $solicitud->loadMissing(['tipo', 'vecino']);
        $email = $solicitud->emailNotificacionVecino();

        if (! $email) {
            Log::warning('Sin correo para notificación de solicitud respondida', [
                'folio' => $solicitud->folio,
                'vecino_id' => $solicitud->vecino_id,
            ]);

            return;
        }

        Mail::to($email)->send(new SolicitudRespondidaMail($solicitud));

        Log::info('Correo de solicitud respondida enviado', [
            'folio' => $solicitud->folio,
            'destino' => $email,
        ]);
    }

    /** Envía correo sin interrumpir el flujo principal si falla el SMTP. */
    public static function enviarConfirmacionCreacionSeguro(Solicitud $solicitud): void
    {
        try {
            self::enviarConfirmacionCreacion($solicitud);
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar correo de solicitud creada', [
                'folio' => $solicitud->folio,
                'destino' => $solicitud->emailNotificacionVecino(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public static function enviarNotificacionRespuestaSeguro(Solicitud $solicitud): void
    {
        try {
            self::enviarNotificacionRespuesta($solicitud);
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar correo de solicitud respondida', [
                'folio' => $solicitud->folio,
                'destino' => $solicitud->emailNotificacionVecino(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
