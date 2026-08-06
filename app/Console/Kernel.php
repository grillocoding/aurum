<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Verifica diariamente se hoje é dia de lembrete do DAS (3 dias antes e no dia 20)
        $schedule->command('das:enviar-lembretes')->dailyAt('08:00');

        // Reseta o controle de alertas de limite anual do MEI todo 1º de janeiro
        $schedule->command('mei:resetar-limite-anual')->yearlyOn(1, 1, '00:30');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
