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
        Schema::create('meeting_participant', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade'); // Foreign key to meetings
            $table->foreignId('participant_id')->constrained('users')->onDelete('cascade'); // Foreign key to users
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_participant');
    }
};