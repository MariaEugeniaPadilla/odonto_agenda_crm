<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->after('activo')->constrained('usuarios')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('usuarios')->nullOnDelete();
            $table->boolean('debe_cambiar_contrasena')->default(false)->after('updated_by');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('debe_cambiar_contrasena');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
