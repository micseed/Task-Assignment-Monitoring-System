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
        Schema::create('submission_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_url', 500)->nullable();
            $table->longText('code_content')->nullable();
            $table->enum('action', ['submitted', 'unsubmitted', 'resubmitted']);
            $table->dateTime('action_at')->useCurrent();
            $table->timestamps();

            $table->index('submission_id', 'idx_sub_history_submission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_history');
    }
};
