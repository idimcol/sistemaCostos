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
        Schema::create('tiempos_produccions', function (Blueprint $table) {
            $table->id();
            $table->string('dia');
            $table->string('mes');
            $table->string('año');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('operativo_id');
            $table->string('proseso_id');
            $table->Integer('sdp_id');
            $table->string('nombre_operario');
            $table->string('nombre_servicio');
            $table->decimal('valor_total_horas', 20, 2);
            $table->decimal('horas', 8, 2);
            $table->timestamps();

            $table->foreign('proseso_id')->references('codigo')->on('servicios')->onDelete('cascade');
            $table->foreign('operativo_id')->references('codigo')->on('operativos')->onDelete('cascade');
            $table->foreign('sdp_id')->references('numero_sdp')->on('sdps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiempos_produccions');
    }
};
