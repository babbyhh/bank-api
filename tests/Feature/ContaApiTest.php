<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\Contas;

class ContaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_cria_conta_com_sucesso()
    {
        $user = User::factory()->create(); 
        $token = $user->createToken('api-token')->plainTextToken;

        $payload = [
            'numero_conta' => 123456,
            'saldo' => 250.75
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/conta', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'numero_conta' => 123456,
                     'saldo' => 250.75
                 ]);

        $this->assertDatabaseHas('contas', $payload);
    }

    public function test_nao_permite_conta_duplicada()
    {
        $user = User::factory()->create(); 
        $token = $user->createToken('api-token')->plainTextToken;


        Contas::create([
            'numero_conta' => 9999,
            'saldo' => 100.00
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/conta', [
            'numero_conta' => 9999,
            'saldo' => 200.00
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['numero_conta']);
    }
    public function test_nao_permite_saldo_menor_ou_igual_zero()
    {
        $user = User::factory()->create(); 
        $token = $user->createToken('api-token')->plainTextToken;

        $payload = [
            'numero_conta' => 123456,
            'saldo' => 0
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/conta', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['saldo']);

        $payload['saldo'] = -100;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/conta', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['saldo']);
    }

    public function test_consulta_conta_pelo_numero()
    {
        $user = User::factory()->create(); 
        Sanctum::actingAs($user); 

        Contas::create([
            'numero_conta' => 7777,
            'saldo' => 500.00
        ]);

        $response = $this->getJson('/api/conta?numero_conta=7777');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'numero_conta' => 7777,
                     'saldo' => 500.00, 
                 ]);
    }
}
