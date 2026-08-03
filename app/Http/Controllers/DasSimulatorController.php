<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Services\DasCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DasSimulatorController extends Controller
{
    public function index(Request $request)
    {
        $atividadeKey = $request->query('atividade', Auth::user()->activity_type ?? 'comercio');
        $faturamentoAnual = (float) str_replace(',', '.', $request->query('faturamento_anual', 0));

        // Sugere o faturamento anual real do usuário nos últimos 12 meses, se ele não informar nada
        if ($faturamentoAnual <= 0) {
            $faturamentoAnual = (float) Revenue::where('user_id', Auth::id())
                ->where('date', '>=', now()->subMonths(12))
                ->sum('value');
        }

        $resultado = DasCalculator::calcular($atividadeKey);
        $percentualUsado = DasCalculator::percentualLimite($faturamentoAnual);
        $dentroLimite = $faturamentoAnual <= DasCalculator::LIMITE_ANUAL_MEI;

        return view('das.index', [
            'atividades' => DasCalculator::ATIVIDADES,
            'atividadeKey' => $atividadeKey,
            'faturamentoAnual' => $faturamentoAnual,
            'resultado' => $resultado,
            'percentualUsado' => $percentualUsado,
            'dentroLimite' => $dentroLimite,
            'limiteAnual' => DasCalculator::LIMITE_ANUAL_MEI,
        ]);
    }
}
