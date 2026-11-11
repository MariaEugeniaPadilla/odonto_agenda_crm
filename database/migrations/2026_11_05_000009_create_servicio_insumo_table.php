<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicio_insumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
            $table->unsignedInteger('cantidad_consumo')->default(1);

            $table->unique(['servicio_id', 'insumo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicio_insumo');
    }
};
