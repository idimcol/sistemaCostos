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
        Schema::create('servicios_s_d_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('servicio_id');
            $table->integer('sdp_id');
            $table->decimal('valor_servicio');
            $table->timestamps();

            $table->foreign('sdp_id')->references('numero_sdp')->on('sdps')->onDelete('cascade');
            $table->foreign('servicio_id')->references('codigo')->on('servicios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_s_d_p_s');
    }
};
