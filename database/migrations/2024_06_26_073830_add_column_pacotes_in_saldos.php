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
            $table->boolean("pacote")->default(false);
            $table->integer("pacote_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldos', function (Blueprint $table) {
            $table->dropColumn("pacote");
            $table->dropColumn("pacote_id");
        });
    }
};
