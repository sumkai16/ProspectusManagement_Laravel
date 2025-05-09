<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Program;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->text('program_description')->nullable();
            $table->string('program_department')->nullable();
            $table->unsignedBigInteger('status_id')->nullable()->default(1);
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
        });

        $programs = [
            ['program_name' => 'Bachelor of Science in Information Technology', 
                'program_description' => 'College of Information Technology', 
                'program_department' => 'BSIT'],
            ['program_name' => 'Bachelor of Science in Computer Science', 
                'program_description' => 'College of Computer Studies', 
                'program_department' => 'BSCS'],
        ];  

        foreach($programs as $program){
            Program::create($program);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
