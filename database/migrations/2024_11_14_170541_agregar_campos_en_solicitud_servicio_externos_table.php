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
        Schema::table('solicitud_servicio_externos', function (Blueprint $table) {
            $table->integer('numero_ste')->unique();
            $table->string('observaciones')->nullable();
            $table->string('despacho')->nullable();
            $table->enum('departamento', array_column(Departamento::cases(), 'value'));
            $table->string('recibido')->nullable();
            $table->date('fecha_salida_planta');
            $table->string('proveedor_id');
            $table->string('ordenCompra_id');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo');
            $table->string('contacto');

            $table->foreign('proveedor_id')->references('nit')->on('proveedores')->onDelete('cascade');
            $table->foreign('ordenCompra_id')->references('numero')->on('orden_compras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud_servicio_externos', function (Blueprint $table) {
            //
        });
    }
};
