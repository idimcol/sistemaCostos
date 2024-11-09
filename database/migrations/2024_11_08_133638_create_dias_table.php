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
        Schema::create('dias', function (Blueprint $table) {
            $table->id();
            $table->integer('dias_trabajados')->default(0);
            $table->integer('dias_remunerados')->default(0);
            $table->integer('dias_incapacidad')->default(0);
            $table->integer('dias_vacaciones')->default(0);
            $table->integer('dias_no_remunerados')->default(0);
            $table->unsignedBigInteger('trabajador_id');
            $table->unsignedBigInteger('nomina_id');
            $table->timestamps();
            $table->foreign('trabajador_id')->references('id')->on('trabajadors')->onDelete('cascade');
            $table->foreign('nomina_id')->references('id')->on('nominas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias');
    }
};
