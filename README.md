# AURUM

Sistema de gestão financeira para Microempreendedores Individuais (MEI).

AURUM centraliza o controle de faturamento, despesas, obrigações fiscais e relatórios gerenciais em uma única plataforma, permitindo que o empreendedor tome decisões com base em dados claros e atualizados — sem depender de conhecimento técnico em contabilidade.

## Sobre o projeto

A gestão financeira é um dos principais gargalos do microempreendedor individual: falta de visibilidade sobre o caixa, dificuldade em compreender o cálculo do DAS e ausência de relatórios organizados são problemas recorrentes que impactam diretamente a saúde do negócio.

O AURUM foi desenvolvido para resolver esse problema com uma interface simples, indicadores objetivos e automações que eliminam tarefas manuais — como o cálculo do DAS, a classificação da atividade a partir do CNPJ e o preenchimento de endereço a partir do CEP.

## Funcionalidades

- **Dashboard gerencial** com indicadores de faturamento, despesas, saldo e obrigações do mês, gráficos de evolução mensal e monitoramento do limite anual de faturamento do MEI
- **Controle de faturamento e despesas**, com categorização e histórico completo
- **Relatórios financeiros**, incluindo comparativo mensal, distribuição por categoria e indicadores de performance (margem de lucro, ticket médio, ROI), com exportação em PDF
- **Simulador de DAS**, com cálculo automático conforme o tipo de atividade e monitoramento do limite anual de faturamento
- **Notificações automáticas por e-mail**: alertas ao atingir 80%, 90% e 100% do limite anual do MEI, além de lembretes de vencimento do DAS (3 dias antes e no dia 20)
- **Busca automática de dados por CNPJ**, com preenchimento de razão social, endereço e sugestão do tipo de atividade para o cálculo do DAS
- **Gestão de perfil da empresa**, com foto, dados cadastrais e preenchimento automático de endereço via CEP
- **Página institucional (landing page)** para apresentação do produto antes do acesso à plataforma

## Tecnologias utilizadas

| Camada              | Tecnologia                          |
|---------------------|---------------------------------------|
| Backend              | Laravel (PHP)                        |
| Banco de dados       | MySQL                                |
| Frontend             | Blade, Tailwind CSS                  |
| Visualização de dados| Chart.js                             |
| Geração de PDF       | barryvdh/laravel-dompdf              |
| Envio de e-mail      | API transacional (Resend, compatível com Postmark/Mailgun) |
| Consulta de CNPJ     | BrasilAPI                            |
| Consulta de CEP      | ViaCEP                               |

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

Configure o envio de e-mails (necessário para os alertas de limite e lembretes de DAS):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=sua-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="AURUM"
```

Execute as migrations e, opcionalmente, popule o banco com dados de demonstração:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Em um terminal separado, mantenha o agendador ativo para que os lembretes de DAS e o reset anual dos alertas de limite funcionem:

```bash
php artisan schedule:work
```

A aplicação estará disponível em `http://localhost:8000`.

Um usuário de demonstração é criado automaticamente pelo seeder:
- E-mail: `joao@aurum.com`
- Senha: `password`

## Sobre o cálculo do DAS

O valor do DAS-MEI é fixo mensalmente, composto pelo INSS e, conforme a atividade do empreendedor, ICMS e/ou ISS — não havendo variação conforme o faturamento mensal. O ponto de atenção real é o limite anual de faturamento de R$ 81.000,00, cujo acompanhamento é feito automaticamente pela plataforma, com alertas por e-mail ao atingir 80%, 90% e 100% do teto.

A sugestão de atividade a partir do CNPJ é feita por classificação automática do CNAE e deve ser confirmada com um contador antes de ser considerada definitiva para fins fiscais.

## Roadmap

- Edição de lançamentos de faturamento e despesas
- Integração com contas bancárias (open finance)
- Emissão de notas fiscais
- Aplicativo mobile

## Licença

Projeto proprietário. Todos os direitos reservados.
