<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data pekerjaan & daily report lama masih dummy -> aman dikosongkan.
        // notifications juga dikosongkan karena isinya refer ke job lama.
        DB::table('daily_reports')->delete();
        DB::table('notifications')->delete();
        DB::table('job_tasks')->delete();

        Schema::table('job_tasks', function (Blueprint $table) {
            $table->dropForeign(['document_type_id']);
            $table->dropColumn(['document_type_id', 'stage']);
        });

        Schema::table('job_tasks', function (Blueprint $table) {
            $table->foreignId('document_category_id')->after('employee_id')->constrained('document_categories')->restrictOnDelete();
            $table->foreignId('authority_id')->nullable()->after('document_category_id')->constrained('authorities')->nullOnDelete();
            $table->foreignId('stage_id')->after('authority_id')->constrained('job_stages')->restrictOnDelete();
        });

        Schema::table('daily_reports', function (Blueprint $table) {
            $table->foreignId('stage_id')->after('job_task_id')->constrained('job_stages')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
        });

        Schema::table('job_tasks', function (Blueprint $table) {
            $table->dropForeign(['document_category_id']);
            $table->dropForeign(['authority_id']);
            $table->dropForeign(['stage_id']);
            $table->dropColumn(['document_category_id', 'authority_id', 'stage_id']);
        });

        Schema::table('job_tasks', function (Blueprint $table) {
            $table->foreignId('document_type_id')->after('employee_id')->constrained();
            $table->enum('stage', ['draft', 'revisi', 'sidang', 'final'])->default('draft');
        });
    }
};
