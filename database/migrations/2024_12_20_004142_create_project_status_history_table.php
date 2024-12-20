<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_status_history', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // FK to Projects
            $table->enum('status', ['Pendente', 'Em Progresso', 'Concluído']); // Project status
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade'); // FK to Users (who changed the status)
            $table->timestamp('changed_at'); // Timestamp for when the status was changed
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
        Schema::dropIfExists('project_status_history');
    }
}
