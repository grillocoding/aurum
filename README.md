# AURUM — arquivos da aplicação (compatível com Laravel 11, 12 e 13)

Este pacote contém **apenas os arquivos específicos da aplicação** —
nada do skeleton do framework (sem `bootstrap/app.php`, sem `config/*`,
sem `artisan`). Isso porque a partir do Laravel 11 a estrutura mudou
bastante (não existe mais `app/Http/Kernel.php` nem `app/Console/Kernel.php`
— tudo isso ficou dentro do `bootstrap/app.php`), então o skeleton que te
mandei antes (feito pra Laravel 10) não é compatível com sua versão 13.21.1.

A solução: você já tem um projeto Laravel 13 funcional e correto (gerado
pelo `laravel new` ou `composer create-project`). Só precisa copiar estes
arquivos de aplicação por cima — eles são 100% independentes da versão
do framework.

## Como aplicar

A partir da raiz do seu projeto Laravel 13:

```bash
# 1. Copie os arquivos deste pacote por cima do seu projeto
cp -r app/Models/* /caminho/do/seu/projeto/app/Models/
cp -r app/Services /caminho/do/seu/projeto/app/
cp -r app/Http/Controllers/* /caminho/do/seu/projeto/app/Http/Controllers/
cp -r database/migrations/* /caminho/do/seu/projeto/database/migrations/
cp database/seeders/DatabaseSeeder.php /caminho/do/seu/projeto/database/seeders/
cp -r resources/views/* /caminho/do/seu/projeto/resources/views/
cp routes/web.php /caminho/do/seu/projeto/routes/web.php   # SUBSTITUI o arquivo padrão
```

Ou simplesmente descompacte este zip dentro da pasta do seu projeto Laravel
(os caminhos já batem com a estrutura padrão) e confirme sobrescrever
`routes/web.php`, `app/Http/Controllers/Controller.php` e
`database/seeders/DatabaseSeeder.php`.

## O que muda em relação ao pacote anterior (Laravel 10)

- **`app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`,
  `app/Http/Middleware/*`, `config/*`, `bootstrap/*`** — **não precisa copiar nada
  disso**. Seu projeto Laravel 13 já vem com o equivalente configurado
  automaticamente dentro do `bootstrap/app.php`. Os middlewares `auth` e
  `guest` que uso nas rotas (`routes/web.php`) já vêm registrados por padrão
  no framework a partir da v11 — não precisa criar nada.

- **Migration de `users`**: em vez de recriar a tabela inteira (que geraria
  conflito com a migration padrão que o Laravel 13 já cria), incluí uma
  migration **aditiva** —
  `database/migrations/2026_07_02_000000_add_business_fields_to_users_table.php`
  — que só adiciona as colunas extras (`company_name`, `cnpj`, `phone`,
  `address`, `city`, `state`, `cep`, `activity_type`) à tabela `users` padrão
  do Laravel. Não mexe na migration original.

## Passo a passo completo

```bash
# depois de copiar os arquivos acima para dentro do seu projeto:

# se seu .env ainda não está configurado para MySQL:
# DB_CONNECTION=mysql
# DB_DATABASE=aurum
# DB_USERNAME=root
# DB_PASSWORD=sua_senha

php artisan migrate
php artisan db:seed
php artisan serve
```

Login de teste criado pelo seeder:
- **E-mail:** joao@aurum.com
- **Senha:** password

## Estrutura copiada

```
app/Models/User.php, Revenue.php, Expense.php
app/Services/DasCalculator.php                → regra de negócio do DAS-MEI
app/Http/Controllers/*.php                    → todos os controllers
app/Http/Controllers/Auth/*.php               → login e registro
database/migrations/*                          → revenues, expenses, + colunas extras em users
database/seeders/DatabaseSeeder.php            → dados de demonstração
resources/views/**                             → todas as telas (Blade + Tailwind CDN + Chart.js)
routes/web.php                                 → todas as rotas (substitui o arquivo padrão)
```

## Observação sobre o `Controller.php`

O arquivo `app/Http/Controllers/Controller.php` deste pacote é idêntico ao
padrão do Laravel 11+ (`abstract class Controller {}`), então pode
sobrescrever sem medo.
