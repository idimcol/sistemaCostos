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
        Schema::table('items_ingreso', function (Blueprint $table) {
            $table->string('remision_ingreso_id');
            $table->string('item_id');
            $table->integer('cantidad');

            $table->foreign('remision_ingreso_id')->references('codigo')->on('remisiones_ingresos')->onDelete('cascade');
            $table->foreign('item_id')->references('codigo')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_ingreso', function (Blueprint $table) {
            //
        });
    }
};