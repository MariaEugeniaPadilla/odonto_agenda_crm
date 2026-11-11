<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'notificaciones';

    protected $fillable = [
        'cita_id',
        'tipo',
        'fecha_envio',
        'estado_envio',
        'mensaje',
        'created_at',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}
