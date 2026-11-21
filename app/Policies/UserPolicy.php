<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Usuario;

class UserPolicy
{
    /**
     * Determina si el usuario autenticado puede gestionar el módulo de usuarios.
     */
    public function viewAny(Usuario $authUser): bool
    {
        return $authUser->esAdmin() && $authUser->activo;
    }

    public function view(Usuario $authUser, Usuario $model): bool
    {
        return $authUser->esAdmin() && $authUser->activo;
    }

    public function create(Usuario $authUser): bool
    {
        return $authUser->esAdmin() && $authUser->activo;
    }

    public function update(Usuario $authUser, Usuario $targetUser): bool
    {
        return $authUser->esAdmin() && $authUser->activo;
    }

    /**
     * Un Administrador no puede desactivarse a sí mismo.
     * Tampoco se puede desactivar al último Administrador activo del sistema.
     */
    public function toggleActivo(Usuario $authUser, Usuario $targetUser): bool
    {
        if (!$authUser->esAdmin() || !$authUser->activo) {
            return false;
        }

        // No puede desactivarse a sí mismo
        if ($authUser->id === $targetUser->id) {
            return false;
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
                return false;
            }
        }

        return true;
    }

    /**
     * No puede eliminarse a sí mismo ni al último administrador.
     */
    public function delete(Usuario $authUser, Usuario $targetUser): bool
    {
        return $this->toggleActivo($authUser, $targetUser);
    }

    public function resendAccess(Usuario $authUser, Usuario $targetUser): bool
    {
        return $authUser->esAdmin() && $authUser->activo && $targetUser->activo;
    }
}
