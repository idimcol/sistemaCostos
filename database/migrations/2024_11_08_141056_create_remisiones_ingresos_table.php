<?php

use App\Enums\Departamento;
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
        Schema::create('remisiones_ingresos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('proveedor_id')->nullable();
            $table->date('fecha_ingreso');
            $table->string('observaciones')->nullable();
            $table->string('despacho')->nullable();
            $table->enum('departamento', array_column(Departamento::cases(), 'value'));
            $table->string('recibido')->nullable();
            $table->integer('sdp_id');
            $table->string('cliente_nit')->nullable();
            $table->timestamps();

            $table->foreign('proveedor_id')->references('nit')->on('proveedores')->onDelete('cascade');
            $table->foreign('sdp_id')->references('numero_sdp')->on('sdps')->onDelete('cascade');
            $table->foreign('cliente_nit')->references('nit')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remisiones_ingresos');
    }
};
