<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURUM — Gestão Financeira para MEI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#1A1512',
                        foreground: '#E5D4B8',
                        card: '#231E1A',
                        'card-foreground': '#E5D4B8',
                        primary: '#C9A961',
                        'primary-foreground': '#1A1512',
                        muted: '#2A2520',
                        'muted-foreground': '#9B8A6F',
                        border: 'rgba(201, 169, 97, 0.2)',
                    },
                },
            },
        };
    </script>
    <style>
        body { background:#1A1512; color:#E5D4B8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .gold-gradient { background: linear-gradient(135deg, #C9A961 0%, #B89050 100%); }
    </style>
</head>
<body class="min-h-screen bg-background">

    {{-- Navbar --}}
    <header class="fixed top-0 inset-x-0 z-50 border-b border-border bg-background/90 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded border border-primary/40 flex items-center justify-center">
                    <div class="w-2.5 h-2.5 rounded-sm bg-primary"></div>
                </div>
                <span class="text-xl tracking-[0.2em] text-primary font-light">AURUM</span>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm text-muted-foreground">
                <a href="#funcionalidades" class="hover:text-foreground transition-colors">Funcionalidades</a>
                <a href="#como-funciona" class="hover:text-foreground transition-colors">Como funciona</a>
                <a href="#faq" class="hover:text-foreground transition-colors">FAQ</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors px-4 py-2">
                    Entrar
                </a>
                <a href="{{ route('register') }}" class="text-sm px-4 py-2 rounded-lg font-medium text-primary-foreground gold-gradient">
                    Assinar por R$ 14,90/mês
                </a>
            </div>

            <button class="md:hidden text-muted-foreground" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                <x-icon name="menu" class="w-5 h-5" />
            </button>
        </div>

        <div id="mobileMenu" class="hidden md:hidden border-t border-border bg-background px-6 py-4 space-y-4">
            <a href="#funcionalidades" class="block text-sm text-muted-foreground hover:text-foreground">Funcionalidades</a>
            <a href="#como-funciona" class="block text-sm text-muted-foreground hover:text-foreground">Como funciona</a>
            <a href="#faq" class="block text-sm text-muted-foreground hover:text-foreground">FAQ</a>
            <a href="{{ route('login') }}" class="block text-sm text-muted-foreground hover:text-foreground">Entrar</a>
            <a href="{{ route('register') }}" class="block text-sm px-4 py-2 rounded-lg font-medium text-primary-foreground gold-gradient text-center">
                Assinar por R$ 14,90/mês
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <section class="pt-40 pb-24 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 border border-primary/30 rounded-full px-4 py-1.5 text-xs text-primary mb-8">
                <x-icon name="sparkles" class="w-3.5 h-3.5" /> Feito para o Microempreendedor Individual
            </div>
            <h1 class="text-4xl md:text-6xl leading-tight mb-6 text-card-foreground">
                Suas finanças MEI, <span class="text-primary">sob controle.</span>
            </h1>
            <p class="text-lg text-muted-foreground max-w-2xl mx-auto mb-10">
                Faturamento, despesas, DAS e relatórios em um só lugar. Simples o suficiente
                pra quem empreende, completo o suficiente pra quem quer crescer.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="flex items-center gap-2 px-8 py-3.5 rounded-lg font-medium text-primary-foreground gold-gradient hover:opacity-90 transition-opacity">
                    Começar agora <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
                <a href="#funcionalidades"
                   class="px-8 py-3.5 rounded-lg border border-border text-card-foreground hover:bg-muted transition-colors">
                    Ver funcionalidades
                </a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="pb-24 px-6">
        <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ([
                ['value' => 'R$ 81 mil', 'label' => 'Limite MEI monitorado'],
                ['value' => 'Dia 20', 'label' => 'Lembrete de vencimento DAS'],
                ['value' => '100%', 'label' => 'Focado em MEI'],
                ['value' => 'R$ 14,90', 'label' => 'Por mês, tudo incluso'],
            ] as $stat)
                <div class="text-center border border-border rounded-lg py-6 px-4 bg-card">
                    <div class="text-2xl md:text-3xl text-primary mb-1">{{ $stat['value'] }}</div>
                    <div class="text-xs text-muted-foreground">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Funcionalidades --}}
    <section id="funcionalidades" class="py-24 px-6 border-t border-border">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl text-card-foreground mb-4">Tudo que seu MEI precisa</h2>
                <p class="text-muted-foreground max-w-xl mx-auto">Ferramentas pensadas pra tirar o peso da gestão financeira das suas costas.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['icon' => 'dashboard', 'title' => 'Dashboard em tempo real', 'desc' => 'Visualize faturamento, despesas e saldo de forma clara. Gráficos dinâmicos que revelam a saúde do seu negócio num relance.'],
                    ['icon' => 'trending-up', 'title' => 'Controle de faturamento', 'desc' => 'Registre entradas, categorize receitas e acompanhe sua evolução mensal. Nunca mais perca uma nota.'],
                    ['icon' => 'trending-down', 'title' => 'Gestão de despesas', 'desc' => 'Classifique seus gastos por categoria, identifique onde cortar e mantenha o caixa saudável.'],
                    ['icon' => 'calculator', 'title' => 'Simulador DAS', 'desc' => 'Saiba exatamente o quanto pagar todo mês. Valor fixo, sem surpresas — calculado com base na sua atividade.'],
                    ['icon' => 'file-text', 'title' => 'Relatórios inteligentes', 'desc' => 'Comparativos de receitas e despesas, projeções e indicadores financeiros — tudo em um lugar.'],
                    ['icon' => 'lightbulb', 'title' => 'Sugestão de nomes', 'desc' => 'Ainda escolhendo o nome do seu MEI? Encontre opções que combinam com sua área de atuação.'],
                ] as $feature)
                    <div class="border border-border rounded-lg p-6 bg-card hover:border-primary/40 transition-colors">
                        <div class="mb-4 text-primary"><x-icon :name="$feature['icon']" class="w-7 h-7" /></div>
                        <h3 class="text-lg text-card-foreground mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Como funciona --}}
    <section id="como-funciona" class="py-24 px-6 border-t border-border bg-card/30">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl text-card-foreground mb-4">Como funciona</h2>
                <p class="text-muted-foreground">Três passos simples pra começar a gerir seu negócio hoje mesmo.</p>
            </div>

            <div class="space-y-8">
                @foreach ([
                    ['number' => '01', 'title' => 'Crie sua conta gratuitamente', 'desc' => 'Cadastro simples com seu CNPJ MEI. Por apenas R$ 14,90/mês, sem burocracia.'],
                    ['number' => '02', 'title' => 'Configure seu perfil de negócio', 'desc' => 'Informe sua atividade e deixe o AURUM preparar tudo para você.'],
                    ['number' => '03', 'title' => 'Comece a gerir suas finanças', 'desc' => 'Registre entradas e saídas, acompanhe seu DAS e emita relatórios na hora.'],
                ] as $step)
                    <div class="flex items-start gap-6">
                        <div class="text-3xl text-primary/40 font-light w-16 shrink-0">{{ $step['number'] }}</div>
                        <div>
                            <h3 class="text-lg text-card-foreground mb-1">{{ $step['title'] }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="py-24 px-6 border-t border-border">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl text-card-foreground mb-4">Perguntas frequentes</h2>
            </div>

            <div class="space-y-3" x-data="{}">
                @foreach ([
                    ['q' => 'Quanto custa o AURUM?', 'a' => 'R$ 14,90 por mês, com todas as funcionalidades incluídas. Sem taxas extras, sem surpresas na fatura.'],
                    ['q' => 'Preciso saber de contabilidade para usar?', 'a' => 'Não. O AURUM foi criado para quem empreende, não para contadores. A interface é direta e os cálculos são automáticos.'],
                    ['q' => 'O valor do DAS é calculado sobre o meu faturamento?', 'a' => 'Não — e este é um equívoco comum. O DAS do MEI é um valor fixo mensal (INSS + ICMS e/ou ISS conforme sua atividade), independente do quanto você faturou no mês.'],
                    ['q' => 'Meus dados ficam seguros?', 'a' => 'Sim. Todos os dados são armazenados com criptografia e nunca são compartilhados com terceiros.'],
                ] as $index => $faq)
                    <details class="border border-border rounded-lg bg-card group">
                        <summary class="flex items-center justify-between px-6 py-4 cursor-pointer text-card-foreground list-none">
                            <span>{{ $faq['q'] }}</span>
                            <span class="text-muted-foreground group-open:rotate-180 transition-transform"><x-icon name="chevron-down" class="w-4 h-4" /></span>
                        </summary>
                        <div class="px-6 pb-4 text-sm text-muted-foreground leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    <section class="py-24 px-6 border-t border-border">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl text-card-foreground mb-4">Pronto pra organizar suas finanças?</h2>
            <p class="text-muted-foreground mb-8">Crie sua conta em menos de 2 minutos e assuma o controle do seu MEI.</p>
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg font-medium text-primary-foreground gold-gradient hover:opacity-90 transition-opacity">
                Assinar por R$ 14,90/mês <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-border py-8 px-6">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-muted-foreground">
            <span class="tracking-[0.2em] text-primary">AURUM</span>
            <span>&copy; {{ date('Y') }} AURUM. Feito para o empreendedor brasileiro.</span>
        </div>
    </footer>
</body>
</html>
