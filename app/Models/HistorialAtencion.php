<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialAtencion extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'historial_atencion';

    protected $fillable = [
        'cita_id',
        'paciente_id',
        'fecha',
        'tipo_registro',
        'resumen',
        'created_at',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'paciente_id');
    }
}
