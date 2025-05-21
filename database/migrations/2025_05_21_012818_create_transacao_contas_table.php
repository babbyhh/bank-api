<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transacao_contas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('numero_conta');
            $table->float('valor');
            $table->string('forma_pagamento');
            $table->timestamps();

           $table->foreign('numero_conta')->references('numero_conta')->on('contas')
           ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacao_contas');
    }
};
