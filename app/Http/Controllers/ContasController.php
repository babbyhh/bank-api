<?php
namespace App\Http\Controllers;

use App\Services\ContaService;
use Illuminate\Http\Request;

class ContasController extends Controller
{
    protected $contaService;

    public function __construct(ContaService $contaService)
    {
        $this->contaService = $contaService;
    }


    public function index() {
        
    }
    public function store(Request $request)
    {
        $dados = $request->validate([
            'numero_conta' => 'required|integer|unique:contas',
            'saldo' => 'required|decimal:2|gt:0',
        ],[
            'numero_conta.unique' => 'Já existe uma conta cadastrada com este número.',
            'saldo.decimal' => 'O saldo deve ser um número decimal com 2 casas decimais.',
            'saldo.gt' => 'O saldo deve ser maior que zero.'
        ]);

        $dados['saldo'] = (float) $dados['saldo'];
        $conta = $this->contaService->criar($dados);

        return response()->json($conta, 201);
    }

    public function show(Request $request)
    {
        $request->validate([
            'numero_conta' => 'required|integer'
        ], [
            'numero_conta.integer' => 'O número da conta deve ser um número inteiro.'
        ]);

        $numeroConta = (int) $request->query('numero_conta');
        
        $conta = $this->contaService->buscar($numeroConta);

        return response()->json($conta);
    }
}
