<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'duracion_total_min',
        'costo_total',
        'observaciones',
        'motivo_inasistencia',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'paciente_id');
    }

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class, 'odontologo_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio', 'cita_id', 'servicio_id')
                    ->withPivot('precio_historico');
    }

    public function historial()
    {
        return $this->hasMany(HistorialAtencion::class, 'cita_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'cita_id');
    }

    /**
     * Regla 3: Ventana de cancelación o reagendamiento mínimo 2 horas antes
     */
    public function puedeCancelarOReagendar(): bool
    {
        if (in_array($this->estado, ['Atendido', 'Cancelado', 'No Asistio'])) {
            return false;
        }

        $inicio = Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora_inicio);
        $horasRestantes = Carbon::now()->diffInHours($inicio, false);

        return $horasRestantes >= 2;
    }

    /**
     * Regla 4: Margen de tolerancia máximo 15 minutos para marcar No Asistió
     */
    public function puedeMarcarNoAsistio(): bool
    {
        if (!in_array($this->estado, ['Solicitado', 'Asignado'])) {
            return false;
        }

        $inicio = Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora_inicio);
        $minutosTranscurridos = $inicio->diffInMinutes(Carbon::now(), false);

        return $minutosTranscurridos >= 15;
    }

    /**
     * Regla 6: Inmutabilidad de citas terminales
     */
    public function esTerminal(): bool
    {
        return in_array($this->estado, ['Atendido', 'Cancelado', 'No Asistio']);
    }
}
