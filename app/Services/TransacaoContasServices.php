<?php 
namespace App\Services;

use App\Models\Contas;
use App\Models\TransacaoContas;
use App\Exceptions\ContaNaoEncontradaException;
use App\Exceptions\TransacaoException;

class TransacaoContasServices {

    public function calcularTaxa(float $valor, string $forma_pagamento): float
    {
        return match (strtoupper($forma_pagamento)) {
            'D' => $valor * 0.03, // 3% para débito
            'C' => $valor * 0.05, // 5% para crédito
            'P' => 0.0,           // sem taxa para pix
            default => throw new \InvalidArgumentException('Forma de pagamento inválida'),
        };
    }

    public function realizarTransacao(array $dados): array
    {
        
        $conta = Contas::where('numero_conta', $dados['numero_conta'])->first();
        if (!$conta) {
            throw new ContaNaoEncontradaException('Conta não encontrada');
        }
        $taxa = $this->calcularTaxa($dados['valor'], $dados['forma_pagamento']);
        $total = $dados['valor'] + $taxa;
 
        if ($conta->saldo < $total) {
            throw new TransacaoException('Saldo insuficiente');
        }
        $conta->saldo -= $total;
        
        $conta->save();

        $TransacaoContas = TransacaoContas::create($dados);

        return [
            'numero_conta' => (int) $conta->numero_conta,
            'saldo' => (float) $conta->saldo
        ];
    }
    public function getTransacaoContas(int $numero_conta): array
    {
        $transacaoContas = TransacaoContas::where('numero_conta', $numero_conta)
              ->select('numero_conta', 'valor', 'forma_pagamento', 'created_at')
              ->orderBy('created_at', 'desc')
              ->get()
              ->toArray();

        if (!$transacaoContas) {
            throw new TransacaoException('Transação não encontrada');
        }
        
        return $transacaoContas;
    }

}
