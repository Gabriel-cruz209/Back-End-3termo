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
        Schema::create('authorization_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_id')->constrained('authorizations')->cascadeOnDelete();
            $table->integer('lesson_number');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['presente', 'atraso_sem_falta', 'falta_justificada', 'falta_nao_justificada']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorization_lessons');
    }
};
