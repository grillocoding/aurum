@extends('layouts.app')

@section('title', 'Relatórios')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl text-card-foreground mb-2">Relatórios</h1>
            <p class="text-muted-foreground">Análise detalhada do seu negócio (últimos 6 meses)</p>
        </div>
        <a href="{{ route('reports.pdf') }}"
           class="flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
            <x-icon name="file-text" class="w-4 h-4" /> Exportar PDF
        </a>
    </div>

    {{-- Resumo Financeiro --}}
    <div class="grid gap-6 md:grid-cols-3">
        <div class="bg-card border border-border rounded-lg p-6">
            <div class="text-sm text-muted-foreground mb-2">Total Receitas (6 meses)</div>
            <div class="text-3xl text-green-500 mb-1">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
            <div class="text-sm text-muted-foreground">Média: R$ {{ number_format($totalRevenue / 6, 2, ',', '.') }}/mês</div>
        </div>
        <div class="bg-card border border-border rounded-lg p-6">
            <div class="text-sm text-muted-foreground mb-2">Total Despesas (6 meses)</div>
            <div class="text-3xl text-red-500 mb-1">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</div>
            <div class="text-sm text-muted-foreground">Média: R$ {{ number_format($totalExpenses / 6, 2, ',', '.') }}/mês</div>
        </div>
        <div class="bg-card border border-border rounded-lg p-6">
            <div class="text-sm text-muted-foreground mb-2">Lucro Líquido (6 meses)</div>
            <div class="text-3xl text-primary mb-1">R$ {{ number_format($netProfit, 2, ',', '.') }}</div>
            <div class="text-sm text-muted-foreground">Média: R$ {{ number_format($netProfit / 6, 2, ',', '.') }}/mês</div>
        </div>
    </div>

    {{-- Gráficos de Pizza --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-card border border-border rounded-lg p-6">
            <h3 class="text-lg text-card-foreground mb-6">Receitas por Categoria</h3>
            <canvas id="revenueCategoryChart" height="220"></canvas>
        </div>
        <div class="bg-card border border-border rounded-lg p-6">
            <h3 class="text-lg text-card-foreground mb-6">Despesas por Categoria</h3>
            <canvas id="expenseCategoryChart" height="220"></canvas>
        </div>
    </div>

    {{-- Comparativo Mensal --}}
    <div class="bg-card border border-border rounded-lg p-6">
        <h3 class="text-lg text-card-foreground mb-6">Comparativo Receitas x Despesas</h3>
        <canvas id="comparisonChart" height="120"></canvas>
    </div>

    {{-- Indicadores --}}
    <div class="bg-card border border-border rounded-lg p-6">
        <h3 class="text-lg text-card-foreground mb-4">Indicadores Financeiros</h3>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-muted/50 rounded-lg p-4">
                <div class="text-sm text-muted-foreground mb-2">Margem de Lucro</div>
                <div class="text-2xl text-green-500 font-medium">{{ number_format($profitMargin, 2, ',', '.') }}%</div>
                <div class="mt-2 h-2 bg-border rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" style="width: {{ max(min($profitMargin, 100), 0) }}%"></div>
                </div>
            </div>
            <div class="bg-muted/50 rounded-lg p-4">
                <div class="text-sm text-muted-foreground mb-2">Ticket Médio</div>
                <div class="text-2xl text-primary font-medium">R$ {{ number_format($averageTicket, 2, ',', '.') }}</div>
                <div class="mt-2 text-xs text-muted-foreground">Por transação</div>
            </div>
            <div class="bg-muted/50 rounded-lg p-4">
                <div class="text-sm text-muted-foreground mb-2">ROI Mensal</div>
                <div class="text-2xl text-green-500 font-medium">{{ number_format($roi, 2, ',', '.') }}%</div>
                <div class="mt-2 h-2 bg-border rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" style="width: {{ max(min($roi, 100), 0) }}%"></div>
                </div>
            </div>
            <div class="bg-muted/50 rounded-lg p-4">
                <div class="text-sm text-muted-foreground mb-2">Despesas/Receitas</div>
                <div class="text-2xl text-amber-500 font-medium">{{ number_format($expenseRevenueRatio, 2, ',', '.') }}%</div>
                <div class="mt-2 h-2 bg-border rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ max(min($expenseRevenueRatio, 100), 0) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const tooltipStyle = {
        backgroundColor: '#231E1A', titleColor: '#E5D4B8', bodyColor: '#E5D4B8',
        borderColor: 'rgba(201, 169, 97, 0.2)', borderWidth: 1, padding: 10, cornerRadius: 8,
    };
    const chartTextColor = '#9B8A6F';
    const chartGridColor = 'rgba(201, 169, 97, 0.1)';

    const revenueCategoryLabels = @json($revenueCategoryData->keys());
    const revenueCategoryValues = @json($revenueCategoryData->values());
    const revenueColors = @json($revenueColors);

    const expenseCategoryLabels = @json($expenseCategoryData->keys());
    const expenseCategoryValues = @json($expenseCategoryData->values());
    const expenseColors = @json($expenseColors);

    new Chart(document.getElementById('revenueCategoryChart'), {
        type: 'pie',
        data: {
            labels: revenueCategoryLabels,
            datasets: [{ data: revenueCategoryValues, backgroundColor: revenueColors, borderColor: '#231E1A', borderWidth: 2 }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { color: '#E5D4B8' } },
                tooltip: { ...tooltipStyle, callbacks: { label: (ctx) => ctx.label + ': R$ ' + ctx.parsed.toLocaleString('pt-BR', {minimumFractionDigits: 2}) } }
            }
        }
    });

    new Chart(document.getElementById('expenseCategoryChart'), {
        type: 'pie',
        data: {
            labels: expenseCategoryLabels,
            datasets: [{ data: expenseCategoryValues, backgroundColor: expenseColors, borderColor: '#231E1A', borderWidth: 2 }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { color: '#E5D4B8' } },
                tooltip: { ...tooltipStyle, callbacks: { label: (ctx) => ctx.label + ': R$ ' + ctx.parsed.toLocaleString('pt-BR', {minimumFractionDigits: 2}) } }
            }
        }
    });

    const comparisonLabels = @json(collect($monthlyComparison)->pluck('month'));
    const comparisonRevenues = @json(collect($monthlyComparison)->pluck('receitas'));
    const comparisonExpenses = @json(collect($monthlyComparison)->pluck('despesas'));

    new Chart(document.getElementById('comparisonChart'), {
        type: 'bar',
        data: {
            labels: comparisonLabels,
            datasets: [
                { label: 'Receitas', data: comparisonRevenues, backgroundColor: '#22c55e', borderRadius: 8 },
                { label: 'Despesas', data: comparisonExpenses, backgroundColor: '#ef4444', borderRadius: 8 },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top', labels: { color: '#E5D4B8' } },
                tooltip: { ...tooltipStyle, callbacks: { label: (ctx) => ctx.dataset.label + ': R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2}) } }
            },
            scales: {
                x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
                y: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }
            }
        }
    });
</script>
@endpush
