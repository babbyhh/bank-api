# API de Contas e Transações

API REST para gerenciamento de contas e transações financeiras.

## Tecnologias Utilizadas

- PHP 8.4
- Laravel 12.x 
- SQLite (via arquivo)
- PHPUnit para testes

## Instalação

1. Clone o repositório
2. Execute `composer install`
3. O banco de dados SQLite será criado automaticamente em `database/database.sqlite`
4. Execute as migrations: `php artisan migrate`
5. Execute os testes: `php artisan test`

## Migrations

O sistema possui as seguintes tabelas:

### Contas
### User
### TransacaoContas
## APIs Disponíveis

### Autenticação

#### POST /api/register
Registra um novo usuário.
- Parâmetros:
  - name: Nome do usuário
  - email: Email do usuário
  - password: Senha do usuário
- Retorno: Token de acesso

#### POST /api/login  
Realiza login do usuário.
- Parâmetros:
  - email: Email do usuário
  - password: Senha do usuário
- Retorno: Token de acesso

### Contas

#### POST /api/conta
Cria uma nova conta.
- Headers: Bearer Token
- Parâmetros:
  - numero_conta: Número da conta (inteiro)
  - saldo: Saldo inicial (decimal > 0)
- Retorno: Dados da conta criada

### Transações

#### POST /api/transacao
Realiza uma transação financeira.
- Headers: Bearer Token
- Parâmetros:
  - numero_conta: Número da conta
  - valor: Valor da transação (decimal > 0)
  - forma_pagamento: Forma de pagamento (D: Débito, C: Crédito, P: Pix)
- Retorno: Número da conta e saldo atualizado
- Taxas:
  - Débito: 3%
  - Crédito: 5%
  - Pix: Sem taxa

#### GET /api/transacao
Lista transações de uma conta.
- Headers: Bearer Token
- Query Parameters:
  - numero_conta: Número da conta
- Retorno: Lista de transações ordenadas por data (decrescente)
