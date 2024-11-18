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
        Schema::create('items_orden_compras_cantidads', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden_compra');
            $table->string('item_codigo');
            $table->integer('cantidad');
            $table->decimal('precio', 20, 2);
            $table->timestamps();

            $table->foreign('numero_orden_compra')->references('numero')->on('orden_compras')->onDelete('cascade');
            $table->foreign('item_codigo')->references('codigo')->on('items_orden_compras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_orden_compras_cantidads');
    }
};
