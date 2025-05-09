<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code');
            $table->string('course_description');
            $table->integer('course_units');
            $table->timestamps();
        });
        $courses = [
            [
                'course_code' => 'CS101',
                'course_description' => 'Introduction to Computer Science',
                'course_units' => 3,
            ],
        ];
        foreach($courses as $courses){
            Course::create($courses);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
