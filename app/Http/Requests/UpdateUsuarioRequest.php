<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $targetUser = $this->route('usuario');
        return $this->user() && $this->user()->can('update', $targetUser);
    }

    public function rules(): array
    {
        $userId = $this->route('usuario') instanceof \App\Models\Usuario
            ? $this->route('usuario')->id
            : $this->route('usuario');

        return [
            'nombre' => ['required', 'string', 'max:150'],
            'correo' => ['required', 'string', 'email', 'max:150', Rule::unique('usuarios', 'correo')->ignore($userId)],
            'telefono' => ['required', 'string', 'max:20'],
            'documento_identidad' => ['nullable', 'string', 'max:20'],
            'rol' => ['required', 'string', Rule::in(Role::rolesDisponibles())],
            'activo' => ['nullable', 'boolean'],
            'contrasena' => ['nullable', 'string', 'min:8'],
            'especialidad' => ['nullable', 'string', 'max:120'],
            'registro_medico' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre completo del usuario es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
            'correo.unique' => 'Este correo ya pertenece a otro usuario registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'rol.required' => 'Debe indicar un rol para el usuario.',
            'rol.in' => 'El rol indicado no es válido.',
            'contrasena.min' => 'Si cambia la contraseña, debe tener al menos 8 caracteres.',
        ];
    }
}
