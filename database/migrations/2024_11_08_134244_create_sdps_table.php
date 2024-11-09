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
        Schema::create('sdps', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->integer('numero_sdp')->unique();
            $table->string('cliente_nit');
            $table->unsignedBigInteger('vendedor_id');
            $table->date('fecha_despacho_comercial');
            $table->date('fecha_despacho_produccion')->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->string('requisitos_cliente', 255)->nullable();
            $table->string('orden_compra')->nullable();
            $table->string('memoria_calculo')->nullable();
            $table->enum('estado', ['abierto', 'cerrado'])->default('abierto');
            $table->timestamps();

            $table->foreign('cliente_nit')->references('nit')->on('clientes')->onDelete('cascade');
            $table->foreign('vendedor_id')->references('id')->on('vendedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdps');
    }
};
