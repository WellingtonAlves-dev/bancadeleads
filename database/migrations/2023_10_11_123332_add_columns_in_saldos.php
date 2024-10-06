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
        Schema::table('saldos', function (Blueprint $table) {
            $table->double("saldo_anterior")->nullable();
            $table->double("saldo_atual")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldos', function (Blueprint $table) {
            $table->dropColumn("saldo_anterior");
            $table->dropColumn("saldo_atual");
        });
    }
};
