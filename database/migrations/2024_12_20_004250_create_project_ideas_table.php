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
        Schema::create('project_ideas', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // 'name' should be a string
            $table->text('description')->nullable(); // 'description' should be a text field, nullable if optional
            $table->foreignId('proponent_id')->constrained('users'); // Foreign key referencing 'users' table
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_ideas');
    }
};