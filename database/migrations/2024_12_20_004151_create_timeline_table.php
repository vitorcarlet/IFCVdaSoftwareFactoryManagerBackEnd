<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeline', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // FK to Projects
            $table->date('date'); // Timeline date
            $table->text('description'); // Description of the event
            $table->string('attachment_path')->nullable(); // Path for an optional attachment
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
        Schema::dropIfExists('timeline');
    }
}
