<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\Contas;

class TransacoesContaApiTest extends TestCase
{
   
    use RefreshDatabase;

    public function test_transacao_com_forma_pagamento_debito()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        // Cria a conta com saldo inicial
        $conta = Contas::create([
            'numero_conta' => 123456,
            'saldo' => 500.00
        ]);

        $payload = [
            'numero_conta' => 123456,
            'valor' => 100.00,
            'forma_pagamento' => 'D' // débito = 3%
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/transacao', $payload);

        // Verifica o novo saldo (500 - 100 - 3% de taxa = 500 - 103 = 397)
        $contaAtualizada = Contas::where('numero_conta', 123456)->first();
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'numero_conta' => 123456,
                     'saldo' => $contaAtualizada->saldo
                 ]);

    }
   
    public function test_transacao_com_forma_pagamento_credito()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        Contas::create([
            'numero_conta' => 1111,
            'saldo' => 100.00
        ]);

        $payload = [
            'numero_conta' => 1111,
            'valor' => 50.00,
            'forma_pagamento' => 'C'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/transacao', $payload);

        // 5% de 50 = 2.50 -> total = 52.50 → saldo final: 47.50
        $contaAtualizada = Contas::where('numero_conta', 1111)->first();
        $response->assertStatus(201)
                ->assertJsonFragment([
                    'numero_conta' => 1111,
                    'saldo' => $contaAtualizada->saldo
                ]);
    }
    public function test_transacao_com_forma_pagamento_pix()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        Contas::create([
            'numero_conta' => 1112,
            'saldo' => 100.00
        ]);

        $payload = [
            'numero_conta' => 1112,
            'valor' => 50.00,
            'forma_pagamento' => 'P'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/transacao', $payload);

        // 0% de 50 = 0.00 -> total = 50.00 → saldo final: 50.00
        $contaAtualizada = Contas::where('numero_conta', 1112)->first();
        $response->assertStatus(201)
                ->assertJsonFragment([
                    'numero_conta' => 1112,
                    'saldo' => $contaAtualizada->saldo
                ]);
    }
    public function test_falha_quando_saldo_insuficiente()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        // Cria conta com saldo baixo
        Contas::create([
            'numero_conta' => 123456,
            'saldo' => 50.00
        ]);

        $payload = [
            'numero_conta' => 123456,
            'valor' => 100.00,
            'forma_pagamento' => 'C' // crédito = 5% taxa => total 105.00
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/transacao', $payload);

        $response->assertStatus(404)
                ->assertJson([
                    'error' => 'Saldo insuficiente'
                ]);
    }
}
