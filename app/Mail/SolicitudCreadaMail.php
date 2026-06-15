<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Solicitud $solicitud) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de solicitud ' . $this->solicitud->folio . ' - Portal Ciudadano',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-creada',
        );
    }
}
