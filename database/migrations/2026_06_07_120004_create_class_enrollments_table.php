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
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('status', ['active', 'dropped', 'completed'])->default('active');
            $table->timestamps();

            $table->unique(['class_id', 'student_id'], 'uq_enrollment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};
