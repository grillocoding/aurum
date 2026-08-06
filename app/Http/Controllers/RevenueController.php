<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\User;
use App\Notifications\LimiteAnualAtingidoNotification;
use App\Services\DasCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = Revenue::where('user_id', Auth::id())
            ->orderByDesc('date')
            ->get();

        $total = $revenues->sum('value');

        return view('revenues.index', [
            'revenues' => $revenues,
            'total' => $total,
            'categories' => Revenue::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'category' => ['required', 'string', 'in:'.implode(',', Revenue::CATEGORIES)],
        ]);

        Revenue::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        $this->verificarLimiteAnual(Auth::user());

        return redirect()->route('revenues.index')->with('success', 'Receita adicionada com sucesso!');
    }

    public function destroy(Revenue $revenue)
    {
        abort_unless($revenue->user_id === Auth::id(), 403);

        $revenue->delete();

        return redirect()->route('revenues.index')->with('success', 'Receita removida.');
    }

    /**
     * Verifica se o faturamento dos últimos 12 meses cruzou um novo limiar
     * (80%, 90% ou 100%) do limite anual do MEI e, se sim, dispara o e-mail
     * de alerta apenas uma vez por limiar (controlado por
     * `limite_alerta_percentual`, resetado todo 1º de janeiro).
     */
    private function verificarLimiteAnual(User $user): void
    {
        $faturamentoAnual = (float) Revenue::where('user_id', $user->id)
            ->where('date', '>=', now()->subMonths(12))
            ->sum('value');

        $limiteAnual = DasCalculator::LIMITE_ANUAL_MEI;
        $percentual = DasCalculator::percentualLimite($faturamentoAnual);

        $novoLimiar = match (true) {
            $percentual >= 100 => 100,
            $percentual >= 90 => 90,
            $percentual >= 80 => 80,
            default => null,
        };

        $limiarAtual = (int) ($user->limite_alerta_percentual ?? 0);

        if ($novoLimiar !== null && $novoLimiar > $limiarAtual) {
            $user->notify(new LimiteAnualAtingidoNotification($novoLimiar, $faturamentoAnual, $limiteAnual));
            $user->update(['limite_alerta_percentual' => $novoLimiar]);
        }
    }
}
