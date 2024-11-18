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
        Schema::table('remisiones_despachos', function (Blueprint $table) {
            $table->string('codigo');
            $table->string('cliente_id');
            $table->date('fecha_despacho');
            $table->integer('sdp_id');
            $table->string('observaciones')->nullable();
            $table->string('despacho')->nullable();
            $table->enum('departamento', array_column(Departamento::cases(), 'value'));
            $table->string('recibido')->nullable();

            $table->foreign('cliente_id')->references('nit')->on('clientes')->onDelete('cascade');
            $table->foreign('sdp_id')->references('numero_sdp')->on('sdps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remisiones_despacho', function (Blueprint $table) {
            //
        });
    }
};
