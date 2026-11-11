<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumos';

    protected $fillable = [
        'nombre',
        'stock',
        'stock_minimo',
        'unidad_medida',
    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_insumo', 'insumo_id', 'servicio_id')
                    ->withPivot('cantidad_consumo');
    }

    public function tieneStockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}
