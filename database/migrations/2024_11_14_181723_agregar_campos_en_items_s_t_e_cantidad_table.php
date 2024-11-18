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
        Schema::table('items_s_t_e_cantidad', function (Blueprint $table) {
            $table->unsignedBigInteger('item_ste_id');
            $table->Integer('solicitud_servicio_externo_id');
            $table->integer('cantidad');

            $table->foreign('solicitud_servicio_externo_id')->references('numero_ste')->on('solicitud_servicio_externos')->onDelete('cascade');
            $table->foreign('item_ste_id')->references('id')->on('items_s_t_e')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_s_t_e_cantidad', function (Blueprint $table) {
            //
        });
    }
};
