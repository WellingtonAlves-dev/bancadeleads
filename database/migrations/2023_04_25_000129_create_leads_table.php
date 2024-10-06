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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->boolean("ativo")->default(true);
            $table->double("preco");
            $table->dateTime("horario_partida");
            $table->integer("dias_disponivel");
            $table->integer("qtd_vidas");
            //dados users/privados
            $table->string("nome_lead");
            $table->integer("ddd");
            $table->string("telefone")->nullable();
            $table->string("email")->nullable();
            $table->text("extra")->nullable();
            //referencias
            $table->unsignedBigInteger("plano_id");
            $table->unsignedBigInteger("tipo_id");
            $table->foreign("plano_id")->references("id")->on("planos");
            $table->foreign("tipo_id")->references("id")->on("tipos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
