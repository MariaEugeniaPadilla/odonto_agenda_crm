<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determina si el usuario autenticado puede gestionar el módulo de usuarios.
     */
    public function viewAny(Usuario $authUser): Response
    {
        return ($authUser->esAdmin() && $authUser->activo)
            ? Response::allow()
            : Response::deny('No tiene permisos para realizar esta acción.');
    }

    public function view(Usuario $authUser, Usuario $model): Response
    {
        return ($authUser->esAdmin() && $authUser->activo)
            ? Response::allow()
            : Response::deny('No tiene permisos para realizar esta acción.');
    }

    public function create(Usuario $authUser): Response
    {
        return ($authUser->esAdmin() && $authUser->activo)
            ? Response::allow()
            : Response::deny('No tiene permisos para realizar esta acción.');
    }

    public function update(Usuario $authUser, Usuario $targetUser): Response
    {
        return ($authUser->esAdmin() && $authUser->activo)
            ? Response::allow()
            : Response::deny('No tiene permisos para realizar esta acción.');
    }

    /**
     * Un Administrador no puede desactivarse a sí mismo.
     * Tampoco se puede desactivar al último Administrador activo del sistema.
     */
    public function toggleActivo(Usuario $authUser, Usuario $targetUser): Response
    {
        if (!$authUser->esAdmin() || !$authUser->activo) {
            return Response::deny('No tiene permisos para realizar esta acción.');
        }

        // No puede desactivarse a sí mismo
        if ($authUser->id === $targetUser->id) {
            return Response::deny('No puede desactivar su propia cuenta.');
        }

        // Si se va a desactivar a un Administrador, debe haber al menos otro Administrador activo
        if ($targetUser->esAdmin() && $targetUser->activo) {
            $adminRoleId = Role::where('nombre', Role::ADMINISTRADOR)->value('id');
            $adminsActivos = Usuario::where('activo', true)
                ->where(function ($q) use ($adminRoleId) {
                    $q->where('role_id', $adminRoleId)
                      ->orWhere('rol', 'admin');
                })
                ->where('id', '!=', $targetUser->id)
                ->count();

            if ($adminsActivos === 0) {
                return Response::deny('Debe existir al menos un administrador activo.');
            }
        }

        return Response::allow();
    }

    /**
     * No puede eliminarse a sí mismo ni al último administrador.
     */
    public function delete(Usuario $authUser, Usuario $targetUser): Response
    {
        return $this->toggleActivo($authUser, $targetUser);
    }

    public function resendAccess(Usuario $authUser, Usuario $targetUser): Response
    {
        return ($authUser->esAdmin() && $authUser->activo && $targetUser->activo)
            ? Response::allow()
            : Response::deny('No tiene permisos para realizar esta acción.');
    }
}
