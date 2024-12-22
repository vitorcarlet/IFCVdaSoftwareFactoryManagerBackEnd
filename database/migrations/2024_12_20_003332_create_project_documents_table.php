<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // FK to Projects
            $table->string('name'); // Document name
            $table->string('path'); // File path
            $table->bigInteger('size'); // File size in bytes
            $table->string('version')->default('1.0'); // Document version
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
        Schema::dropIfExists('project_documents');
    }
}
