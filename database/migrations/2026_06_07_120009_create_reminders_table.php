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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('sent_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->text('message');
            $table->enum('target', ['non_submitters', 'all', 'specific'])->default('non_submitters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
