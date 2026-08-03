<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'João Silva',
            'email' => 'joao@aurum.com',
            'password' => Hash::make('password'),
            'company_name' => 'Silva Consultoria LTDA',
            'cnpj' => '12.345.678/0001-90',
            'phone' => '(11) 99999-9999',
            'address' => 'Rua Exemplo, 123',
            'city' => 'São Paulo',
            'state' => 'SP',
            'cep' => '01234-567',
            'activity_type' => 'servicos',
        ]);

        // Receitas de exemplo nos últimos 6 meses
        $revenueSamples = [
            ['description' => 'Serviço de Consultoria', 'category' => 'Serviços', 'value' => 5000],
            ['description' => 'Desenvolvimento Web', 'category' => 'Serviços', 'value' => 8000],
            ['description' => 'Manutenção de Sistema', 'category' => 'Serviços', 'value' => 3000],
            ['description' => 'Treinamento', 'category' => 'Educação', 'value' => 3000],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            foreach ($revenueSamples as $index => $sample) {
                Revenue::create([
                    'user_id' => $user->id,
                    'description' => $sample['description'],
                    'value' => $sample['value'] + ($i * 200) - ($index * 100),
                    'date' => $month->copy()->startOfMonth()->addDays($index * 5 + 1),
                    'category' => $sample['category'],
                ]);
            }
        }

        // Despesas de exemplo nos últimos 6 meses
        $expenseSamples = [
            ['description' => 'Aluguel Escritório', 'category' => 'Fixas', 'value' => 2000],
            ['description' => 'Internet', 'category' => 'Fixas', 'value' => 150],
            ['description' => 'Equipamentos', 'category' => 'Variáveis', 'value' => 1500],
            ['description' => 'Marketing', 'category' => 'Marketing', 'value' => 800],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            foreach ($expenseSamples as $index => $sample) {
                Expense::create([
                    'user_id' => $user->id,
                    'description' => $sample['description'],
                    'value' => $sample['value'] + ($i * 100) - ($index * 50),
                    'date' => $month->copy()->startOfMonth()->addDays($index * 5 + 3),
                    'category' => $sample['category'],
                ]);
            }
        }
    }
}
