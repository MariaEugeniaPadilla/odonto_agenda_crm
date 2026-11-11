<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Odontologo;
use App\Models\Usuario;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard Gerencial y Operativo
     */
    public function dashboard()
    {
        $totalCitas = Cita::count();
        $citasAtendidas = Cita::where('estado', 'Atendido')->count();
        $citasNoAsistio = Cita::where('estado', 'No Asistio')->count();
        $citasCanceladas = Cita::where('estado', 'Cancelado')->count();
        $citasPendientes = Cita::whereIn('estado', ['Solicitado', 'Asignado'])->count();

        // Tasa Porcentual de Inasistencia (Ausentismo)
        // Calculado sobre citas terminadas con compromiso de asistencia (Atendidas + No Asistió)
        $totalFinalizadas = $citasAtendidas + $citasNoAsistio;
        $tasaAusentismo = $totalFinalizadas > 0 
            ? round(($citasNoAsistio / $totalFinalizadas) * 100, 1) 
            : 0.0;

        // Insumos con stock crítico o bajo
        $insumosBajos = Insumo::whereColumn('stock', '<=', 'stock_minimo')->get();

        // Pacientes con alerta por inasistencias
        $pacientesEnRiesgo = Usuario::where('rol', 'paciente')
            ->where('faltas_acumuladas', '>', 0)
            ->orderByDesc('faltas_acumuladas')
            ->take(5)
            ->get();

        // Próximas citas del día
        $citasHoy = Cita::with(['paciente', 'odontologo.usuario', 'servicios'])
            ->where('fecha', Carbon::today())
            ->orderBy('hora_inicio')
            ->get();

        return view('admin.dashboard', compact(
            'totalCitas',
            'citasAtendidas',
            'citasNoAsistio',
            'citasCanceladas',
            'citasPendientes',
            'tasaAusentismo',
            'insumosBajos',
            'pacientesEnRiesgo',
            'citasHoy'
        ));
    }

    /**
     * Agenda General de la Clínica (Recepción / Admin)
     */
    public function agendaGeneral(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->format('Y-m-d'));
        $odontologoId = $request->get('odontologo_id');

        $query = Cita::with(['paciente', 'odontologo.usuario', 'servicios'])
            ->where('fecha', $fecha);

        if ($odontologoId) {
            $query->where('odontologo_id', $odontologoId);
        }

        $citas = $query->orderBy('hora_inicio')->get();
        $odontologos = Odontologo::with('usuario')->where('estado', 'activo')->get();

        return view('admin.agenda', compact('citas', 'odontologos', 'fecha', 'odontologoId'));
    }

    /**
     * Cambio manual de estado por Recepcionista / Administrador
     */
    public function cambiarEstadoCita(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Solicitado,Asignado,En Proceso,Atendido,Cancelado,No Asistio',
            'observaciones' => 'nullable|string',
        ]);

        $cita = Cita::findOrFail($id);

        if ($cita->esTerminal() && !in_array($request->estado, ['Atendido', 'Cancelado', 'No Asistio'])) {
            return back()->with('error', 'Una cita en estado finalizado o no asistido no puede reactivarse.');
        }

        $cita->update([
            'estado' => $request->estado,
            'observaciones' => $request->observaciones ?: $cita->observaciones,
        ]);

        return back()->with('success', "Estado de la cita #{$cita->id} actualizado a {$request->estado}.");
    }

    /**
     * Gestión de Servicios del Catálogo
     */
    public function servicios()
    {
        $servicios = Servicio::with('insumos')->get();
        $insumos = Insumo::all();
        return view('admin.servicios', compact('servicios', 'insumos'));
    }

    /**
     * Control de Inventario
     */
    public function inventario()
    {
        $insumos = Insumo::orderBy('nombre')->get();
        return view('admin.inventario', compact('insumos'));
    }

    /**
     * Reporte Estadístico de Ausentismo e Indicadores de Gestión
     */
    public function reportes()
    {
        // Ausentismo por Especialista
        $ausentismoPorOdontologo = Odontologo::with('usuario')->get()->map(function ($medico) {
            $atendidas = Cita::where('odontologo_id', $medico->id)->where('estado', 'Atendido')->count();
            $noAsistio = Cita::where('odontologo_id', $medico->id)->where('estado', 'No Asistio')->count();
            $total = $atendidas + $noAsistio;
            $tasa = $total > 0 ? round(($noAsistio / $total) * 100, 1) : 0.0;

            return [
                'nombre' => $medico->usuario->nombre,
                'especialidad' => $medico->especialidad,
                'atendidas' => $atendidas,
                'no_asistio' => $noAsistio,
                'tasa_ausentismo' => $tasa,
            ];
        });

        // Tasa por Servicio
        $serviciosEstadisticas = Servicio::all()->map(function ($serv) {
            $citasServicio = DB::table('cita_servicio')
                ->join('citas', 'cita_servicio.cita_id', '=', 'citas.id')
                ->where('cita_servicio.servicio_id', $serv->id)
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN citas.estado = 'Atendido' THEN 1 ELSE 0 END) as atendidas,
                    SUM(CASE WHEN citas.estado = 'No Asistio' THEN 1 ELSE 0 END) as inasistencias
                ")
                ->first();

            $total = ($citasServicio->atendidas ?? 0) + ($citasServicio->inasistencias ?? 0);
            $tasa = $total > 0 ? round((($citasServicio->inasistencias ?? 0) / $total) * 100, 1) : 0.0;

            return [
                'nombre' => $serv->nombre,
                'duracion' => $serv->duracion_min,
                'atendidas' => $citasServicio->atendidas ?? 0,
                'inasistencias' => $citasServicio->inasistencias ?? 0,
                'tasa' => $tasa,
            ];
        });

        return view('admin.reportes', compact('ausentismoPorOdontologo', 'serviciosEstadisticas'));
    }
}
