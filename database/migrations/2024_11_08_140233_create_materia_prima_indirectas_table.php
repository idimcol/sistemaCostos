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
        Schema::create('materia_prima_indirectas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('descripcion');
            $table->string('proveedor');
            $table->string('proveedor_id');
            $table->string('numero_factura');
            $table->string('numero_orden_compra');
            $table->decimal('precio_unit', 20, 2);
            $table->decimal('valor', 20, 2);
            $table->timestamps();

            $table->foreign('proveedor_id')->references('nit')->on('proveedores')->onDelete('cascade');
            $table->foreign('numero_orden_compra')->references('numero')->on('orden_compras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_prima_indirectas');
    }
};
