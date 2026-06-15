<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestCorreoCommand extends Command
{
    protected $signature = 'portal:test-correo {email : Correo destino}';

    protected $description = 'Envía un correo de prueba SMTP del Portal Ciudadano';

    public function handle(): int
    {
        $destino = $this->argument('email');

        if (! filter_var($destino, FILTER_VALIDATE_EMAIL)) {
            $this->error('Correo inválido: ' . $destino);

            return self::FAILURE;
        }

        $host = config('mail.mailers.smtp.host');
        $from = config('mail.from.address');

        $this->info("SMTP: {$host}");
        $this->info("From: {$from}");
        $this->info("To: {$destino}");

        try {
            Mail::raw(
                'Prueba SMTP Portal Ciudadano — ' . now()->format('d/m/Y H:i:s'),
                fn ($message) => $message->to($destino)->subject('Prueba correo Portal Ciudadano')
            );

            $this->info('Correo enviado correctamente.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Error al enviar: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
