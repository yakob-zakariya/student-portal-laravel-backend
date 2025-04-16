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
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_course_semester_id')->constrained('batch_course_semester')->onDelete('cascade');

            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');

            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');


            $table->unique([
                'batch_course_semester_id',
                'teacher_id',
                'section_id'
            ], 'teacher_assignments_unique');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
