<?php

namespace App\Exceptions;

use Exception;

class ContaExistenteException extends Exception
{
    public function __construct()
    {
        parent::__construct('Já existe uma conta cadastrada com este número.');
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ], 422);
    }
} 