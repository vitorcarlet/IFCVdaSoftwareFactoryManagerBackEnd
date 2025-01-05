<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Modificando a coluna 'status' para incluir novos valores no ENUM
            $table->enum('status', ['Em Progresso', 'Concluído', 'Pendente', 'Rejeitado', 'Novo'])->default('Pendente')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Voltando para os valores originais do ENUM
            $table->enum('status', ['Em Progresso', 'Concluído', 'Pendente', 'Rejeitado'])->default('Pendente')->change();
        });
    }
};
