<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('title'); // Project title
            $table->string('business_environment'); // Business environment description
            $table->text('business_need'); // Business need description
            $table->text('objective'); // Project objectives
            $table->string('technologies')->nullable(); // Technologies (can be a FK if needed later)
            $table->string('stakeholders')->nullable(); // Stakeholders (can be a FK if needed later)
            $table->enum('status', ['Em Progresso', 'Concluído', 'Pendente', 'Rejeitado'])->default('Pendente'); // Project status
            $table->boolean('is_public')->default(false); // Is the project public?
            $table->timestamp('start_date')->nullable(); // Project start date
            $table->timestamp('end_date')->nullable(); // Project end date
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade'); // Foreign key to Users
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
