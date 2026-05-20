<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'cpf')) {
                $table->string('cpf')->nullable()->unique()->after('email');
            }

            if (! Schema::hasColumn('users', 'cep')) {
                $table->string('cep')->nullable()->after('cpf');
            }

            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('cep');
            }

            if (! Schema::hasColumn('users', 'rm')) {
                $table->string('rm')->nullable()->unique()->after('phone');
            }
        });

        Schema::table('authorizations', function (Blueprint $table) {
            if (! Schema::hasColumn('authorizations', 'professor_user_id')) {
                $table->foreignId('professor_user_id')
                    ->nullable()
                    ->after('teacher_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('authorizations', 'additional_recipient')) {
                $table->enum('additional_recipient', ['nenhum', 'portaria'])
                    ->default('nenhum')
                    ->after('type');
            }
        });

        if (Schema::hasColumn('authorizations', 'teacher_id') && DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE authorizations DROP FOREIGN KEY authorizations_teacher_id_foreign');
            } catch (Throwable) {
                //
            }

            DB::statement('ALTER TABLE authorizations MODIFY teacher_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            if (Schema::hasColumn('authorizations', 'professor_user_id')) {
                $table->dropConstrainedForeignId('professor_user_id');
            }

            if (Schema::hasColumn('authorizations', 'additional_recipient')) {
                $table->dropColumn('additional_recipient');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            foreach (['cpf', 'cep', 'phone', 'rm'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
