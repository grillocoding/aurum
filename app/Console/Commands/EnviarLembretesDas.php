<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DasVencimentoNotification;
use App\Services\DasCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class EnviarLembretesDas extends Command
{
    protected $signature = 'das:enviar-lembretes';

    protected $description = 'Envia lembretes por e-mail sobre o vencimento do DAS (dia 20 de cada mês)';

    // Dias antes do vencimento em que um lembrete é disparado (0 = no próprio dia 20)
    private const DIAS_LEMBRETE = [3, 0];

    private const DIA_VENCIMENTO = 20;

    public function handle(): int
    {
        $hoje = Carbon::now()->day;
        $diasRestantes = self::DIA_VENCIMENTO - $hoje;

        if (! in_array($diasRestantes, self::DIAS_LEMBRETE, true)) {
            $this->info('Hoje não é dia de lembrete de DAS. Nenhum e-mail enviado.');

            return self::SUCCESS;
        }

        $totalEnviados = 0;

        User::query()->chunkById(100, function ($users) use ($diasRestantes, &$totalEnviados) {
            foreach ($users as $user) {
                $das = DasCalculator::calcular($user->activity_type ?? 'comercio');

                $user->notify(new DasVencimentoNotification(
                    $das['total'],
                    $das['atividade']['label'],
                    $diasRestantes,
                ));

                $totalEnviados++;
            }
        });

        $this->info("Lembretes de DAS enviados para {$totalEnviados} usuário(s).");

        return self::SUCCESS;
    }
}
