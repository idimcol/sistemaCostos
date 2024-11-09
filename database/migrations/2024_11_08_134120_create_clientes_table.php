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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nit')->unique();
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('contacto')->nullable();
            $table->string('correo')->unique();
            $table->unsignedBigInteger('comerciales_id');
            $table->unsignedBigInteger('departamento');
            $table->unsignedBigInteger('ciudad');
            $table->timestamps();

            $table->foreign('comerciales_id')->references('id')->on('vendedores')->onDelete('cascade');
            $table->foreign('departamento')->references('id')->on('departamentos')->onDelete('cascade');
            $table->foreign('ciudad')->references('id')->on('municipios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
