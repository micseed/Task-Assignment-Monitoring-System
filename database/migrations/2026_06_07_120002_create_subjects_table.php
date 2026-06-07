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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code', 30)->unique();
            $table->string('subject_name', 200);
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('school_year', 20);
            $table->enum('semester', ['1st', '2nd', 'Summer']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
