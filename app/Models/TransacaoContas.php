<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransacaoContas extends Model
{
    protected $fillable = ['numero_conta', 'valor', 'forma_pagamento'];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }
    
}
