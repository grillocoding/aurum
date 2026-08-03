@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl text-card-foreground mb-2">Perfil</h1>
        <p class="text-muted-foreground">Gerencie suas informações pessoais e empresariais</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Avatar e Resumo --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-card border border-border rounded-lg p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-4">
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-primary flex items-center justify-center text-5xl">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="Foto de perfil" class="w-full h-full object-cover">
                            @else
                                <x-icon name="user" class="w-12 h-12 text-primary-foreground" />
                            @endif
                        </div>
                        <label for="avatarInput"
                               class="absolute bottom-0 right-0 w-9 h-9 rounded-full bg-muted border border-border flex items-center justify-center cursor-pointer hover:bg-secondary transition-colors"
                               title="Alterar foto">
                            <x-icon name="camera" class="w-4 h-4 text-card-foreground" />
                        </label>
                    </div>

                    <form id="avatarForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="activity_type" value="{{ $user->activity_type }}">
                        <input type="file" id="avatarInput" name="avatar" accept="image/png,image/jpeg,image/webp"
                               onchange="document.getElementById('avatarForm').submit()">
                    </form>

                    @if ($user->avatar)
                        <form method="POST" action="{{ route('profile.avatar.destroy') }}"
                              onsubmit="return confirm('Remover a foto de perfil?')" class="mb-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-destructive hover:underline">
                                Remover foto
                            </button>
                        </form>
                    @endif

                    <h2 class="text-xl text-card-foreground mb-1">{{ $user->name }}</h2>
                    <p class="text-sm text-muted-foreground mb-4">{{ $user->email }}</p>
                    <div class="w-full pt-4 border-t border-border">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                            <x-icon name="building" class="w-4 h-4 shrink-0" /> <span>{{ $user->company_name ?? 'Empresa não informada' }}</span>
                        </div>
                        <div class="text-sm text-muted-foreground">
                            CNPJ: {{ $user->cnpj ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-card border border-border rounded-lg p-6">
                <h3 class="text-lg text-card-foreground mb-4">Estatísticas</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Membro desde:</span>
                        <span class="text-card-foreground">{{ $user->created_at->translatedFormat('M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Total de receitas:</span>
                        <span class="text-card-foreground">{{ $totalRevenues }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Total de despesas:</span>
                        <span class="text-card-foreground">{{ $totalExpenses }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulário --}}
        <div class="lg:col-span-2 space-y-6">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-card border border-border rounded-lg p-6">
                    <h3 class="text-lg text-card-foreground mb-6">Informações Pessoais</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">Nome Completo</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">Telefone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <div class="bg-card border border-border rounded-lg p-6">
                    <h3 class="text-lg text-card-foreground mb-6">Informações Empresariais</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-card-foreground mb-2">Razão Social</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">CNPJ</label>
                            <input type="text" name="cnpj" value="{{ old('cnpj', $user->cnpj) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">Tipo de Atividade (para o DAS)</label>
                            <select name="activity_type" class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                @foreach ($atividades as $key => $atv)
                                    <option value="{{ $key }}" {{ old('activity_type', $user->activity_type) === $key ? 'selected' : '' }}>
                                        {{ $atv['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">CEP</label>
                            <input type="text" name="cep" id="cepInput" maxlength="9" placeholder="00000-000"
                                   value="{{ old('cep', $user->cep) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                            <p id="cepStatus" class="text-xs text-muted-foreground mt-1 h-4"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-card-foreground mb-2">Endereço</label>
                            <input type="text" name="address" id="addressInput" value="{{ old('address', $user->address) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">Cidade</label>
                            <input type="text" name="city" id="cityInput" value="{{ old('city', $user->city) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-card-foreground mb-2">Estado</label>
                            <input type="text" name="state" id="stateInput" maxlength="2" value="{{ old('state', $user->state) }}"
                                   class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <button type="submit" class="bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:opacity-90 transition-opacity">
                    <x-icon name="save" class="w-4 h-4 inline mr-1" /> Salvar Alterações
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const cepInput = document.getElementById('cepInput');
    const addressInput = document.getElementById('addressInput');
    const cityInput = document.getElementById('cityInput');
    const stateInput = document.getElementById('stateInput');
    const cepStatus = document.getElementById('cepStatus');

    // Formata o CEP visualmente (00000-000) enquanto o usuário digita
    cepInput.addEventListener('input', () => {
        let value = cepInput.value.replace(/\D/g, '').slice(0, 8);
        if (value.length > 5) {
            value = value.slice(0, 5) + '-' + value.slice(5);
        }
        cepInput.value = value;
    });

    cepInput.addEventListener('blur', buscarCep);
    cepInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') buscarCep();
    });

    async function buscarCep() {
        const cep = cepInput.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            return;
        }

        cepStatus.textContent = 'Buscando endereço...';
        cepStatus.classList.remove('text-red-400', 'text-emerald-400');
        cepStatus.classList.add('text-muted-foreground');

        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();

            if (data.erro) {
                cepStatus.textContent = 'CEP não encontrado.';
                cepStatus.classList.add('text-red-400');
                return;
            }

            addressInput.value = data.logradouro || addressInput.value;
            cityInput.value = data.localidade || cityInput.value;
            stateInput.value = data.uf || stateInput.value;

            cepStatus.textContent = 'Endereço preenchido automaticamente.';
            cepStatus.classList.add('text-emerald-400');
        } catch (error) {
            cepStatus.textContent = 'Não foi possível consultar o CEP agora.';
            cepStatus.classList.add('text-red-400');
        }
    }
</script>
@endpush

@endsection
