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
        Schema::create('semester_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');

            $table->integer('total_credit_hour')->nullable();

            $table->decimal('semester_gpa', 5, 2)->nullable(); // Decimal with 2 decimal places
            $table->decimal('cumulative_gpa', 5, 2)->nullable(); // Decimal with 2 decimal places
            $table->integer('cumulative_hour')->nullable();
            $table->decimal('grade_points', 5, 2)->nullable(); // Decimal with 2 decimal places
            $table->decimal('cumulative_grade_points', 5, 2)->nullable(); // Decimal with 2 decimal places
            $table->string('status')->default('Not Determined');
            $table->unique(['student_id', 'semester_id']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_registrations');
    }
};
