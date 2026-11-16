<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'telefono',
        'documento_identidad',
        'rol',
        'role_id',
        'faltas_acumuladas',
        'activo',
        'created_by',
        'updated_by',
        'debe_cambiar_contrasena',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'debe_cambiar_contrasena' => 'boolean',
            'faltas_acumuladas' => 'integer',
        ];
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->correo;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function actualizador()
    {
        return $this->belongsTo(Usuario::class, 'updated_by');
    }

    public function hasRole(string $roleName): bool
    {
        $rolActual = $this->role ? $this->role->nombre : $this->rol;
        $map = [
            'admin' => Role::ADMINISTRADOR,
            'administrador' => Role::ADMINISTRADOR,
            'odontologo' => Role::ODONTOLOGO,
            'odontólogo' => Role::ODONTOLOGO,
            'recepcionista' => Role::RECEPCIONISTA,
            'paciente' => Role::PACIENTE,
        ];
        $rolActualNorm = $map[mb_strtolower($rolActual ?? '', 'UTF-8')] ?? $rolActual;
        $targetNorm = $map[mb_strtolower($roleName, 'UTF-8')] ?? $roleName;

        return strcasecmp($rolActualNorm, $targetNorm) === 0;
    }

    public function esAdmin(): bool
    {
        return $this->hasRole(Role::ADMINISTRADOR);
    }

    public function esOdontologo(): bool
    {
        return $this->hasRole(Role::ODONTOLOGO);
    }

    public function esRecepcionista(): bool
    {
        return $this->hasRole(Role::RECEPCIONISTA);
    }

    public function esPaciente(): bool
    {
        return $this->hasRole(Role::PACIENTE);
    }

    /**
     * Retorna el nombre formal y exacto del rol.
     */
    public function getNombreRolAttribute(): string
    {
        if ($this->role) {
            return $this->role->nombre;
        }

        $map = [
            'admin' => Role::ADMINISTRADOR,
            'administrador' => Role::ADMINISTRADOR,
            'odontologo' => Role::ODONTOLOGO,
            'odontólogo' => Role::ODONTOLOGO,
            'recepcionista' => Role::RECEPCIONISTA,
            'paciente' => Role::PACIENTE,
        ];

        return $map[mb_strtolower($this->rol ?? '', 'UTF-8')] ?? Role::PACIENTE;
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
