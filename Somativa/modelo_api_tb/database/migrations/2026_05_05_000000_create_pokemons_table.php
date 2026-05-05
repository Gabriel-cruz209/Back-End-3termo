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
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pokemon_id')->unique();
            $table->string('nome');
            $table->string('tipo_primario', 80);
            $table->string('tipo_secundario', 80)->nullable();
            $table->unsignedSmallInteger('hp');
            $table->unsignedSmallInteger('ataque');
            $table->unsignedSmallInteger('defesa');
            $table->unsignedSmallInteger('sp_ataque');
            $table->unsignedSmallInteger('sp_defesa');
            $table->unsignedSmallInteger('velocidade');
            $table->unsignedSmallInteger('altura');
            $table->unsignedSmallInteger('peso');
            $table->string('imagem_url', 500)->nullable();
            $table->string('imagem_local')->nullable();
            $table->text('flavor_text')->nullable();
            $table->string('apelido')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
