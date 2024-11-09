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
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajador_id')->constrained('trabajadors');
            $table->unsignedBigInteger('paquete_nomina_id')->nullable();
            $table->integer('año');
            $table->integer('mes');
            $table->string('periodo_pago');
            $table->decimal('devengado_trabajados', 20, 2)->nullable();
            $table->decimal('devengado_incapacidad', 20, 2)->nullable();
            $table->decimal('devengado_vacaciones', 20, 2)->nullable();
            $table->decimal('devengado_remunerados', 20, 2)->nullable();
            $table->decimal('pension', 20, 2)->nullable();
            $table->decimal('salud', 20, 2)->nullable();
            $table->decimal('total_devengado', 20, 2)->nullable();
            $table->decimal('total_deducido', 20, 2)->nullable();
            $table->decimal('total_a_pagar', 20, 2)->nullable();
            $table->decimal('suspencion', 20, 2)->nullable();
            $table->decimal('bonificacion_auxilio', 20, 2)->nullable();
            $table->decimal('celular', 20, 2)->nullable();
            $table->decimal('anticipo', 20, 2)->nullable();
            $table->decimal('auxilio_transporte', 20, 2)->nullable();
            $table->string('desde')->nullable();
            $table->string('a')->nullable();
            $table->decimal('otro', 20, 2);
            $table->timestamps();
            $table->foreign('paquete_nomina_id')->references('id')->on('paquete_nominas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};
