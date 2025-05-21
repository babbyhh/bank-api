<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Contas extends Model
{
    protected $fillable = ['numero_conta', 'saldo'];

     /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasFactory;

    public function transacoes()
    {
        return $this->hasMany(TransacaoContas::class);
    }
    
}
