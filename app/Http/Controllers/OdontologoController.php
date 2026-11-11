<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Odontologo;
use App\Models\HistorialAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OdontologoController extends Controller
{
    /**
     * Agenda del Día para el Especialista
     */
    public function agenda(Request $request)
    {
        $usuario = Auth::user();
        $odontologo = Odontologo::where('usuario_id', $usuario->id)->firstOrFail();

        $fechaSeleccionada = $request->get('fecha', Carbon::today()->format('Y-m-d'));

        $citas = Cita::with(['paciente', 'servicios'])
            ->where('odontologo_id', $odontologo->id)
            ->where('fecha', $fechaSeleccionada)
            ->orderBy('hora_inicio')
            ->get();

        $metricas = [
            'total' => $citas->count(),
            'pendientes' => $citas->whereIn('estado', ['Solicitado', 'Asignado'])->count(),
            'en_proceso' => $citas->where('estado', 'En Proceso')->count(),
            'atendidas' => $citas->where('estado', 'Atendido')->count(),
            'no_asistio' => $citas->where('estado', 'No Asistio')->count(),
        ];

        return view('odontologo.agenda', compact('odontologo', 'fechaSeleccionada', 'citas', 'metricas'));
    }

    /**
     * Iniciar Atención Clínica (Pasa estado a 'En Proceso')
     */
    public function iniciarAtencion($id)
    {
        $odontologo = Odontologo::where('usuario_id', Auth::id())->firstOrFail();
        $cita = Cita::where('id', $id)->where('odontologo_id', $odontologo->id)->firstOrFail();

        if ($cita->estado !== 'Asignado' && $cita->estado !== 'Solicitado') {
            return back()->with('error', 'Solo puede iniciar la atención de citas solicitadas o asignadas.');
        }

        $cita->update([
            'estado' => 'En Proceso',
        ]);

        return back()->with('success', "Atención médica iniciada para el paciente {$cita->paciente->nombre}.");
    }

    /**
     * Finalizar Atención Clínica (Pasa estado a 'Atendido', registra observaciones clínicas y descuenta insumos)
     */
    public function finalizarAtencion(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string|min:5|max:1000',
        ]);

        $odontologo = Odontologo::where('usuario_id', Auth::id())->firstOrFail();
        $cita = Cita::with('servicios.insumos')->where('id', $id)->where('odontologo_id', $odontologo->id)->firstOrFail();

        if ($cita->estado !== 'En Proceso') {
            return back()->with('error', 'La cita debe estar en estado En Proceso para poder finalizarla.');
        }

        DB::beginTransaction();
        try {
            $cita->update([
                'estado' => 'Atendido',
                'observaciones' => $request->observaciones,
            ]);

            // Si los triggers de base de datos no están activos en el motor simulado, aplicamos la regla de negocio explícita:
            // 1. Registro en historial
            HistorialAtencion::create([
                'cita_id' => $cita->id,
                'paciente_id' => $cita->paciente_id,
                'fecha' => $cita->fecha,
                'tipo_registro' => 'Atencion Realizada',
                'resumen' => 'Atención culminada exitosamente. Observaciones: ' . $request->observaciones,
            ]);

            // 2. Descuento de insumos estipulado por los servicios de la cita
            foreach ($cita->servicios as $servicio) {
                foreach ($servicio->insumos as $insumo) {
                    $cantidad = $insumo->pivot->cantidad_consumo ?? 1;
                    $insumo->decrement('stock', $cantidad);
                }
            }

            DB::commit();
            return back()->with('success', 'Atención médica finalizada. Se guardó el registro clínico y se actualizaron las existencias de insumos.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al finalizar atención: ' . $e->getMessage());
        }
    }

    /**
     * Reportar Inasistencia (No Asistió tras ventana de 15 minutos de tolerancia)
     */
    public function marcarNoAsistio(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:255',
        ]);

        $odontologo = Odontologo::where('usuario_id', Auth::id())->firstOrFail();
        $cita = Cita::with('paciente')->where('id', $id)->where('odontologo_id', $odontologo->id)->firstOrFail();

        // Validar Regla 4: Margen de 15 minutos de tolerancia
        if (!$cita->puedeMarcarNoAsistio()) {
            return back()->with('error', 'Regla de negocio: Debe cumplirse el tiempo de tolerancia mínimo de quince (15) minutos posteriores a la hora pactada para registrar inasistencia.');
        }

        DB::beginTransaction();
        try {
            $motivo = $request->motivo ?: 'Paciente ausente tras transcurrir los 15 minutos reglamentarios de tolerancia.';

            $cita->update([
                'estado' => 'No Asistio',
                'motivo_inasistencia' => $motivo,
            ]);

            // Sumar falta acumulada al paciente (Regla 7 y 8)
            $paciente = $cita->paciente;
            $paciente->increment('faltas_acumuladas');

            // Registrar en Historial
            HistorialAtencion::create([
                'cita_id' => $cita->id,
                'paciente_id' => $cita->paciente_id,
                'fecha' => $cita->fecha,
                'tipo_registro' => 'Inasistencia Registrada',
                'resumen' => "Inasistencia registrada tras 15 min de espera. Motivo: {$motivo}",
            ]);

            // Notificación formal por email al paciente invitándolo a reprogramar
            DB::table('notificaciones')->insert([
                'cita_id' => $cita->id,
                'tipo' => 'inasistencia',
                'fecha_envio' => now(),
                'estado_envio' => 'enviado',
                'mensaje' => "Estimado(a) {$paciente->nombre}, registramos su inasistencia a la cita de hoy. Puede ingresar a la plataforma y solicitar una nueva fecha.",
            ]);

            // REGLA 5: ¡NO SE DESCUENTA NINGÚN INSUMO!

            DB::commit();
            return back()->with('warning', 'Inasistencia registrada formalmente. Se liberó la agenda del consultorio y se notificó al paciente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar inasistencia: ' . $e->getMessage());
        }
    }
}
