<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('create', \App\Models\Usuario::class);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'correo' => ['required', 'string', 'email', 'max:150', 'unique:usuarios,correo'],
            'telefono' => ['required', 'string', 'max:20'],
            'documento_identidad' => ['nullable', 'string', 'max:20'],
            'rol' => ['required', 'string', Rule::in(Role::rolesDisponibles())],
            'activo' => ['nullable', 'boolean'],
            'metodo_contrasena' => ['required', 'string', Rule::in(['enlace', 'manual'])],
            'contrasena' => [
                'required_if:metodo_contrasena,manual',
                'nullable',
                'string',
                'min:8',
            ],
            // Datos adicionales si el rol es Odontólogo
            'especialidad' => ['nullable', 'string', 'max:120'],
            'registro_medico' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre completo del usuario es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico ingresado no tiene un formato válido.',
            'correo.unique' => 'Ya existe un usuario registrado con este correo electrónico.',
            'telefono.required' => 'El número de teléfono o celular es obligatorio.',
            'rol.required' => 'Debe seleccionar un rol válido.',
            'rol.in' => 'El rol seleccionado no es válido en el sistema.',
            'metodo_contrasena.required' => 'Debe especificar el método de asignación de contraseña.',
            'contrasena.required_if' => 'Debe ingresar una contraseña manual cuando se elige esa opción.',
            'contrasena.min' => 'La contraseña manual debe contener un mínimo de 8 caracteres.',
        ];
    }
}
