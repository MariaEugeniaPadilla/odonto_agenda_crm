<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OdontoAgendaSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Password123!');

        // 1. Usuarios
        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'nombre' => 'Administradora General',
                'correo' => 'admin@dentalcare.com',
                'contrasena' => $password,
                'telefono' => '3104567890',
                'documento_identidad' => '1144001001',
                'rol' => 'admin',
                'faltas_acumuladas' => 0,
            ],
            [
                'id' => 2,
                'nombre' => 'María Recepcionista',
                'correo' => 'recepcion@dentalcare.com',
                'contrasena' => $password,
                'telefono' => '3157891234',
                'documento_identidad' => '1144002002',
                'rol' => 'recepcionista',
                'faltas_acumuladas' => 0,
            ],
            [
                'id' => 3,
                'nombre' => 'Dra. Camila Morales',
                'correo' => 'dra.morales@dentalcare.com',
                'contrasena' => $password,
                'telefono' => '3209876543',
                'documento_identidad' => '1144003003',
                'rol' => 'odontologo',
                'faltas_acumuladas' => 0,
            ],
            [
                'id' => 4,
                'nombre' => 'Dr. Carlos Mendoza',
                'correo' => 'dr.mendoza@dentalcare.com',
                'contrasena' => $password,
                'telefono' => '3187654321',
                'documento_identidad' => '1144004004',
                'rol' => 'odontologo',
                'faltas_acumuladas' => 0,
            ],
            [
                'id' => 5,
                'nombre' => 'Dra. Valeria Gómez',
                'correo' => 'dra.gomez@dentalcare.com',
                'contrasena' => $password,
                'telefono' => '3112233445',
                'documento_identidad' => '1144005005',
                'rol' => 'odontologo',
                'faltas_acumuladas' => 0,
            ],
            [
                'id' => 6,
                'nombre' => 'Juan Carlos Pérez',
                'correo' => 'juan.perez@gmail.com',
                'contrasena' => $password,
                'telefono' => '3001234567',
                'documento_identidad' => '1144006006',
                'rol' => 'paciente',
                'faltas_acumuladas' => 0,
            ],
            [
                'id' => 7,
                'nombre' => 'Laura Sofía Restrepo',
                'correo' => 'laura.restrepo@hotmail.com',
                'contrasena' => $password,
                'telefono' => '3012345678',
                'documento_identidad' => '1144007007',
                'rol' => 'paciente',
                'faltas_acumuladas' => 1,
            ],
            [
                'id' => 8,
                'nombre' => 'Andrés Felipe Murillo',
                'correo' => 'andres.murillo@gmail.com',
                'contrasena' => $password,
                'telefono' => '3023456789',
                'documento_identidad' => '1144008008',
                'rol' => 'paciente',
                'faltas_acumuladas' => 2, // Restringido para agendamiento web
            ],
        ]);

        // 2. Odontólogos
        DB::table('odontologos')->insert([
            ['id' => 1, 'usuario_id' => 3, 'especialidad' => 'Ortodoncia y Ortopedia Maxilar', 'registro_medico' => 'RM-ORT-4491', 'estado' => 'activo'],
            ['id' => 2, 'usuario_id' => 4, 'especialidad' => 'Endodoncia y Rehabilitación Oral', 'registro_medico' => 'RM-END-8210', 'estado' => 'activo'],
            ['id' => 3, 'usuario_id' => 5, 'especialidad' => 'Odontología General y Estética Dental', 'registro_medico' => 'RM-ODG-3315', 'estado' => 'activo'],
        ]);

        // 3. Servicios oficiales (Tabla 7.4)
        DB::table('servicios')->insert([
            ['id' => 1, 'nombre' => 'Valoración general', 'descripcion' => 'Diagnóstico inicial completo, revisión de salud bucal y formulación de plan de tratamiento.', 'duracion_min' => 30, 'precio' => 30000.00, 'activo' => 1],
            ['id' => 2, 'nombre' => 'Profilaxis (limpieza)', 'descripcion' => 'Limpieza profunda con ultrasonido, remoción de placa bacteriana, cálculo y pulido coronal.', 'duracion_min' => 45, 'precio' => 100000.00, 'activo' => 1],
            ['id' => 3, 'nombre' => 'Blanqueamiento dental', 'descripcion' => 'Tratamiento estético de aclaramiento dental profesional con peróxido de alta pureza.', 'duracion_min' => 60, 'precio' => 500000.00, 'activo' => 1],
            ['id' => 4, 'nombre' => 'Resina / calza dental', 'descripcion' => 'Restauración estética de cavidades por caries usando resinas compuestas de última generación.', 'duracion_min' => 45, 'precio' => 80000.00, 'activo' => 1],
            ['id' => 5, 'nombre' => 'Endodoncia', 'descripcion' => 'Tratamiento de conductos radiculares para salvar piezas dentales con compromiso pulpar.', 'duracion_min' => 90, 'precio' => 400000.00, 'activo' => 1],
            ['id' => 6, 'nombre' => 'Extracción simple', 'descripcion' => 'Exodoncia de pieza dental no restaurable bajo anestesia local de infiltración.', 'duracion_min' => 30, 'precio' => 150000.00, 'activo' => 1],
            ['id' => 7, 'nombre' => 'Control de ortodoncia', 'descripcion' => 'Revisión y ajuste periódico de brackets, cambio de arcos, ligaduras y evaluación de avance.', 'duracion_min' => 30, 'precio' => 120000.00, 'activo' => 1],
        ]);

        // 4. Insumos
        DB::table('insumos')->insert([
            ['id' => 1, 'nombre' => 'Guantes de nitrilo descartables', 'stock' => 450, 'stock_minimo' => 100, 'unidad_medida' => 'pares'],
            ['id' => 2, 'nombre' => 'Eyectores de saliva desechables', 'stock' => 320, 'stock_minimo' => 80, 'unidad_medida' => 'unidades'],
            ['id' => 3, 'nombre' => 'Baberos odontológicos impermeables', 'stock' => 280, 'stock_minimo' => 50, 'unidad_medida' => 'unidades'],
            ['id' => 4, 'nombre' => 'Carpules de Anestesia Lidocaína 2%', 'stock' => 110, 'stock_minimo' => 30, 'unidad_medida' => 'ampollas'],
            ['id' => 5, 'nombre' => 'Agujas cortas para anestesia 30G', 'stock' => 140, 'stock_minimo' => 40, 'unidad_medida' => 'unidades'],
            ['id' => 6, 'nombre' => 'Jeringa de resina fotocurable nanohíbrida', 'stock' => 22, 'stock_minimo' => 10, 'unidad_medida' => 'jeringas'],
            ['id' => 7, 'nombre' => 'Kit gel blanqueador peróxido de hidrógeno', 'stock' => 14, 'stock_minimo' => 5, 'unidad_medida' => 'kits'],
            ['id' => 8, 'nombre' => 'Pasta profiláctica abrasiva con flúor', 'stock' => 35, 'stock_minimo' => 10, 'unidad_medida' => 'tubos'],
            ['id' => 9, 'nombre' => 'Conos de gutapercha estériles', 'stock' => 80, 'stock_minimo' => 20, 'unidad_medida' => 'cajas'],
            ['id' => 10, 'nombre' => 'Ligaduras elastoméricas para ortodoncia', 'stock' => 500, 'stock_minimo' => 150, 'unidad_medida' => 'unidades'],
        ]);

        // 5. Relación Servicio - Insumo
        DB::table('servicio_insumo')->insert([
            ['servicio_id' => 1, 'insumo_id' => 1, 'cantidad_consumo' => 1],
            ['servicio_id' => 1, 'insumo_id' => 2, 'cantidad_consumo' => 1],
            ['servicio_id' => 1, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 2, 'insumo_id' => 1, 'cantidad_consumo' => 1],
            ['servicio_id' => 2, 'insumo_id' => 2, 'cantidad_consumo' => 1],
            ['servicio_id' => 2, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 2, 'insumo_id' => 8, 'cantidad_consumo' => 1],
            ['servicio_id' => 3, 'insumo_id' => 1, 'cantidad_consumo' => 2],
            ['servicio_id' => 3, 'insumo_id' => 2, 'cantidad_consumo' => 2],
            ['servicio_id' => 3, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 3, 'insumo_id' => 7, 'cantidad_consumo' => 1],
            ['servicio_id' => 4, 'insumo_id' => 1, 'cantidad_consumo' => 1],
            ['servicio_id' => 4, 'insumo_id' => 2, 'cantidad_consumo' => 1],
            ['servicio_id' => 4, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 4, 'insumo_id' => 6, 'cantidad_consumo' => 1],
            ['servicio_id' => 4, 'insumo_id' => 4, 'cantidad_consumo' => 1],
            ['servicio_id' => 4, 'insumo_id' => 5, 'cantidad_consumo' => 1],
            ['servicio_id' => 5, 'insumo_id' => 1, 'cantidad_consumo' => 2],
            ['servicio_id' => 5, 'insumo_id' => 2, 'cantidad_consumo' => 2],
            ['servicio_id' => 5, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 5, 'insumo_id' => 4, 'cantidad_consumo' => 2],
            ['servicio_id' => 5, 'insumo_id' => 5, 'cantidad_consumo' => 2],
            ['servicio_id' => 5, 'insumo_id' => 9, 'cantidad_consumo' => 1],
            ['servicio_id' => 6, 'insumo_id' => 1, 'cantidad_consumo' => 2],
            ['servicio_id' => 6, 'insumo_id' => 2, 'cantidad_consumo' => 1],
            ['servicio_id' => 6, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 6, 'insumo_id' => 4, 'cantidad_consumo' => 2],
            ['servicio_id' => 6, 'insumo_id' => 5, 'cantidad_consumo' => 2],
            ['servicio_id' => 7, 'insumo_id' => 1, 'cantidad_consumo' => 1],
            ['servicio_id' => 7, 'insumo_id' => 2, 'cantidad_consumo' => 1],
            ['servicio_id' => 7, 'insumo_id' => 3, 'cantidad_consumo' => 1],
            ['servicio_id' => 7, 'insumo_id' => 10, 'cantidad_consumo' => 1],
        ]);
    }
}
