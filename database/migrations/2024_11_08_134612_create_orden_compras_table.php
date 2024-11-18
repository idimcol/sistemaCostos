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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('proveedor_id');
            $table->date('fecha_orden');
            $table->float('subtotal');
            $table->float('iva');
            $table->float('total');
            $table->string('elaboracion');
            $table->string('autorizacion');
            $table->timestamps();

            $table->foreign('proveedor_id')->references('nit')->on('proveedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compras');
    }
};
