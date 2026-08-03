@extends('layouts.app')

@section('title', 'Despesas')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl text-card-foreground mb-2">Despesas</h1>
            <p class="text-muted-foreground">Gerencie seus gastos</p>
        </div>
        <button onclick="document.getElementById('expenseForm').classList.toggle('hidden')"
                class="flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
            <x-icon name="plus" class="w-4 h-4" /> Nova Despesa
        </button>
    </div>

    <div class="bg-card border border-border rounded-lg p-6">
        <div class="text-sm text-muted-foreground mb-2">Total de Despesas</div>
        <div class="text-4xl text-destructive">R$ {{ number_format($total, 2, ',', '.') }}</div>
    </div>

    <div id="expenseForm" class="hidden bg-card border border-border rounded-lg p-6">
        <h3 class="text-lg text-card-foreground mb-4">Nova Despesa</h3>
        <form method="POST" action="{{ route('expenses.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-sm text-card-foreground mb-2">Descrição</label>
                <input type="text" name="description" required placeholder="Descrição da despesa"
                       class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm text-card-foreground mb-2">Valor</label>
                <input type="number" step="0.01" name="value" required placeholder="0.00"
                       class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm text-card-foreground mb-2">Data</label>
                <input type="date" name="date" required
                       class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm text-card-foreground mb-2">Categoria</label>
                <select name="category" required
                        class="w-full px-4 py-3 bg-input-background border border-border rounded-lg text-card-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Selecione</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="bg-primary text-primary-foreground px-6 py-2 rounded-lg hover:opacity-90 transition-opacity">
                    Adicionar
                </button>
                <button type="button" onclick="document.getElementById('expenseForm').classList.add('hidden')"
                        class="bg-muted text-muted-foreground px-6 py-2 rounded-lg hover:bg-secondary transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>

    <div class="bg-card border border-border rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-muted">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm text-card-foreground">Descrição</th>
                        <th class="px-6 py-3 text-left text-sm text-card-foreground">Categoria</th>
                        <th class="px-6 py-3 text-left text-sm text-card-foreground">Data</th>
                        <th class="px-6 py-3 text-right text-sm text-card-foreground">Valor</th>
                        <th class="px-6 py-3 text-right text-sm text-card-foreground">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr class="border-t border-border">
                            <td class="px-6 py-4 text-card-foreground">{{ $expense->description }}</td>
                            <td class="px-6 py-4 text-muted-foreground">{{ $expense->category }}</td>
                            <td class="px-6 py-4 text-muted-foreground">{{ $expense->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right text-destructive">R$ {{ number_format($expense->value, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                      onsubmit="return confirm('Remover esta despesa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-destructive hover:opacity-80 transition-opacity">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">
                                Nenhuma despesa cadastrada ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
