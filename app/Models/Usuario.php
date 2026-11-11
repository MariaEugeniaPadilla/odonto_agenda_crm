<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'telefono',
        'documento_identidad',
        'rol',
        'faltas_acumuladas',
        'activo',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    /**
     * Comprueba si el paciente puede agendar citas en línea.
     * Regla 7: Pacientes con 2 o más faltas consecutivas tienen restringido el agendamiento web.
     */
    public function puedeAgendarWeb(): bool
    {
        return $this->faltas_acumuladas < 2;
    }

    public function odontologo()
    {
        return $this->hasOne(Odontologo::class, 'usuario_id');
    }

    public function citasPaciente()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialAtencion::class, 'paciente_id');
    }
}
