@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl text-card-foreground mb-2">Dashboard</h1>
        <p class="text-muted-foreground">Visão geral do seu negócio</p>
    </div>

    {{-- Cards de Resumo --}}
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-card border border-border rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-muted-foreground">Faturamento</span>
                <div class="bg-green-500/10 p-2 rounded-lg"><x-icon name="trending-up" class="w-5 h-5 text-green-500" /></div>
            </div>
            <div class="text-3xl text-card-foreground mb-2">
                R$ {{ number_format($currentRevenue, 2, ',', '.') }}
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 text-xs px-2 py-1 rounded {{ $revenueChange >= 0 ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                    <x-icon :name="$revenueChange >= 0 ? 'arrow-up' : 'arrow-down'" class="w-3 h-3" />
                    <span>{{ number_format(abs($revenueChange), 1, ',', '.') }}%</span>
                </div>
                <span class="text-xs text-muted-foreground">vs mês anterior</span>
            </div>
        </div>

        <div class="bg-card border border-border rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-muted-foreground">Despesas</span>
                <div class="bg-red-500/10 p-2 rounded-lg"><x-icon name="trending-down" class="w-5 h-5 text-red-500" /></div>
            </div>
            <div class="text-3xl text-card-foreground mb-2">
                R$ {{ number_format($currentExpenses, 2, ',', '.') }}
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 text-xs px-2 py-1 rounded {{ $expenseChange <= 0 ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                    <x-icon :name="$expenseChange <= 0 ? 'arrow-down' : 'arrow-up'" class="w-3 h-3" />
                    <span>{{ number_format(abs($expenseChange), 1, ',', '.') }}%</span>
                </div>
                <span class="text-xs text-muted-foreground">vs mês anterior</span>
            </div>
        </div>

        <div class="bg-card border border-border rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-muted-foreground">Saldo</span>
                <div class="bg-primary/10 p-2 rounded-lg"><x-icon name="wallet" class="w-5 h-5 text-primary" /></div>
            </div>
            <div class="text-3xl text-primary mb-2">
                R$ {{ number_format($balance, 2, ',', '.') }}
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 text-xs px-2 py-1 rounded {{ $balanceChange >= 0 ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                    <x-icon :name="$balanceChange >= 0 ? 'arrow-up' : 'arrow-down'" class="w-3 h-3" />
                    <span>{{ number_format(abs($balanceChange), 1, ',', '.') }}%</span>
                </div>
                <span class="text-xs text-muted-foreground">vs mês anterior</span>
            </div>
        </div>

        <div class="bg-card border border-border rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-muted-foreground">DAS Mensal</span>
                <div class="bg-amber-500/10 p-2 rounded-lg"><x-icon name="alert-triangle" class="w-5 h-5 text-amber-500" /></div>
            </div>
            <div class="text-3xl text-card-foreground mb-2">
                R$ {{ number_format($das['total'], 2, ',', '.') }}
            </div>
            <div class="flex items-center gap-1">
                <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                <span class="text-xs text-amber-500">Vencimento: dia 20</span>
            </div>
        </div>
    </div>

    {{-- Limite Anual MEI --}}
    <div class="bg-card border border-border rounded-lg p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg text-card-foreground">Limite Anual MEI</h3>
            <span class="text-sm text-muted-foreground">
                R$ {{ number_format($annualRevenue, 2, ',', '.') }} / R$ {{ number_format($limiteAnual, 2, ',', '.') }}
            </span>
        </div>
        <div class="h-3 rounded-full bg-muted overflow-hidden">
            <div class="h-full rounded-full {{ $limitePercentual >= 90 ? 'bg-red-500' : ($limitePercentual >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                 style="width: {{ $limitePercentual }}%"></div>
        </div>
        <div class="mt-3 text-sm">
            @if (! $dentroLimite)
                <span class="text-red-400 inline-flex items-center gap-1"><x-icon name="alert-triangle" class="w-4 h-4" /> Faturamento acima do limite MEI (R$ {{ number_format($limiteAnual, 0, ',', '.') }}). Você pode precisar migrar para ME/EPP.</span>
            @elseif ($limitePercentual >= 70)
                <span class="text-amber-400 inline-flex items-center gap-1"><x-icon name="alert-triangle" class="w-4 h-4" /> Você já utilizou {{ number_format($limitePercentual, 0) }}% do limite anual. Fique atento.</span>
            @else
                <span class="text-emerald-400 inline-flex items-center gap-1"><x-icon name="check-circle" class="w-4 h-4" /> Dentro do limite MEI — {{ number_format($limitePercentual, 0) }}% utilizado nos últimos 12 meses.</span>
            @endif
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-card border border-border rounded-lg p-6">
            <h3 class="text-lg text-card-foreground mb-6">Faturamento (Últimos 6 meses)</h3>
            <canvas id="revenueChart" height="150"></canvas>
        </div>

        <div class="bg-card border border-border rounded-lg p-6">
            <h3 class="text-lg text-card-foreground mb-6">Despesas (Últimos 6 meses)</h3>
            <canvas id="expenseChart" height="150"></canvas>
        </div>
    </div>

    {{-- Últimas Transações --}}
    <div class="bg-card border border-border rounded-lg p-6">
        <h3 class="text-lg text-card-foreground mb-4">Últimas Transações</h3>
        <div class="space-y-4">
            @forelse ($latestTransactions as $transaction)
                <div class="flex items-center justify-between py-3 border-b border-border last:border-0">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="p-2 rounded-lg {{ $transaction['value'] > 0 ? 'bg-green-500/10' : 'bg-red-500/10' }}">
                            <x-icon :name="$transaction['value'] > 0 ? 'trending-up' : 'trending-down'" class="w-5 h-5 {{ $transaction['value'] > 0 ? 'text-green-500' : 'text-red-500' }}" />
                        </div>
                        <div>
                            <p class="text-card-foreground">{{ $transaction['desc'] }}</p>
                            <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="text-lg font-medium {{ $transaction['value'] > 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ $transaction['value'] > 0 ? '+' : '-' }}R$ {{ number_format(abs($transaction['value']), 2, ',', '.') }}
                    </div>
                </div>
            @empty
                <p class="text-muted-foreground text-sm">Nenhuma transação registrada ainda.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chartTextColor = '#9B8A6F';
    const chartGridColor = 'rgba(201, 169, 97, 0.1)';
    const tooltipStyle = {
        backgroundColor: '#231E1A',
        titleColor: '#E5D4B8',
        bodyColor: '#E5D4B8',
        borderColor: 'rgba(201, 169, 97, 0.2)',
        borderWidth: 1,
        padding: 10,
        cornerRadius: 8,
    };

    const revenueLabels = @json(collect($revenueByMonth)->pluck('month'));
    const revenueValues = @json(collect($revenueByMonth)->pluck('value'));
    const expenseLabels = @json(collect($expenseByMonth)->pluck('month'));
    const expenseValues = @json(collect($expenseByMonth)->pluck('value'));

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Faturamento',
                data: revenueValues,
                backgroundColor: '#22c55e',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { ...tooltipStyle, callbacks: { label: (ctx) => 'R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2}) } }
            },
            scales: {
                x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
                y: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }
            }
        }
    });

    new Chart(document.getElementById('expenseChart'), {
        type: 'line',
        data: {
            labels: expenseLabels,
            datasets: [{
                label: 'Despesas',
                data: expenseValues,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.15)',
                borderWidth: 3,
                pointBackgroundColor: '#ef4444',
                pointRadius: 5,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { ...tooltipStyle, callbacks: { label: (ctx) => 'R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2}) } }
            },
            scales: {
                x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
                y: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }
            }
        }
    });
</script>
@endpush
