<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data uji coba dikosongkan: tahapan lama tidak bisa dipetakan otomatis ke tahapan baru.
        DB::table('daily_reports')->delete();
        DB::table('notifications')->delete();
        DB::table('job_tasks')->delete();
        DB::table('job_stages')->delete();

        Schema::table('job_stages', function (Blueprint $table) {
            $table->dropColumn('group');
        });

        Schema::table('job_stages', function (Blueprint $table) {
            $table->foreignId('category_id')->after('id')->constrained('document_categories')->restrictOnDelete();
            $table->unsignedTinyInteger('phase')->after('name'); // Progress 1-4
        });
    }

    public function down(): void
    {
        DB::table('daily_reports')->delete();
        DB::table('notifications')->delete();
        DB::table('job_tasks')->delete();
        DB::table('job_stages')->delete();

        Schema::table('job_stages', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'phase']);
        });

        Schema::table('job_stages', function (Blueprint $table) {
            $table->string('group', 20)->after('weight');
        });
    }
};
