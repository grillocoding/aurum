<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório AURUM</title>
    <style>
        @page { margin: 28px 32px; }
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #2A2520;
            font-size: 11px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #C9A961;
            padding-bottom: 10px;
            margin-bottom: 18px;
            width: 100%;
        }
        .header table { width: 100%; }
        .brand {
            font-size: 22px;
            letter-spacing: 2px;
            color: #B89050;
            font-weight: bold;
        }
        .subtitle { color: #6b6355; font-size: 10px; margin-top: 2px; }
        .meta { text-align: right; font-size: 10px; color: #6b6355; }

        h2.section-title {
            font-size: 13px;
            color: #B89050;
            border-bottom: 1px solid #e0d8c8;
            padding-bottom: 4px;
            margin: 20px 0 10px 0;
        }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .summary-table td {
            width: 33.33%;
            border: 1px solid #e0d8c8;
            padding: 10px 12px;
            vertical-align: top;
        }
        .summary-label { font-size: 9px; color: #6b6355; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 16px; font-weight: bold; margin-top: 4px; }
        .green { color: #16a34a; }
        .red { color: #dc2626; }
        .gold { color: #B89050; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data-table th {
            background-color: #f3ede0;
            color: #6b6355;
            text-align: left;
            padding: 6px 10px;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e0d8c8;
        }
        table.data-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #f0ebe0;
        }
        table.data-table td.number, table.data-table th.number { text-align: right; }

        .two-col { width: 100%; }
        .two-col td { width: 50%; vertical-align: top; padding-right: 12px; }

        .indicators-table { width: 100%; border-collapse: collapse; }
        .indicators-table td {
            width: 25%;
            border: 1px solid #e0d8c8;
            padding: 8px 10px;
            text-align: center;
        }
        .indicators-table .ind-label { font-size: 9px; color: #6b6355; }
        .indicators-table .ind-value { font-size: 14px; font-weight: bold; margin-top: 3px; }

        .footer {
            margin-top: 24px;
            padding-top: 8px;
            border-top: 1px solid #e0d8c8;
            font-size: 8.5px;
            color: #9b8a6f;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="brand">AURUM</div>
                    <div class="subtitle">Relatório Financeiro — {{ $user->company_name ?? $user->name }}</div>
                </td>
                <td class="meta">
                    Período: {{ $periodStart->format('d/m/Y') }} a {{ $periodEnd->format('d/m/Y') }}<br>
                    Gerado em: {{ $generatedAt->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Resumo --}}
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total de Receitas</div>
                <div class="summary-value green">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Total de Despesas</div>
                <div class="summary-value red">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Lucro Líquido</div>
                <div class="summary-value gold">R$ {{ number_format($netProfit, 2, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- Categorias --}}
    <h2 class="section-title">Detalhamento por Categoria</h2>
    <table class="two-col">
        <tr>
            <td>
                <table class="data-table">
                    <thead>
                        <tr><th>Receitas</th><th class="number">Valor</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($revenueCategoryData as $category => $value)
                            <tr>
                                <td>{{ $category }}</td>
                                <td class="number">R$ {{ number_format($value, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">Nenhuma receita no período.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
            <td>
                <table class="data-table">
                    <thead>
                        <tr><th>Despesas</th><th class="number">Valor</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($expenseCategoryData as $category => $value)
                            <tr>
                                <td>{{ $category }}</td>
                                <td class="number">R$ {{ number_format($value, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">Nenhuma despesa no período.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    {{-- Comparativo Mensal --}}
    <h2 class="section-title">Comparativo Mensal</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Mês</th>
                <th class="number">Receitas</th>
                <th class="number">Despesas</th>
                <th class="number">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyComparison as $row)
                <tr>
                    <td>{{ $row['month'] }}</td>
                    <td class="number green">R$ {{ number_format($row['receitas'], 2, ',', '.') }}</td>
                    <td class="number red">R$ {{ number_format($row['despesas'], 2, ',', '.') }}</td>
                    <td class="number">R$ {{ number_format($row['receitas'] - $row['despesas'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Indicadores --}}
    <h2 class="section-title">Indicadores Financeiros</h2>
    <table class="indicators-table">
        <tr>
            <td>
                <div class="ind-label">Margem de Lucro</div>
                <div class="ind-value green">{{ number_format($profitMargin, 2, ',', '.') }}%</div>
            </td>
            <td>
                <div class="ind-label">Ticket Médio</div>
                <div class="ind-value gold">R$ {{ number_format($averageTicket, 2, ',', '.') }}</div>
            </td>
            <td>
                <div class="ind-label">ROI Mensal</div>
                <div class="ind-value green">{{ number_format($roi, 2, ',', '.') }}%</div>
            </td>
            <td>
                <div class="ind-label">Despesas/Receitas</div>
                <div class="ind-value">{{ number_format($expenseRevenueRatio, 2, ',', '.') }}%</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Relatório gerado automaticamente pelo AURUM — Sistema de Gestão Financeira para MEI.
        Este documento não substitui a apuração contábil oficial.
    </div>

</body>
</html>
