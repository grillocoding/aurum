<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AURUM - @yield('title', 'Gestão Financeira MEI')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#1A1512',
                        foreground: '#C9A961',
                        card: '#231E1A',
                        'card-foreground': '#E5D4B8',
                        primary: '#C9A961',
                        'primary-foreground': '#1A1512',
                        secondary: '#3A322A',
                        'secondary-foreground': '#E5D4B8',
                        muted: '#2A2520',
                        'muted-foreground': '#9B8A6F',
                        destructive: '#d4183d',
                        border: 'rgba(201, 169, 97, 0.2)',
                        'input-background': '#231E1A',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        body { background-color: #1A1512; color: #E5D4B8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: rgba(201,169,97,0.3); border-radius: 4px; }
    </style>
    /* Deu erro no meu pc /*
    @stack('styles')
</head>
<body class="min-h-screen bg-background">
    <div>
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 z-40 h-screen w-64 bg-card border-r border-border">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between border-b border-border p-6">
                    <h1 class="text-3xl tracking-wider text-primary">AURUM</h1>
                </div>

                <nav class="flex-1 space-y-1 p-4">
                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                            ['route' => 'revenues.index', 'label' => 'Faturamento', 'icon' => 'trending-up'],
                            ['route' => 'expenses.index', 'label' => 'Despesas', 'icon' => 'trending-down'],
                            ['route' => 'reports.index', 'label' => 'Relatórios', 'icon' => 'file-text'],
                            ['route' => 'das.index', 'label' => 'Simulador DAS', 'icon' => 'calculator'],
                            ['route' => 'names.index', 'label' => 'Sugestão de Nomes', 'icon' => 'lightbulb'],
                            ['route' => 'profile.edit', 'label' => 'Perfil', 'icon' => 'user'],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 rounded-lg px-4 py-3 transition-colors {{ request()->routeIs($item['route']) ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-muted hover:text-card-foreground' }}">
                            <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="border-t border-border p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-muted-foreground transition-colors hover:bg-muted hover:text-card-foreground">
                            <x-icon name="log-out" class="w-5 h-5 shrink-0" />
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="lg:pl-64">
            <header class="sticky top-0 z-30 border-b border-border bg-card px-6 py-4">
                <div class="flex items-center justify-between">
                    <div></div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 text-sm text-muted-foreground hover:text-card-foreground transition-colors">
                        <span>Sistema de Gestão Financeira para MEI — {{ Auth::user()->name ?? '' }}</span>
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-primary flex items-center justify-center text-sm shrink-0">
                            @if (Auth::user()?->avatar)
                                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <x-icon name="user" class="w-4 h-4 text-primary-foreground" />
                            @endif
                        </div>
                    </a>
                </div>
            </header>

            <main class="p-6">
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-primary/30 bg-primary/10 px-4 py-3 text-primary">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-destructive/40 bg-destructive/10 px-4 py-3 text-destructive">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
