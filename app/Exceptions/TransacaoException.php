<?php

namespace App\Exceptions;

use Exception;

class TransacaoException extends Exception
{
    public function __construct()
    {
        parent::__construct('Saldo insuficiente');
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ], 404);
    }
} 