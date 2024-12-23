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
		Schema::create('meetings_participants', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('meeting_id');
			$table->unsignedBigInteger('participant_id');
			$table->timestamps();

			// Foreign keys with onDelete('cascade')
			$table->foreign('meeting_id')
				->references('id')
				->on('meetings')
				->onDelete('cascade');

			$table->foreign('participant_id')
				->references('id')
				->on('users')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('meetings_participants');
	}
};

