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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['pdf_upload', 'code_submission', 'both']);
            $table->decimal('max_points', 6, 2);
            $table->dateTime('due_date');
            $table->boolean('allow_late')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('class_id', 'idx_assignments_class');
            $table->index('due_date', 'idx_assignments_due');
            $table->index('is_published', 'idx_assignments_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
