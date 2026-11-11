<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Odontologo;
use App\Models\HistorialAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PacienteController extends Controller
{
    /**
     * Panel Principal del Paciente
     */
    public function dashboard()
    {
        $usuario = Auth::user();

        // Próxima cita programada (Asignado o Solicitado)
        $proximaCita = Cita::with(['odontologo.usuario', 'servicios'])
            ->where('paciente_id', $usuario->id)
            ->whereIn('estado', ['Solicitado', 'Asignado'])
            ->where('fecha', '>=', Carbon::today())
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->first();

        // Contadores del paciente
        $citasAtendidas = Cita::where('paciente_id', $usuario->id)->where('estado', 'Atendido')->count();
        $citasCanceladas = Cita::where('paciente_id', $usuario->id)->where('estado', 'Cancelado')->count();
        $inasistencias = $usuario->faltas_acumuladas;

        return view('paciente.dashboard', compact('usuario', 'proximaCita', 'citasAtendidas', 'citasCanceladas', 'inasistencias'));
    }

    /**
     * Formulario de Agendamiento de Cita en 4 pasos
     */
    public function agendar()
    {
        $usuario = Auth::user();

        // Regla 7: Restricción por ausentismo reiterativo (2 o más faltas consecutivas)
        if (!$usuario->puedeAgendarWeb()) {
            return redirect()->route('paciente.dashboard')->with('error_bloqueo', 
                'Su cuenta presenta dos o más inasistencias registradas. Por políticas de la clínica DentalCare, debe agendar su cita presencialmente o comunicándose con recepción.');
        }

        $servicios = Servicio::where('activo', true)->get();
        $odontologos = Odontologo::with(['usuario', 'horarios'])->where('estado', 'activo')->get();

        return view('paciente.agendar', compact('servicios', 'odontologos'));
    }

    /**
     * Procesa la solicitud de cita aplicando cálculo aditivo y validación de no solapamiento
     */
    public function storeCita(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario->puedeAgendarWeb()) {
            return back()->with('error', 'No tiene autorización para agendar en línea debido a inasistencias acumuladas.');
        }

        $request->validate([
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Regla 2: Cálculo aditivo de duración y costo total
        $serviciosSeleccionados = Servicio::whereIn('id', $request->servicios)->get();
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
            return back()->withInput()->with('error', 'El odontólogo seleccionado ya tiene una cita reservada en ese horario. Por favor elija otra hora.');
        }

        DB::beginTransaction();
        try {
            $cita = Cita::create([
                'paciente_id' => $usuario->id,
                'odontologo_id' => $request->odontologo_id,
                'fecha' => $request->fecha,
                'hora_inicio' => $horaInicio->format('H:i:s'),
                'hora_fin' => $horaFin->format('H:i:s'),
                'estado' => 'Solicitado', // Pasa a solicitado
                'duracion_total_min' => $duracionTotalMin,
                'costo_total' => $costoTotal,
                'observaciones' => $request->observaciones,
            ]);

            // Asociar los servicios
            foreach ($serviciosSeleccionados as $servicio) {
                $cita->servicios()->attach($servicio->id, [
                    'precio_historico' => $servicio->precio,
                ]);
            }

            // Registrar notificación de confirmación inmediata
            DB::table('notificaciones')->insert([
                'cita_id' => $cita->id,
                'tipo' => 'confirmacion',
                'fecha_envio' => now(),
                'estado_envio' => 'enviado',
                'mensaje' => "Cita solicitada exitosamente para el {$cita->fecha->format('d/m/Y')} a las {$cita->hora_inicio}.",
            ]);

            DB::commit();

            return redirect()->route('paciente.citas')->with('success', '¡Cita solicitada exitosamente! Recibirá la confirmación por correo electrónico.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al agendar la cita: ' . $e->getMessage());
        }
    }

    /**
     * Listado interactivo de Mis Citas
     */
    public function citas()
    {
        $citas = Cita::with(['odontologo.usuario', 'servicios'])
            ->where('paciente_id', Auth::id())
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->get();

        return view('paciente.citas', compact('citas'));
    }

    /**
     * Regla 3: Cancelación con mínimo 2 horas de anticipación
     */
    public function cancelarCita(Request $request, $id)
    {
        $cita = Cita::where('id', $id)->where('paciente_id', Auth::id())->firstOrFail();

        if ($cita->esTerminal()) {
            return back()->with('error', 'Esta cita ya no puede ser cancelada debido a su estado actual.');
        }

        if (!$cita->puedeCancelarOReagendar()) {
            return back()->with('error', 'Políticas de la clínica: La cancelación solo es permitida con al menos 2 horas de anticipación a la cita programada.');
        }

        $cita->update([
            'estado' => 'Cancelado',
            'observaciones' => ($cita->observaciones ? $cita->observaciones . " | " : "") . 'Cancelada voluntariamente por el paciente con anticipación oportuna.',
        ]);

        return back()->with('success', 'La cita fue cancelada exitosamente y la franja horaria ha sido liberada.');
    }

    /**
     * Historial Clínico y de Asistencia
     */
    public function historial()
    {
        $historial = HistorialAtencion::with('cita.servicios')
            ->where('paciente_id', Auth::id())
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return view('paciente.historial', compact('historial'));
    }
}
