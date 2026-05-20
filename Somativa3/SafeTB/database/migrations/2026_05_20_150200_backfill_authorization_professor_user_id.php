<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            ! Schema::hasTable('authorizations')
            || ! Schema::hasTable('teachers')
            || ! Schema::hasColumn('authorizations', 'professor_user_id')
            || ! Schema::hasColumn('authorizations', 'teacher_id')
            || ! Schema::hasColumn('teachers', 'user_id')
        ) {
            return;
        }

        DB::table('authorizations')
            ->join('teachers', 'authorizations.teacher_id', '=', 'teachers.id')
            ->whereNull('authorizations.professor_user_id')
            ->whereNotNull('teachers.user_id')
            ->update([
                'authorizations.professor_user_id' => DB::raw('teachers.user_id'),
            ]);
    }

    public function down(): void
    {
        //
    }
};
