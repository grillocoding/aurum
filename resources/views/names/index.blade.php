@extends('layouts.app')

@section('title', 'Sugestão de Nomes')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl text-card-foreground mb-2">Sugestão de Nomes</h1>
        <p class="text-muted-foreground">Encontre o nome perfeito para o seu negócio</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1 bg-card border border-border rounded-lg p-6">
            <div class="flex items-center gap-2 mb-6">
                <x-icon name="sparkles" class="w-5 h-5 text-primary" />
                <h3 class="text-lg text-card-foreground">Gerar Sugestões</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-card-foreground mb-2">Categoria do negócio</label>
                    <select id="category" class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Selecione</option>
                        <option value="consultoria">Consultoria</option>
                        <option value="desenvolvimento">Desenvolvimento / TI</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                        <option value="comercio">Comércio</option>
                        <option value="servicos">Serviços gerais</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-card-foreground mb-2">Palavras-chave (separadas por vírgula)</label>
                    <input type="text" id="keywords" placeholder="Ex: digital, prime, foco"
                           class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <button onclick="generateNames()"
                        class="w-full flex items-center justify-center gap-2 bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:opacity-90 transition-opacity">
                    <x-icon name="refresh-cw" class="w-4 h-4" /> Gerar Nomes
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-lg p-6">
            <h3 class="text-lg text-card-foreground mb-6">Sugestões</h3>
            <div id="suggestions" class="grid gap-3 md:grid-cols-2">
                <p class="text-muted-foreground text-sm col-span-2">Preencha o formulário e clique em "Gerar Nomes".</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const namePrefixes = ["Pro", "Smart", "Prime", "Elite", "Max", "Plus", "Tech", "Digital"];
    const nameSuffixes = ["Solutions", "Services", "Pro", "Express", "Lab", "Hub", "Studio", "Group"];
    const businessTypes = {
        consultoria: ["Consultoria", "Assessoria", "Estratégia"],
        desenvolvimento: ["Software", "Sistemas", "Code", "Dev"],
        design: ["Design", "Criação", "Studio"],
        marketing: ["Marketing", "Growth", "Mídia"],
        comercio: ["Comércio", "Vendas", "Mercado"],
        servicos: ["Serviços", "Gestão", "Soluções"],
    };

    function generateNames() {
        const category = document.getElementById('category').value;
        const keywords = document.getElementById('keywords').value;
        const container = document.getElementById('suggestions');

        if (!category) {
            alert('Por favor, selecione uma categoria');
            return;
        }

        const keywordList = keywords.split(',').map(k => k.trim()).filter(k => k);
        const types = businessTypes[category] || ["Negócios"];
        const generated = [];

        for (let i = 0; i < 20 && generated.length < 10; i++) {
            const rand = Math.random();
            let name = '';

            if (rand < 0.3 && keywordList.length > 0) {
                const keyword = keywordList[Math.floor(Math.random() * keywordList.length)];
                const suffix = nameSuffixes[Math.floor(Math.random() * nameSuffixes.length)];
                name = `${keyword} ${suffix}`;
            } else if (rand < 0.6) {
                const prefix = namePrefixes[Math.floor(Math.random() * namePrefixes.length)];
                const type = types[Math.floor(Math.random() * types.length)];
                name = `${prefix} ${type}`;
            } else {
                const prefix = namePrefixes[Math.floor(Math.random() * namePrefixes.length)];
                const suffix = nameSuffixes[Math.floor(Math.random() * nameSuffixes.length)];
                name = `${prefix}${suffix}`;
            }

            if (!generated.includes(name)) {
                generated.push(name);
            }
        }

        container.innerHTML = generated.map(name => `
            <div class="bg-muted/30 border border-border rounded-lg px-4 py-3 text-card-foreground hover:border-primary/40 transition-colors">
                ${name}
            </div>
        `).join('');
    }
</script>
@endpush
