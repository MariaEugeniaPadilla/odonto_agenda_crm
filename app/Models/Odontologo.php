<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odontologo extends Model
{
    use HasFactory;

    protected $table = 'odontologos';

    protected $fillable = [
        'usuario_id',
        'especialidad',
        'registro_medico',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'odontologo_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'odontologo_id');
    }
}
