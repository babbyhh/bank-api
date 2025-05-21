<?php

namespace App\Exceptions;

use Exception;

class ContaNaoEncontradaException extends Exception
{
    public function __construct()
    {
        parent::__construct('Conta não encontrada.');
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ], 404);
    }
} 