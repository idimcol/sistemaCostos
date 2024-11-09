<?php

use App\Enums\Departamento;
use App\Enums\TipoPago;
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
        Schema::create('trabajadors', function (Blueprint $table) {
            $table->id();
            $table->string('numero_identificacion');
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('edad');
            $table->string('cargo');
            $table->enum('sexo', ['masculino', 'femenino', 'otro']);
            $table->date('fecha_nacimiento');
            $table->date('fecha_ingreso');
            $table->string('celular');
            $table->string('Eps');
            $table->string('cuenta_bancaria');
            $table->string('banco');
            $table->enum('tipo_pago', array_column(TipoPago::cases(), 'value'));
            $table->enum('departamentos', array_column(Departamento::cases(), 'value'));
            $table->string('contrato')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadors');
    }
};
