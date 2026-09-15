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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_task_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->unsignedTinyInteger('progress'); // progres saat itu dicatat
            $table->text('description');   // uraian pekerjaan
            $table->text('obstacle')->nullable();     // kendala
            $table->text('next_plan')->nullable();    // rencana berikutnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
