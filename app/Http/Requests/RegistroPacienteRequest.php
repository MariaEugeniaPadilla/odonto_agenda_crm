<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroPacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) config('app.registro_publico', true);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'correo' => ['required', 'string', 'email', 'max:150', 'unique:usuarios,correo'],
            'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
            'telefono' => ['required', 'string', 'max:20'],
            'documento_identidad' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Por favor ingrese su nombre completo.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no es válido.',
            'correo.unique' => 'Ya existe una cuenta registrada con este correo electrónico.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener como mínimo 8 caracteres.',
            'contrasena.confirmed' => 'La confirmación de la contraseña no coincide.',
            'telefono.required' => 'El número de teléfono o celular es obligatorio.',
        ];
    }
}
