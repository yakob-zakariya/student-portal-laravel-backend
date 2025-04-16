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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');

            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade');

            $table->unique(['id', 'user_id'], 'student_user_id_unique');
            $table->unique(['id', 'batch_id'], 'student_batch_id_unique');
            $table->unique(['id', 'section_id'], 'student_section_id_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
