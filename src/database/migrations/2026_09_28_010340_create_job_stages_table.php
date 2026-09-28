<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('weight'); // progress kumulatif (0-100) saat tahapan tercapai
            $table->string('group', 20);           // draft, revisi, sidang, final
            $table->unsignedSmallInteger('order_no')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_stages');
    }
};
