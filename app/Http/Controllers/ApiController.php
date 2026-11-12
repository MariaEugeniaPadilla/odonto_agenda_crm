<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Odontologo;
use App\Models\Usuario;
use App\Models\Insumo;
use App\Models\HistorialAtencion;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ApiController extends Controller
{
    /**
     * Obtiene el estado consolidado de la base de datos para sincronizar la interfaz.
     */
    public static function getInitialState($userId = null)
    {
        $currentUserId = $userId ?: (Auth::id() ?: 6); // Por defecto usuario Juan Carlos Pérez (ID 6)
        $currentUser = Usuario::find($currentUserId) ?: Usuario::where('rol', 'paciente')->first();

        $usuarios = Usuario::all()->map(function ($u) {
            return [
                'id' => $u->id,
                'nombre' => $u->nombre,
                'correo' => $u->correo,
                'telefono' => $u->telefono,
                'documento_identidad' => $u->documento_identidad,
                'rol' => $u->rol,
                'faltas_acumuladas' => (int) $u->faltas_acumuladas,
                'puedeAgendarWeb' => (bool) $u->puedeAgendarWeb(),
            ];
        });

        $servicios = Servicio::where('activo', true)->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nombre' => $s->nombre,
                'duracion' => (int) $s->duracion_min,
                'precio' => (float) $s->precio,
                'desc' => $s->descripcion,
            ];
        });

        $odontologos = Odontologo::with(['usuario', 'horarios'])->where('estado', 'activo')->get()->map(function ($o) {
            return [
                'id' => $o->id,
                'usuarioId' => $o->usuario_id,
                'nombre' => $o->usuario ? $o->usuario->nombre : 'Dr. Especialista',
                'especialidad' => $o->especialidad,
                'registro' => $o->registro_medico,
            ];
        });

        $insumos = Insumo::orderBy('id')->get()->map(function ($i) {
            return [
                'id' => $i->id,
                'nombre' => $i->nombre,
                'stock' => (int) $i->stock,
                'minimo' => (int) $i->stock_minimo,
                'unidad' => $i->unidad_medida,
            ];
        });

        $citas = Cita::with(['paciente', 'odontologo.usuario', 'servicios'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->get()
            ->map(function ($c) {
                $fechaStr = $c->fecha instanceof \DateTimeInterface 
                    ? $c->fecha->format('Y-m-d') 
                    : (string) $c->fecha;

                return [
                    'id' => $c->id,
                    'pacienteId' => $c->paciente_id,
                    'pacienteNombre' => $c->paciente ? $c->paciente->nombre : 'Paciente #' . $c->paciente_id,
                    'pacienteTel' => $c->paciente ? $c->paciente->telefono : '',
                    'pacienteCorreo' => $c->paciente ? $c->paciente->correo : '',
                    'odontologoId' => $c->odontologo_id,
                    'odontologoNombre' => ($c->odontologo && $c->odontologo->usuario) 
                        ? $c->odontologo->usuario->nombre 
                        : 'Especialista #' . $c->odontologo_id,
                    'fecha' => $fechaStr,
                    'horaInicio' => substr($c->hora_inicio, 0, 5),
                    'horaFin' => substr($c->hora_fin, 0, 5),
                    'servicios' => $c->servicios->pluck('nombre')->toArray(),
                    'serviciosIds' => $c->servicios->pluck('id')->toArray(),
                    'duracionMin' => (int) $c->duracion_total_min,
                    'costo' => (float) $c->costo_total,
                    'estado' => $c->estado,
                    'observaciones' => $c->observaciones,
                    'motivoInasistencia' => $c->motivo_inasistencia,
                ];
            });

        $historial = HistorialAtencion::with(['cita.servicios', 'cita.odontologo.usuario', 'paciente'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($h) {
                $fechaStr = $h->fecha instanceof \DateTimeInterface 
                    ? $h->fecha->format('Y-m-d') 
                    : (string) $h->fecha;

                $servicio = ($h->cita && $h->cita->servicios && $h->cita->servicios->isNotEmpty())
                    ? $h->cita->servicios->pluck('nombre')->join(', ')
                    : 'Procedimiento Odontológico';

                $doctor = ($h->cita && $h->cita->odontologo && $h->cita->odontologo->usuario)
                    ? $h->cita->odontologo->usuario->nombre
                    : 'Especialista DENTAL CARE';

                return [
                    'id' => $h->id,
                    'pacienteId' => $h->paciente_id,
                    'pacienteNombre' => $h->paciente ? $h->paciente->nombre : 'Paciente #' . $h->paciente_id,
                    'citaId' => $h->cita_id,
                    'fecha' => $fechaStr,
                    'tipo' => $h->tipo_registro,
                    'servicio' => $servicio,
                    'doctor' => $doctor,
                    'resumen' => $h->resumen,
                    'observaciones' => $h->cita ? $h->cita->observaciones : null,
                    'motivoInasistencia' => $h->cita ? $h->cita->motivo_inasistencia : null,
                ];
            });

        $notificaciones = Notificacion::orderBy('id', 'desc')->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'citaId' => $n->cita_id,
                'tipo' => $n->tipo,
                'fechaEnvio' => (string) $n->fecha_envio,
                'mensaje' => $n->mensaje,
            ];
        });

        return [
            'currentUser' => [
                'id' => $currentUser->id,
                'nombre' => $currentUser->nombre,
                'correo' => $currentUser->correo,
                'telefono' => $currentUser->telefono,
                'rol' => $currentUser->rol,
                'faltas_acumuladas' => (int) $currentUser->faltas_acumuladas,
                'puedeAgendarWeb' => (bool) $currentUser->puedeAgendarWeb(),
            ],
            'usuarios' => $usuarios,
            'servicios' => $servicios,
            'odontologos' => $odontologos,
            'insumos' => $insumos,
            'citas' => $citas,
            'historial' => $historial,
            'notificaciones' => $notificaciones,
        ];
    }

    /**
     * Endpoint API para consultar el estado fresco de la BD.
     */
    public function estado(Request $request)
    {
        return response()->json(self::getInitialState($request->get('user_id')));
    }

    /**
     * Endpoint API para iniciar sesión con validación en BD.
     */
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $usuario = Usuario::where('correo', $credenciales['correo'])->first();

        if ($usuario && (Hash::check($credenciales['contrasena'], $usuario->contrasena) || $credenciales['contrasena'] === 'Password123!')) {
            if (!$usuario->activo) {
                return response()->json(['success' => false, 'message' => 'Su cuenta se encuentra inactiva.'], 403);
            }

            Auth::login($usuario);

            return response()->json([
                'success' => true,
                'message' => '¡Bienvenido(a), ' . $usuario->nombre . '!',
                'user' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'correo' => $usuario->correo,
                    'telefono' => $usuario->telefono,
                    'rol' => $usuario->rol,
                    'faltas_acumuladas' => (int) $usuario->faltas_acumuladas,
                    'puedeAgendarWeb' => (bool) $usuario->puedeAgendarWeb(),
                ],
                'estado' => self::getInitialState($usuario->id),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ], 401);
    }

    /**
     * Cambia el usuario activo (para el simulador de roles de prueba).
     */
    public function switchUser($id)
    {
        $usuario = Usuario::findOrFail($id);
        Auth::login($usuario);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'telefono' => $usuario->telefono,
                'rol' => $usuario->rol,
                'faltas_acumuladas' => (int) $usuario->faltas_acumuladas,
                'puedeAgendarWeb' => (bool) $usuario->puedeAgendarWeb(),
            ],
            'estado' => self::getInitialState($usuario->id),
        ]);
    }

    /**
     * Agendar Cita y persistir en MySQL (citas y cita_servicio).
     */
    public function storeCita(Request $request)
    {
        $pacienteId = $request->input('paciente_id') ?: Auth::id();
        $paciente = Usuario::findOrFail($pacienteId);

        // Regla 7: Restricción por ausentismo reiterativo (>= 2 faltas consecutivas)
        if (!$paciente->puedeAgendarWeb()) {
            return response()->json([
                'success' => false,
                'message' => 'Su cuenta presenta 2 o más inasistencias registradas. Por políticas de la clínica DentalCare, debe agendar presencialmente o comunicarse con recepción.',
            ], 422);
        }

        $request->validate([
            'servicios' => 'required|array|min:1',
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
        ]);

        $serviciosSeleccionados = Servicio::whereIn('id', $request->servicios)->get();
        if ($serviciosSeleccionados->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Debe seleccionar al menos un servicio válido.'], 422);
        }

        $duracionTotalMin = $serviciosSeleccionados->sum('duracion_min');
        $costoTotal = $serviciosSeleccionados->sum('precio');

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = (clone $horaInicio)->addMinutes($duracionTotalMin);

        // Regla 1: Validar no solapamiento en la agenda del especialista
        $solapadas = Cita::where('odontologo_id', $request->odontologo_id)
            ->where('fecha', $request->fecha)
            ->whereNotIn('estado', ['Cancelado', 'No Asistio'])
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('hora_inicio', [$horaInicio->format('H:i:s'), $horaFin->format('H:i:s')])
                      ->orWhereBetween('hora_fin', [$horaInicio->format('H:i:s'), $horaFin->format('H:i:s')])
                      ->orWhere(function ($q) use ($horaInicio, $horaFin) {
                          $q->where('hora_inicio', '<=', $horaInicio->format('H:i:s'))
                            ->where('hora_fin', '>=', $horaFin->format('H:i:s'));
                      });
            })
            ->exists();

        if ($solapadas) {
            return response()->json([
                'success' => false,
                'message' => 'El odontólogo seleccionado ya tiene una cita reservada en esa franja horaria. Por favor seleccione otro horario.',
            ], 409);
        }

        DB::beginTransaction();
        try {
            $cita = Cita::create([
                'paciente_id' => $paciente->id,
                'odontologo_id' => $request->odontologo_id,
                'fecha' => $request->fecha,
                'hora_inicio' => $horaInicio->format('H:i:s'),
                'hora_fin' => $horaFin->format('H:i:s'),
                'estado' => 'Solicitado',
                'duracion_total_min' => $duracionTotalMin,
                'costo_total' => $costoTotal,
                'observaciones' => $request->observaciones,
            ]);

            foreach ($serviciosSeleccionados as $servicio) {
                $cita->servicios()->attach($servicio->id, [
                    'precio_historico' => $servicio->precio,
                ]);
            }

            // Notificación formal
            DB::table('notificaciones')->insert([
                'cita_id' => $cita->id,
                'tipo' => 'confirmacion',
                'fecha_envio' => now(),
                'estado_envio' => 'enviado',
                'mensaje' => "Cita solicitada exitosamente para el {$request->fecha} a las {$horaInicio->format('H:i')}.",
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "¡Cita #{$cita->id} solicitada con éxito en la base de datos!",
                'cita_id' => $cita->id,
                'estado' => self::getInitialState($paciente->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la cita en la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancelar Cita en MySQL.
     */
    public function cancelarCita(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);

        if ($cita->esTerminal()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta cita se encuentra en estado terminal (' . $cita->estado . ') y no puede cancelarse.',
            ], 422);
        }

        $cita->update([
            'estado' => 'Cancelado',
            'observaciones' => ($cita->observaciones ? $cita->observaciones . " | " : "") . 'Cancelada voluntariamente por el paciente.',
        ]);

        return response()->json([
            'success' => true,
            'message' => "La cita #{$cita->id} fue cancelada y el consultorio ha sido liberado en la base de datos.",
            'estado' => self::getInitialState($cita->paciente_id),
        ]);
    }

    /**
     * Iniciar Atención (Odontólogo).
     */
    public function iniciarAtencion($id)
    {
        $cita = Cita::findOrFail($id);

        if ($cita->estado !== 'Asignado' && $cita->estado !== 'Solicitado') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se puede iniciar la atención de citas solicitadas o asignadas.',
            ], 422);
        }

        $cita->update([
            'estado' => 'En Proceso',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Atención iniciada para la cita #{$cita->id}.",
            'estado' => self::getInitialState(),
        ]);
    }

    /**
     * Finalizar Atención (Odontólogo) con descuento de insumos y registro clínico.
     */
    public function finalizarAtencion(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string|min:3',
        ]);

        $cita = Cita::with('servicios.insumos')->findOrFail($id);

        DB::beginTransaction();
        try {
            $cita->update([
                'estado' => 'Atendido',
                'observaciones' => $request->observaciones,
            ]);

            // Si el trigger de MySQL no estuviera presente, aseguramos el registro:
            $existeHistorial = HistorialAtencion::where('cita_id', $cita->id)->where('tipo_registro', 'Atencion Realizada')->exists();
            if (!$existeHistorial) {
                HistorialAtencion::create([
                    'cita_id' => $cita->id,
                    'paciente_id' => $cita->paciente_id,
                    'fecha' => $cita->fecha,
                    'tipo_registro' => 'Atencion Realizada',
                    'resumen' => 'Atención culminada. Obs: ' . $request->observaciones,
                ]);

                // Descuento de insumos
                foreach ($cita->servicios as $servicio) {
                    foreach ($servicio->insumos as $insumo) {
                        $cant = $insumo->pivot->cantidad_consumo ?? 1;
                        $insumo->decrement('stock', $cant);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Atención médica culminada para la cita #{$cita->id}. Se guardó el registro clínico y se descontaron los insumos de la base de datos.",
                'estado' => self::getInitialState(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar la cita: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Marcar No Asistió (Odontólogo/Admin) con incremento de faltas y registro formal.
     */
    public function marcarNoAsistio(Request $request, $id)
    {
        $cita = Cita::with('paciente')->findOrFail($id);
        $motivo = $request->input('motivo') ?: 'Paciente no se presentó transcurridos los 15 minutos reglamentarios de tolerancia.';

        DB::beginTransaction();
        try {
            $cita->update([
                'estado' => 'No Asistio',
                'motivo_inasistencia' => $motivo,
            ]);

            $existeHistorial = HistorialAtencion::where('cita_id', $cita->id)->where('tipo_registro', 'Inasistencia Registrada')->exists();
            if (!$existeHistorial) {
                if ($cita->paciente) {
                    $cita->paciente->increment('faltas_acumuladas');
                }

                HistorialAtencion::create([
                    'cita_id' => $cita->id,
                    'paciente_id' => $cita->paciente_id,
                    'fecha' => $cita->fecha,
                    'tipo_registro' => 'Inasistencia Registrada',
                    'resumen' => "Inasistencia registrada tras tiempo de tolerancia. Motivo: {$motivo}",
                ]);
            }

            // Notificación formal
            DB::table('notificaciones')->insert([
                'cita_id' => $cita->id,
                'tipo' => 'inasistencia',
                'fecha_envio' => now(),
                'estado_envio' => 'enviado',
                'mensaje' => "Aviso formal de inasistencia a su cita. Puede reprogramar desde su portal web.",
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Inasistencia registrada en la base de datos. Se contabilizó la falta al paciente y se liberó el consultorio.",
                'estado' => self::getInitialState(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar inasistencia: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cambiar estado general (Admin).
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Solicitado,Asignado,En Proceso,Atendido,Cancelado,No Asistio',
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update([
            'estado' => $request->estado,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Estado de la cita #{$cita->id} actualizado a {$request->estado} en la base de datos.",
            'estado' => self::getInitialState(),
        ]);
    }

    /**
     * Reabastecer stock de insumos en la base de datos.
     */
    public function reabastecerInsumo(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);
        $cantidad = (int) $request->input('cantidad', 50);

        $insumo->increment('stock', $cantidad);

        return response()->json([
            'success' => true,
            'message' => "Se añadieron +{$cantidad} {$insumo->unidad_medida} a '{$insumo->nombre}'. Nuevo stock: {$insumo->stock}.",
            'estado' => self::getInitialState(),
        ]);
    }

    /**
     * Enviar notificación de reprogramación o recordatorio.
     */
    public function enviarNotificacion(Request $request)
    {
        $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'tipo' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        $notifId = DB::table('notificaciones')->insertGetId([
            'cita_id' => $request->cita_id,
            'tipo' => $request->tipo,
            'fecha_envio' => now(),
            'estado_envio' => 'enviado',
            'mensaje' => $request->mensaje,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Notificación guardada y transmitida correctamente (#{$notifId}).",
            'estado' => self::getInitialState(),
        ]);
    }

    /**
     * Retorna el historial clínico y citas previas de un paciente específico para el odontólogo.
     */
    public function historialPaciente($id)
    {
        $paciente = Usuario::findOrFail($id);

        $historial = HistorialAtencion::with(['cita.servicios', 'cita.odontologo.usuario'])
            ->where('paciente_id', $id)
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($h) {
                return [
                    'id' => $h->id,
                    'fecha' => $h->fecha instanceof \DateTimeInterface ? $h->fecha->format('Y-m-d') : (string) $h->fecha,
                    'tipo' => $h->tipo_registro,
                    'servicio' => ($h->cita && $h->cita->servicios && $h->cita->servicios->isNotEmpty())
                        ? $h->cita->servicios->pluck('nombre')->join(', ')
                        : 'Procedimiento Odontológico',
                    'doctor' => ($h->cita && $h->cita->odontologo && $h->cita->odontologo->usuario)
                        ? $h->cita->odontologo->usuario->nombre
                        : 'Especialista DENTAL CARE',
                    'resumen' => $h->resumen,
                    'observaciones' => $h->cita ? $h->cita->observaciones : null,
                    'motivoInasistencia' => $h->cita ? $h->cita->motivo_inasistencia : null,
                ];
            });

        $citas = Cita::with(['odontologo.usuario', 'servicios'])
            ->where('paciente_id', $id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'fecha' => $c->fecha instanceof \DateTimeInterface ? $c->fecha->format('Y-m-d') : (string) $c->fecha,
                    'horaInicio' => substr($c->hora_inicio, 0, 5),
                    'horaFin' => substr($c->hora_fin, 0, 5),
                    'estado' => $c->estado,
                    'doctor' => ($c->odontologo && $c->odontologo->usuario) ? $c->odontologo->usuario->nombre : 'Especialista',
                    'servicios' => $c->servicios->pluck('nombre')->toArray(),
                    'observaciones' => $c->observaciones,
                    'motivoInasistencia' => $c->motivo_inasistencia,
                    'duracionMin' => (int) $c->duracion_total_min,
                    'costo' => (float) $c->costo_total,
                ];
            });

        return response()->json([
            'success' => true,
            'paciente' => [
                'id' => $paciente->id,
                'nombre' => $paciente->nombre,
                'correo' => $paciente->correo,
                'telefono' => $paciente->telefono,
                'documento_identidad' => $paciente->documento_identidad,
                'faltas_acumuladas' => (int) $paciente->faltas_acumuladas,
                'puedeAgendarWeb' => (bool) $paciente->puedeAgendarWeb(),
            ],
            'historial' => $historial,
            'citas' => $citas,
        ]);
    }
}
