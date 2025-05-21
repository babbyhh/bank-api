<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contas;
use App\Services\TransacaoContasServices;


class TransacaoContasController extends Controller
{
    protected $contaService;

    public function __construct(TransacaoContasServices $transacaoContasServices)
    {
        $this->transacaoContasServices = $transacaoContasServices;
    }
    public function transaction(Request $request)
    {
        $dados = $request->validate([
            'numero_conta' => 'required|integer',
            'valor' => 'required|numeric|gt:0',
            'forma_pagamento' => 'required|in:D,C,P',
        ],[
            'numero_conta.integer' => 'O número da conta deve ser um número inteiro.',
            'valor.numeric' => 'O valor deve ser um número.',
            'valor.gt' => 'O valor deve ser maior que zero.',
            'forma_pagamento.in' => 'A forma de pagamento deve ser Debito(D), Credito(C) ou Pix(P).'
        ]);

        $dados = $this->transacaoContasServices->realizarTransacao($dados);
        

        return response()->json($dados, 201);
    }
    public function getTransacaoContas(Request $request)
    {
        $dados = $request->validate([
            'numero_conta' => 'required|integer',
        ]);
        $numeroConta = $request->query('numero_conta');

        $dados = $this->transacaoContasServices->getTransacaoContas($numeroConta);

        return response()->json($dados, 200);
    }
}
