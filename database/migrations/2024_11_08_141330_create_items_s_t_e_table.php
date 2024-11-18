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
        Schema::create('items_s_t_e', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_servicio_externo_id');
            $table->string('descripcion')->nullable();
            $table->string('servicio_requerido')->nullable();
            $table->string('dureza_HRC')->nullable();
            $table->timestamps();

            $table->foreign('solicitud_servicio_externo_id')->references('id')->on('solicitud_servicio_externos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_s_t_e');
    }
};
