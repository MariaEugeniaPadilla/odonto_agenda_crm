<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'odontologo_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class, 'odontologo_id');
    }

    public function getNombreDiaAttribute(): string
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
        return $dias[$this->dia_semana] ?? 'Desconocido';
    }
}
