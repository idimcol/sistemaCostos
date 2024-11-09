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
        Schema::create('articulo_sdp', function (Blueprint $table) {
            $table->id();
            $table->integer('sdp_id');
            $table->unsignedBigInteger('articulo_id');
            $table->integer('cantidad');
            $table->decimal('precio', 20, 2);

            $table->foreign('sdp_id')->references('numero_sdp')->on('sdps')->onDelete('cascade');
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_sdp');
    }
};
