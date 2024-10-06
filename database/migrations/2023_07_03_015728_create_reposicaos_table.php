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
        Schema::create('reposicaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("solicitante");
            $table->unsignedBigInteger("lead_id");
            $table->string("status")->default("aguardando"); //aguardando, aprovada, rejeitada
            $table->text("descricao")->nullable();
            $table->text("motivo_reprovacao")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reposicaos');
    }
};
