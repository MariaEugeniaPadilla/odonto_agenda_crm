<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cita_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('restrict');
            $table->decimal('precio_historico', 12, 2)->default(0.00);

            $table->unique(['cita_id', 'servicio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cita_servicio');
    }
};
