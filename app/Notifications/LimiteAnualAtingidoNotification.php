<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LimiteAnualAtingidoNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $percentual,
        private readonly float $faturamentoAnual,
        private readonly float $limiteAnual,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $restante = max($this->limiteAnual - $this->faturamentoAnual, 0);

        return (new MailMessage)
            ->subject("AURUM — Você atingiu {$this->percentual}% do limite anual do MEI")
            ->view('emails.limite-anual', [
                'nome' => $notifiable->name,
                'percentual' => $this->percentual,
                'faturamentoAnual' => $this->faturamentoAnual,
                'limiteAnual' => $this->limiteAnual,
                'restante' => $restante,
                'dashboardUrl' => route('dashboard'),
            ]);
    }
}
