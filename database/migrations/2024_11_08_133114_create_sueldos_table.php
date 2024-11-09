<?php

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
        Schema::create('sueldos', function (Blueprint $table) {
            $table->id();
            $table->float('sueldo');
            $table->unsignedBigInteger('trabajador_id');
            $table->enum('tipo_pago', array_column(TipoPago::cases(), 'value'));
            $table->float('auxilio_transporte');
            $table->decimal('bonificacion_auxilio', 20, 2)->default(0);
            $table->timestamps();

            $table->foreign('trabajador_id')->references('id')->on('trabajadors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sueldos');
    }
};
