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
        Schema::create('course_prospectus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospectus_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->foreign('prospectus_id')->references('id')->on('prospectuses')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->unique(['prospectus_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_prospectus');
    }
};
