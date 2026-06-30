# Econet Company

Aplicação CRUD de empresas e funcionários com:

- Laravel 12
- Vue 3
- Vue Router
- Axios
- Bootstrap 5
- SQLite

## Requisitos

- PHP 8.2+
- Composer
- Node.js 20+
- npm

## Instalação

1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd econet-company
```

2. Instale as dependências do backend

```bash
composer install
```

3. Instale as dependências do frontend

```bash
npm install
```

4. Crie o arquivo de ambiente

```bash
cp .env.example .env
```

5. Gere a chave da aplicação

```bash
php artisan key:generate
```

6. Garanta que o banco SQLite exista

```bash
touch database/database.sqlite
```

7. Rode as migrations e a seeder (seeder já cria user / company / employee)

```bash
php artisan migrate --seed
```

## Usuário para teste

- email: `pedro.laskawski@econet.com`
- senha: `password`

## Executando o projeto

Em um terminal, suba o backend:

```bash
php artisan serve
```

Em outro terminal, suba o frontend:

```bash
npm run dev
```

Abra no navegador:

```text
http://127.0.0.1:8000
```

Se o usuário não estiver autenticado:

- é redirecionado para `/login`

Se o usuário já estiver autenticado e tentar abrir `/login`:

- é redirecionado para `/companies`

## Observações

- O projeto usa `database/database.sqlite`
- O CNPJ das empresas é validado como único apenas entre empresas ativas
- Empresas e funcionários são gerenciados via modais no frontend
- Não fiz com docker, pois uso <a href="https://asdf-vm.com/guide/getting-started.html">ASDF</a> para versionamento de pacotes e minha máquina é um Mac e precisa do Docker Desktop para rodar docker
