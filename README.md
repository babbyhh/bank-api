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

- Contas
- User
- TransacaoContas

## APIs Disponíveis

### Autenticação

#### POST /api/register
Registra um novo usuário.
- Parâmetros:
  - name: Nome do usuário
  - email: Email do usuário
  - password: Senha do usuário
- Retorno (JSON):
  ```json
  {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com",
      "created_at": "2024-03-21T01:32:38.000000Z",
      "updated_at": "2024-03-21T01:32:38.000000Z"
    },
    "token": "1|laravel_sanctum_token_hash..."
  }
  ```

#### POST /api/login  
Realiza login do usuário.
- Parâmetros:
  - email: Email do usuário
  - password: Senha do usuário
- Retorno (JSON):
  ```json
  {
    "token": "1|laravel_sanctum_token_hash..."
  }
  ```

### Contas

#### POST /api/conta
Cria uma nova conta.
- Headers: Bearer Token
- Parâmetros:
  - numero_conta: Número da conta (inteiro)
  - saldo: Saldo inicial (decimal > 0)
- Retorno (JSON):
  ```json
  {
    "numero_conta": 123456,
    "saldo": 1000.00
  }
  ```

#### GET /api/conta
Consulta uma conta específica.
- Headers: Bearer Token
- Query Parameters:
  - numero_conta: Número da conta
- Retorno (JSON):
  ```json
  {
    "numero_conta": 123456,
    "saldo": 1000.00
  }
  ```

### Transações

#### POST /api/transacao
Realiza uma transação financeira.
- Headers: Bearer Token
- Parâmetros:
  - numero_conta: Número da conta
  - valor: Valor da transação (decimal > 0)
  - forma_pagamento: Forma de pagamento (D: Débito, C: Crédito, P: Pix)
- Retorno (JSON):
  ```json
  {
    "numero_conta": 123456,
    "saldo": 1000.00
  }
  ```
- Taxas:
  - Débito: 3%
  - Crédito: 5%
  - Pix: Sem taxa

#### GET /api/transacao
Lista transações de uma conta.
- Headers: Bearer Token
- Query Parameters:
  - numero_conta: Número da conta
- Retorno (JSON):
  ```json
  {
    "transacoes": [
      {
        "id": 1,
        "numero_conta": 123456,
        "valor": 100.00,
        "forma_pagamento": "P",
        "created_at": "2024-03-20T10:30:00Z"
      },
      {
        "id": 2,
        "numero_conta": 123456,
        "valor": 50.00,
        "forma_pagamento": "D",
        "created_at": "2024-03-20T09:15:00Z"
      }
    ]
  }
  ```
  - Ordenado por data (decrescente)
  - forma_pagamento: "D" (Débito), "C" (Crédito), "P" (Pix)
