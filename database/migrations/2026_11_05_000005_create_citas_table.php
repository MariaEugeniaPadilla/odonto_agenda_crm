<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('usuarios')->onDelete('restrict');
            $table->foreignId('odontologo_id')->constrained('odontologos')->onDelete('restrict');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', [
                'Solicitado', 
                'Asignado', 
                'En Proceso', 
                'Atendido', 
                'Cancelado', 
                'No Asistio'
            ])->default('Solicitado');
            $table->unsignedInteger('duracion_total_min')->default(30);
            $table->decimal('costo_total', 12, 2)->default(0.00);
            $table->text('observaciones')->nullable();
            $table->string('motivo_inasistencia', 255)->nullable();
            $table->timestamps();

            $table->index(['odontologo_id', 'fecha', 'hora_inicio', 'hora_fin']);
            $table->index(['paciente_id', 'fecha']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
