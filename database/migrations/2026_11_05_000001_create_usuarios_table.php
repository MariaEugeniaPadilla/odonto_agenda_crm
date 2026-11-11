<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('correo', 150)->unique();
            $table->string('contrasena', 255);
            $table->string('telefono', 20);
            $table->string('documento_identidad', 20)->nullable();
            $table->enum('rol', ['paciente', 'odontologo', 'recepcionista', 'admin'])->default('paciente');
            $table->unsignedInteger('faltas_acumuladas')->default(0);
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index('rol');
            $table->index('correo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
