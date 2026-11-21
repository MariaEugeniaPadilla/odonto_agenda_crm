<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'nombre' => Role::ADMINISTRADOR, // 'Administrador'
                'slug' => 'administrador',
                'descripcion' => 'Gestión operativa integral, inventarios, control de ausentismo y administración de usuarios',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => Role::ODONTOLOGO, // 'Odontólogo'
                'slug' => 'odontologo',
                'descripcion' => 'Atención clínica, registro de evolución, insumos y consulta de historial clínico del paciente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => Role::RECEPCIONISTA, // 'Recepcionista'
                'slug' => 'recepcionista',
                'descripcion' => 'Recepción, confirmación y reprogramación de citas y atención al paciente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => Role::PACIENTE, // 'Paciente'
                'slug' => 'paciente',
                'descripcion' => 'Acceso al portal, agendamiento web de citas y consulta de historial clínico',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['id' => $rol['id']],
                $rol
            );
        }
    }
}
