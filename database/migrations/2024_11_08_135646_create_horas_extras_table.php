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
        Schema::create('horas_extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trabajadores_id');
            $table->string('operario_cod');
            $table->decimal('valor_bono', 20, 2)->change();
            $table->decimal('horas_diurnas', 20, 2)->change();
            $table->decimal('horas_nocturnas', 20, 2)->change();
            $table->decimal('horas_festivos', 20, 2)->change();
            $table->decimal('horas_recargo_nocturno', 20, 2)->change();
            $table->timestamps();

            $table->foreign('trabajadores_id')->references('id')->on('trabajadors')->onDelete('cascade');
            $table->foreign('operario_cod')->references('codigo')->on('operativos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horas_extras');
    }
};
