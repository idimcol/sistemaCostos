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
        Schema::create('historial_cifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cif_id')->constrained()->onDelete('cascade');
            $table->decimal('MOI', 20, 2);
            $table->decimal('GOI', 20, 2);
            $table->decimal('OCI', 20, 2);
            $table->integer('NMH');
            $table->year('año');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_cifs');
    }
};
