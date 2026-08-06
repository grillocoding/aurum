<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DasVencimentoNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly float $valorDas,
        private readonly string $atividadeLabel,
        private readonly int $diasRestantes,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $assunto = $this->diasRestantes === 0
            ? 'AURUM — Seu DAS vence hoje'
            : "AURUM — Seu DAS vence em {$this->diasRestantes} dia(s)";

        return (new MailMessage)
            ->subject($assunto)
            ->view('emails.das-vencimento', [
                'nome' => $notifiable->name,
                'valorDas' => $this->valorDas,
                'atividadeLabel' => $this->atividadeLabel,
                'diasRestantes' => $this->diasRestantes,
                'dasUrl' => route('das.index'),
            ]);
    }
}
