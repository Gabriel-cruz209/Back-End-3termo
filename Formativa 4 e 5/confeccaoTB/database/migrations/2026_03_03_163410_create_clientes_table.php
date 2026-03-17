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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); //nome cliente
            $table->string('cpf')->unique(); //cpf cliente deve ser unico
            $table->string('email')->unique(); 
            $table->string('telefone')->nullable(); 
            $table->text('endereco')->nullable();//Nullable caso o cliente nao tenha\queira dar
            $table->timestamps(); //criado e atualizado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
