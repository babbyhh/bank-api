<?php 
namespace App\Services;

use App\Models\Contas;
use App\Exceptions\ContaNaoEncontradaException;

class ContaService
{
    public function criar(array $dados): array
    {
        $conta = Contas::create($dados);
        
        return [
            'numero_conta' => (int) $conta->numero_conta,
            'saldo' => (float) $conta->saldo
        ];
    }

    public function buscar(int $numero_conta): array
    {
        $conta = Contas::where('numero_conta', $numero_conta)
            ->select('numero_conta', 'saldo')
            ->first();

        if (!$conta) {
            throw new ContaNaoEncontradaException('Conta não encontrada');
        }

        return [
            'numero_conta' => (int) $conta->numero_conta,
            'saldo' => (float) $conta->saldo
        ];
    }
}
