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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->string('file_url', 500)->nullable();
            $table->longText('code_content')->nullable();
            $table->string('code_language', 50)->nullable();
            $table->enum('status', ['submitted', 'unsubmitted', 'graded'])->default('submitted');
            $table->dateTime('submitted_at')->useCurrent();
            $table->boolean('is_late')->default(false);
            $table->decimal('points_earned', 6, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->dateTime('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();

            // Unique and Indexes
            $table->unique(['assignment_id', 'student_id'], 'uq_submission');
            $table->index('status', 'idx_submissions_status');
            $table->index('student_id', 'idx_submissions_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
