# AURUM

Sistema de gestão financeira para Microempreendedores Individuais (MEI).

AURUM centraliza o controle de faturamento, despesas, obrigações fiscais e relatórios gerenciais em uma única plataforma, permitindo que o empreendedor tome decisões com base em dados claros e atualizados — sem depender de conhecimento técnico em contabilidade.

## Sobre o projeto

A gestão financeira é um dos principais gargalos do microempreendedor individual: falta de visibilidade sobre o caixa, dificuldade em compreender o cálculo do DAS e ausência de relatórios organizados são problemas recorrentes que impactam diretamente a saúde do negócio.

O AURUM foi desenvolvido para resolver esse problema com uma interface simples, indicadores objetivos e automações que eliminam tarefas manuais — como o cálculo do DAS e o preenchimento de endereço a partir do CEP.

## Funcionalidades

- **Dashboard gerencial** com indicadores de faturamento, despesas, saldo e obrigações do mês, além de gráficos de evolução mensal
- **Controle de faturamento e despesas**, com categorização e histórico completo
- **Relatórios financeiros**, incluindo comparativo mensal, distribuição por categoria e indicadores de performance (margem de lucro, ticket médio, ROI)
- **Exportação de relatórios em PDF**, prontos para envio a contadores ou uso interno
- **Simulador de DAS**, com cálculo automático conforme o tipo de atividade e monitoramento do limite anual de faturamento do MEI
- **Monitoramento de limite anual** integrado ao dashboard principal
- **Gestão de perfil da empresa**, com foto, dados cadastrais e preenchimento automático de endereço via CEP
- **Página institucional (landing page)** para apresentação do produto antes do acesso à plataforma

## Tecnologias utilizadas

| Camada         | Tecnologia                     |
|----------------|---------------------------------|
| Backend        | Laravel 13 (PHP)                |
| Banco de dados | MySQL                           |
| Frontend       | Blade, Tailwind CSS              |
| Visualização   | Chart.js                        |
| Geração de PDF | barryvdh/laravel-dompdf          |

## Instalação e execução local

### Pré-requisitos
- PHP 8.2 ou superior
- Composer
- MySQL

### Passos

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure as credenciais do banco de dados no arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=aurum
DB_USERNAME=root
DB_PASSWORD=
```

Execute as migrations e, opcionalmente, popule o banco com dados de demonstração:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`.

Um usuário de demonstração é criado automaticamente pelo seeder:
- E-mail: `joao@aurum.com`
- Senha: `password`

## Sobre o cálculo do DAS

O valor do DAS-MEI é fixo mensalmente, composto pelo INSS e, conforme a atividade do empreendedor, ICMS e/ou ISS — não havendo variação conforme o faturamento mensal. O ponto de atenção real é o limite anual de faturamento de R$ 81.000,00, cujo acompanhamento é feito automaticamente pela plataforma.

## Roadmap

- Edição de lançamentos de faturamento e despesas
- Integração com contas bancárias (open finance)
- Emissão de notas fiscais
- Aplicativo mobile

## Licença

Projeto proprietário. Todos os direitos reservados.
