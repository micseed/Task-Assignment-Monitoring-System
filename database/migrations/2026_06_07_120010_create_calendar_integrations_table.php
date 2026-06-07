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
        Schema::create('calendar_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade')->onUpdate('cascade');
            $table->string('google_event_id', 255);
            $table->timestamps();

            $table->unique(['student_id', 'assignment_id'], 'uq_calendar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_integrations');
    }
};
