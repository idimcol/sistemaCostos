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
        Schema::table('items_s_t_e', function (Blueprint $table) {
            $table->string('descripcion')->nullable();
            $table->string('servicio_requerido')->nullable();
            $table->string('dureza_HRC')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_s_t_e', function (Blueprint $table) {
            //
        });
    }
};
