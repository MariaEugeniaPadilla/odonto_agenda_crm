<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_min',
        'precio',
        'activo',
    ];

    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_servicio', 'servicio_id', 'cita_id')
                    ->withPivot('precio_historico');
    }

    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'servicio_insumo', 'servicio_id', 'insumo_id')
                    ->withPivot('cantidad_consumo');
    }
}
