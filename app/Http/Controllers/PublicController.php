<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Odontologo;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Muestra la Landing Page pública de la clínica DentalCare
     */
    public function index()
    {
        $servicios = Servicio::where('activo', true)->get();
        $odontologos = Odontologo::with('usuario')->where('estado', 'activo')->get();

        return view('landing', compact('servicios', 'odontologos'));
    }

    /**
     * Catálogo público detallado de servicios con precios y duración estimada
     */
    public function servicios()
    {
        $servicios = Servicio::where('activo', true)->get();
        return view('servicios', compact('servicios'));
    }

    /**
     * Muestra la lista del equipo médico especialista
     */
    public function odontologos()
    {
        $odontologos = Odontologo::with('usuario', 'horarios')->where('estado', 'activo')->get();
        return view('odontologos', compact('odontologos'));
    }
}
