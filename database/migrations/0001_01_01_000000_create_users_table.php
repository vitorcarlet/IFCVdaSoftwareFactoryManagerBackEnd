<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name');
            $table->string('cpf')->unique(); // Unique CPF (Brazilian ID)
            $table->date('birth_date'); // Birth date
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Gender
            $table->boolean('is_active')->default(true); // User active status
            $table->string('registration_number')->unique(); // Registration Number
            $table->timestamps(); // created_at and updated_at

            // Add indices for performance optimization
            $table->index(['cpf', 'registration_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

