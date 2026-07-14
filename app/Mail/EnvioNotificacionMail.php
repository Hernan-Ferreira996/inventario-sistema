<?php

namespace App\Mail;

use App\Models\Envio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnvioNotificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Envio $envio)
    {
    }

    public function build()
    {
        $estados = [
            'preparando' => 'está siendo preparado',
            'enviado'    => 'fue despachado',
            'entregado'  => 'fue entregado',
            'devuelto'   => 'fue devuelto',
        ];

        $asunto = "Envío {$this->envio->numero_envio} - " . ucfirst($estados[$this->envio->estado] ?? $this->envio->estado);

        return $this->subject($asunto)
            ->view('emails.envio');
    }
}
