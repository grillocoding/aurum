@extends('layouts.app')

@section('title', 'Simulador DAS')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl text-card-foreground mb-2">Simulador DAS — MEI</h1>
        <p class="text-muted-foreground">Consulte o valor fixo mensal do seu DAS conforme sua atividade</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Painel principal --}}
        <div class="space-y-4">
            <div class="bg-card border border-border rounded-lg p-6 space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-icon name="calculator" class="w-5 h-5 text-primary" />
                    <h3 class="text-lg text-card-foreground">Sua atividade</h3>
                </div>

                <div class="grid grid-cols-1 gap-2" id="atividadeButtons">
                    @foreach ($atividades as $key => $atv)
                        <button type="button" data-key="{{ $key }}"
                                onclick="selecionarAtividade('{{ $key }}')"
                                class="atividade-btn text-left px-4 py-3 rounded-lg border transition-all {{ $atividadeKey === $key ? 'border-primary bg-primary/10 text-card-foreground' : 'border-border bg-muted/30 text-muted-foreground' }}">
                            <div class="font-medium text-sm">{{ $atv['label'] }}</div>
                            <div class="text-xs mt-0.5 opacity-70">{{ $atv['exemplo'] }}</div>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-sm text-card-foreground">Faturamento anual previsto</label>
                    <span class="text-xs text-muted-foreground">opcional</span>
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground text-sm">R$</span>
                    <input type="number" step="100" id="faturamentoInput" value="{{ $faturamentoAnual > 0 ? $faturamentoAnual : '' }}"
                           oninput="atualizarLimite()" placeholder="Ex: 60000"
                           class="w-full pl-10 pr-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <div id="limiteInfo" class="space-y-2"></div>
            </div>
        </div>

        {{-- Resultado --}}
        <div class="space-y-4">
            <div class="bg-card border border-primary/30 rounded-lg p-6">
                <div class="text-xs tracking-widest uppercase text-muted-foreground mb-1">
                    Seu DAS mensal fixo — <span id="atividadeLabel">{{ $resultado['atividade']['label'] }}</span>
                </div>
                <div class="text-5xl text-primary mb-4" id="totalDisplay">
                    R$ {{ number_format($resultado['total'], 2, ',', '.') }}
                </div>

                <div class="space-y-2 border-t border-border pt-4" id="composicao">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">INSS (5% salário mínimo)</span>
                        <span class="text-card-foreground">R$ {{ number_format($resultado['inss'], 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm" id="icmsLine" style="{{ $resultado['icms'] == 0 ? 'display:none' : '' }}">
                        <span class="text-muted-foreground">ICMS (Comércio/Indústria)</span>
                        <span class="text-card-foreground">R$ {{ number_format(1.00, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm" id="issLine" style="{{ $resultado['iss'] == 0 ? 'display:none' : '' }}">
                        <span class="text-muted-foreground">ISS (Serviços)</span>
                        <span class="text-card-foreground">R$ {{ number_format(5.00, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium border-t border-border pt-2 mt-2">
                        <span class="text-card-foreground">Total mensal</span>
                        <span class="text-primary" id="totalMensal">R$ {{ number_format($resultado['total'], 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">Total anual</span>
                        <span class="text-card-foreground" id="totalAnual">R$ {{ number_format($resultado['total_anual'], 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg p-6 space-y-4">
                <div class="flex items-center gap-2">
                    <x-icon name="info" class="w-4 h-4 text-primary" />
                    <h3 class="text-sm text-card-foreground">Como funciona o DAS MEI</h3>
                </div>
                <div class="space-y-2 text-xs text-muted-foreground leading-relaxed">
                    <p>
                        O DAS do MEI é um valor <strong class="text-card-foreground">fixo mensal</strong> — ele
                        <strong class="text-card-foreground">não varia com o faturamento</strong> do mês.
                        O que importa é não ultrapassar o limite anual de <strong class="text-card-foreground">R$ 81.000</strong>.
                    </p>
                    <p>
                        O valor é composto pelo INSS (5% do salário mínimo vigente) mais R$ 1,00 de ICMS para
                        comércio/indústria e/ou R$ 5,00 de ISS para serviços.
                    </p>
                    <p>
                        Vencimento: <strong class="text-card-foreground">dia 20 de cada mês</strong>. O pagamento é
                        feito pelo portal do Simples Nacional (DAS-MEI) ou pelo app MEI.
                    </p>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg p-4">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Vencimento mensal</span>
                        <span class="text-card-foreground font-medium">Dia 20</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Declaração anual (DASN-SIMEI)</span>
                        <span class="text-card-foreground font-medium">Até 31 de maio</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Limite de faturamento anual</span>
                        <span class="text-primary font-medium">R$ 81.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ATIVIDADES = @json($atividades);
    const INSS_MEI = 70.60;
    const ICMS_MEI = 1.00;
    const ISS_MEI = 5.00;
    const LIMITE_ANUAL_MEI = 81000;

    function formatBRL(value) {
        return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function selecionarAtividade(key) {
        document.querySelectorAll('.atividade-btn').forEach(btn => {
            if (btn.dataset.key === key) {
                btn.classList.add('border-primary', 'bg-primary/10', 'text-card-foreground');
                btn.classList.remove('border-border', 'bg-muted/30', 'text-muted-foreground');
            } else {
                btn.classList.remove('border-primary', 'bg-primary/10', 'text-card-foreground');
                btn.classList.add('border-border', 'bg-muted/30', 'text-muted-foreground');
            }
        });

        const atividade = ATIVIDADES[key];
        const icms = atividade.icms ? ICMS_MEI : 0;
        const iss = atividade.iss ? ISS_MEI : 0;
        const total = INSS_MEI + icms + iss;

        document.getElementById('atividadeLabel').textContent = atividade.label;
        document.getElementById('totalDisplay').textContent = 'R$ ' + formatBRL(total);
        document.getElementById('totalMensal').textContent = 'R$ ' + formatBRL(total);
        document.getElementById('totalAnual').textContent = 'R$ ' + formatBRL(total * 12);
        document.getElementById('icmsLine').style.display = atividade.icms ? '' : 'none';
        document.getElementById('issLine').style.display = atividade.iss ? '' : 'none';
    }

    function atualizarLimite() {
        const input = document.getElementById('faturamentoInput');
        const faturamento = parseFloat(input.value.replace(',', '.')) || 0;
        const container = document.getElementById('limiteInfo');

        if (faturamento <= 0) {
            container.innerHTML = '';
            return;
        }

        const percentual = Math.min((faturamento / LIMITE_ANUAL_MEI) * 100, 100);
        const dentroLimite = faturamento <= LIMITE_ANUAL_MEI;
        const barColor = percentual >= 90 ? 'bg-red-500' : percentual >= 70 ? 'bg-amber-500' : 'bg-emerald-500';

        let alerta = '';
        if (!dentroLimite) {
            alerta = `<div class="flex items-center gap-2 text-xs text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg px-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline w-4 h-4 -mt-0.5 mr-1"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Faturamento acima do limite MEI. Você pode precisar migrar para ME/EPP.
            </div>`;
        } else if (percentual >= 70) {
            alerta = `<div class="flex items-center gap-2 text-xs text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-lg px-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline w-4 h-4 -mt-0.5 mr-1"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Você já utilizou ${percentual.toFixed(0)}% do limite anual. Fique atento.
            </div>`;
        } else {
            alerta = `<div class="flex items-center gap-2 text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-lg px-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline w-4 h-4 -mt-0.5 mr-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Dentro do limite MEI. Você continua com as obrigações simplificadas.
            </div>`;
        }

        container.innerHTML = `
            <div class="flex justify-between text-xs">
                <span class="text-muted-foreground">Limite MEI anual</span>
                <span class="${dentroLimite ? 'text-emerald-400' : 'text-red-400'}">
                    R$ ${formatBRL(faturamento)} / R$ ${formatBRL(LIMITE_ANUAL_MEI)}
                </span>
            </div>
            <div class="h-2 rounded-full bg-muted overflow-hidden">
                <div class="h-full rounded-full ${barColor}" style="width: ${percentual}%"></div>
            </div>
            ${alerta}
        `;
    }

    // Inicializa o painel de limite se já houver faturamento vindo do servidor
    atualizarLimite();
</script>
@endpush
