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
        Schema::create('materia_prima_indirecta_costos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_indirecta_id')->constrained('materia_prima_indirectas')->onDelete('cascade');
            $table->foreignId('costos_produccion_id')->constrained('costos_sdp_produccions')->onDelete('cascade');
            $table->integer('cantidad');
            $table->unsignedBigInteger('articulo_id');
            $table->string('articulo_descripcion');
            $table->timestamps();
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_prima_indirecta_costos');
    }
};
