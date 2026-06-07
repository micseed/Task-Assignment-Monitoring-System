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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->enum('type', ['reminder', 'grade_released', 'deadline_warning', 'system']);
            $table->string('title', 255);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index('recipient_id', 'idx_notifications_recipient');
            $table->index('is_read', 'idx_notifications_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
