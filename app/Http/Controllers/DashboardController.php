<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use App\Services\DasCalculator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Últimos 6 meses (incluindo o atual), do mais antigo para o mais recente
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->startOfMonth();
        });

        $revenueByMonth = [];
        $expenseByMonth = [];

        foreach ($months as $month) {
            $label = ucfirst($month->translatedFormat('M'));

            $revenueByMonth[] = [
                'month' => $label,
                'value' => (float) Revenue::where('user_id', $user->id)
                    ->whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->sum('value'),
            ];

            $expenseByMonth[] = [
                'month' => $label,
                'value' => (float) Expense::where('user_id', $user->id)
                    ->whereYear('date', $month->year)
                    ->whereMonth('date', $month->month)
                    ->sum('value'),
            ];
        }

        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonthNoOverflow()->startOfMonth();

        $currentRevenue = (float) Revenue::where('user_id', $user->id)
            ->whereYear('date', $currentMonth->year)->whereMonth('date', $currentMonth->month)->sum('value');
        $previousRevenue = (float) Revenue::where('user_id', $user->id)
            ->whereYear('date', $previousMonth->year)->whereMonth('date', $previousMonth->month)->sum('value');

        $currentExpenses = (float) Expense::where('user_id', $user->id)
            ->whereYear('date', $currentMonth->year)->whereMonth('date', $currentMonth->month)->sum('value');
        $previousExpenses = (float) Expense::where('user_id', $user->id)
            ->whereYear('date', $previousMonth->year)->whereMonth('date', $previousMonth->month)->sum('value');

        $balance = $currentRevenue - $currentExpenses;
        $previousBalance = $previousRevenue - $previousExpenses;

        $revenueChange = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
        $expenseChange = $previousExpenses > 0 ? (($currentExpenses - $previousExpenses) / $previousExpenses) * 100 : 0;
        $balanceChange = $previousBalance != 0 ? (($balance - $previousBalance) / abs($previousBalance)) * 100 : 0;

        $das = DasCalculator::calcular($user->activity_type ?? 'comercio');

        // Faturamento acumulado nos últimos 12 meses x limite anual do MEI
        $annualRevenue = (float) Revenue::where('user_id', $user->id)
            ->where('date', '>=', Carbon::now()->subMonths(12))
            ->sum('value');
        $limitePercentual = DasCalculator::percentualLimite($annualRevenue);
        $limiteAnual = DasCalculator::LIMITE_ANUAL_MEI;
        $dentroLimite = $annualRevenue <= $limiteAnual;

        $latestTransactions = collect()
            ->concat(Revenue::where('user_id', $user->id)->latest('date')->take(5)->get()->map(fn ($r) => [
                'type' => 'Receita', 'desc' => $r->description, 'value' => (float) $r->value, 'date' => $r->date,
            ]))
            ->concat(Expense::where('user_id', $user->id)->latest('date')->take(5)->get()->map(fn ($e) => [
                'type' => 'Despesa', 'desc' => $e->description, 'value' => -1 * (float) $e->value, 'date' => $e->date,
            ]))
            ->sortByDesc('date')
            ->take(6)
            ->values();

        return view('dashboard', [
            'currentRevenue' => $currentRevenue,
            'currentExpenses' => $currentExpenses,
            'balance' => $balance,
            'revenueChange' => $revenueChange,
            'expenseChange' => $expenseChange,
            'balanceChange' => $balanceChange,
            'revenueByMonth' => $revenueByMonth,
            'expenseByMonth' => $expenseByMonth,
            'das' => $das,
            'annualRevenue' => $annualRevenue,
            'limitePercentual' => $limitePercentual,
            'limiteAnual' => $limiteAnual,
            'dentroLimite' => $dentroLimite,
            'latestTransactions' => $latestTransactions,
        ]);
    }
}
