<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetarLimiteAnualMei extends Command
{
    protected $signature = 'mei:resetar-limite-anual';

    protected $description = 'Reseta o controle de alertas de limite anual do MEI (executar todo 1º de janeiro)';

    public function handle(): int
    {
        $total = User::query()->whereNotNull('limite_alerta_percentual')->update([
            'limite_alerta_percentual' => null,
        ]);

        $this->info("Alertas de limite anual resetados para {$total} usuário(s).");

        return self::SUCCESS;
    }
}
