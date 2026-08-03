<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Paleta de cores para os gráficos de pizza (tons de verde/vermelho combinando com o tema dourado)
    private const REVENUE_COLORS = ['#22c55e', '#86efac', '#4ade80', '#16a34a', '#bbf7d0'];
    private const EXPENSE_COLORS = ['#ef4444', '#f87171', '#fca5a5', '#dc2626', '#fecaca'];

    public function index()
    {
        return view('reports.index', $this->buildReportData());
    }

    public function exportPdf()
    {
        $data = $this->buildReportData();
        $data['user'] = Auth::user();
        $data['generatedAt'] = Carbon::now();

        $pdf = Pdf::loadView('reports.pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-aurum-'.now()->format('Y-m-d').'.pdf');
    }

    /**
     * Monta todos os dados usados tanto na tela de relatórios quanto no PDF exportado.
     */
    private function buildReportData(): array
    {
        $userId = Auth::id();
        $start = Carbon::now()->subMonths(5)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $revenues = Revenue::where('user_id', $userId)->whereBetween('date', [$start, $end])->get();
        $expenses = Expense::where('user_id', $userId)->whereBetween('date', [$start, $end])->get();

        $totalRevenue = (float) $revenues->sum('value');
        $totalExpenses = (float) $expenses->sum('value');
        $netProfit = $totalRevenue - $totalExpenses;

        // Agrupamento por categoria
        $revenueByCategory = $revenues->groupBy('category')->map(fn ($group) => (float) $group->sum('value'));
        $expenseByCategory = $expenses->groupBy('category')->map(fn ($group) => (float) $group->sum('value'));

        // Comparativo mensal (últimos 6 meses)
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i)->startOfMonth());
        $monthlyComparison = $months->map(function ($month) use ($userId) {
            return [
                'month' => ucfirst($month->translatedFormat('M')),
                'receitas' => (float) Revenue::where('user_id', $userId)
                    ->whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('value'),
                'despesas' => (float) Expense::where('user_id', $userId)
                    ->whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('value'),
            ];
        })->values();

        // Indicadores
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
        $transactionCount = $revenues->count();
        $averageTicket = $transactionCount > 0 ? $totalRevenue / $transactionCount : 0;
        $roi = $totalExpenses > 0 ? ($netProfit / $totalExpenses) * 100 : 0;
        $expenseRevenueRatio = $totalRevenue > 0 ? ($totalExpenses / $totalRevenue) * 100 : 0;

        return [
            'periodStart' => $start,
            'periodEnd' => $end,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'revenueCategoryData' => $revenueByCategory,
            'expenseCategoryData' => $expenseByCategory,
            'revenueColors' => self::REVENUE_COLORS,
            'expenseColors' => self::EXPENSE_COLORS,
            'monthlyComparison' => $monthlyComparison,
            'profitMargin' => $profitMargin,
            'averageTicket' => $averageTicket,
            'roi' => $roi,
            'expenseRevenueRatio' => $expenseRevenueRatio,
        ];
    }
}
