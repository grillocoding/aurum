<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURUM - Criar conta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: {
            background: '#1A1512', foreground: '#C9A961', card: '#231E1A',
            'card-foreground': '#E5D4B8', primary: '#C9A961', 'primary-foreground': '#1A1512',
            muted: '#2A2520', 'muted-foreground': '#9B8A6F', destructive: '#d4183d',
            border: 'rgba(201, 169, 97, 0.2)', 'input-background': '#231E1A',
        } } } };
    </script>
    <style>body{background:#1A1512;color:#E5D4B8;}</style>
</head>
<body class="min-h-screen bg-background flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <h1 class="text-4xl tracking-wider text-primary text-center mb-2">AURUM</h1>
        <p class="text-muted-foreground text-center mb-8">Gestão Financeira para MEI</p>

        <div class="bg-card border border-border rounded-lg p-8">
            <h2 class="text-xl text-card-foreground mb-6">Criar sua conta</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-destructive/40 bg-destructive/10 px-4 py-3 text-destructive text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-card-foreground mb-2">Nome completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm text-card-foreground mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm text-card-foreground mb-2">CNPJ (opcional)</label>
                    <input type="text" name="cnpj" value="{{ old('cnpj') }}" placeholder="00.000.000/0001-00"
                           class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm text-card-foreground mb-2">Senha</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm text-card-foreground mb-2">Confirmar senha</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <button type="submit"
                        class="w-full bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:opacity-90 transition-opacity">
                    Criar conta
                </button>
            </form>

            <p class="text-center text-sm text-muted-foreground mt-6">
                Já tem conta?
                <a href="{{ route('login') }}" class="text-primary hover:underline">Entrar</a>
            </p>
        </div>
    </div>
</body>
</html>
