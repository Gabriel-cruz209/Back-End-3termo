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
        Schema::create('authorizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('school_class_id')->constrained('school_classes');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('created_by')->constrained('users');
            $table->enum('type', ['entrada', 'saida']);
            $table->enum('status', ['rascunho', 'aguardando_professor', 'aprovada_professor', 'recusada_professor', 'aguardando_portaria', 'validada_portaria', 'concluida', 'cancelada'])->default('rascunho');
            $table->date('authorization_date');
            $table->time('scheduled_time');
            $table->dateTime('real_time')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('has_absence')->default(false);
            $table->integer('absence_count')->default(0);
            $table->dateTime('teacher_validated_at')->nullable();
            $table->foreignId('teacher_validated_by')->nullable()->constrained('users');
            $table->dateTime('gate_validated_at')->nullable();
            $table->foreignId('gate_validated_by')->nullable()->constrained('users');
            $table->dateTime('canceled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorizations');
    }
};
