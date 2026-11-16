<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
    ];

    public const PACIENTE = 'Paciente';
    public const ODONTOLOGO = 'Odontólogo';
    public const ADMINISTRADOR = 'Administrador';
    public const RECEPCIONISTA = 'Recepcionista';

    public static function rolesDisponibles(): array
    {
        return [
            self::ADMINISTRADOR,
            self::ODONTOLOGO,
            self::RECEPCIONISTA,
            self::PACIENTE,
        ];
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'role_id');
    }
}
