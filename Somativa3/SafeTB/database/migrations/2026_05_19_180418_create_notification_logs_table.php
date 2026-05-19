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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_id')->constrained('authorizations')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('guardian_id')->nullable()->constrained('guardians')->nullOnDelete();
            $table->enum('channel', ['email', 'whatsapp_simulado', 'log']);
            $table->string('recipient')->nullable();
            $table->text('message');
            $table->enum('status', ['enviado', 'erro', 'simulado']);
            $table->dateTime('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
