<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Odontologo;
use App\Models\Role;
use App\Models\Usuario;
use App\Notifications\CuentaCreadaAdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Listado con búsqueda, filtro por rol, filtro por estado y paginación.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Usuario::class);

        $query = Usuario::with(['role', 'odontologo', 'creador'])
            ->orderBy('id', 'desc');

        // Búsqueda por texto (nombre, correo o documento)
        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%")
                  ->orWhere('documento_identidad', 'like', "%{$buscar}%");
            });
        }

        // Filtro por rol
        if ($rol = $request->input('rol')) {
            $query->whereHas('role', function ($q) use ($rol) {
                $q->where('nombre', $rol);
            })->orWhere('rol', strtolower($rol));
        }

        // Filtro por estado activo/inactivo
        if ($request->has('activo') && $request->input('activo') !== '' && $request->input('activo') !== null) {
            $query->where('activo', filter_var($request->input('activo'), FILTER_VALIDATE_BOOLEAN));
        }

        $perPage = (int) $request->input('per_page', 10);
        $usuarios = $query->paginate($perPage);

        // Mapeo seguro para vista o API
        $usuariosTransformados = $usuarios->through(function ($u) {
            return [
                'id' => $u->id,
                'nombre' => $u->nombre,
                'correo' => $u->correo,
                'telefono' => $u->telefono,
                'documento_identidad' => $u->documento_identidad,
                'rol' => $u->nombre_rol,
                'role_id' => $u->role_id,
                'activo' => (bool) $u->activo,
                'debe_cambiar_contrasena' => (bool) $u->debe_cambiar_contrasena,
                'creado_por' => $u->creador?->nombre ?? 'Sistema',
                'created_at' => $u->created_at ? $u->created_at->format('Y-m-d H:i') : null,
                'odontologo' => $u->odontologo ? [
                    'especialidad' => $u->odontologo->especialidad,
                    'registro_medico' => $u->odontologo->registro_medico,
                ] : null,
                'puede_desactivar' => Auth::user()?->can('toggleActivo', $u) ?? false,
                'puede_editar' => Auth::user()?->can('update', $u) ?? false,
            ];
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $usuariosTransformados->items(),
                'pagination' => [
                    'total' => $usuariosTransformados->total(),
                    'current_page' => $usuariosTransformados->currentPage(),
                    'last_page' => $usuariosTransformados->lastPage(),
                    'per_page' => $usuariosTransformados->perPage(),
                ],
                'roles_disponibles' => Role::rolesDisponibles(),
            ]);
        }

        return view('admin.usuarios.index', [
            'usuarios' => $usuariosTransformados,
            'roles' => Role::rolesDisponibles(),
        ]);
    }

    /**
     * Crear un nuevo usuario desde la interfaz de administración.
     */
    public function store(StoreUsuarioRequest $request)
    {
        $data = $request->validated();

        $rolModel = Role::where('nombre', $data['rol'])->first();
        $roleId = $rolModel ? $rolModel->id : 4; // default Paciente

        DB::beginTransaction();
        try {
            $metodo = $data['metodo_contrasena'];
            $contrasenaTextoPlano = null;
            $debeCambiar = false;

            if ($metodo === 'manual') {
                $contrasenaHash = Hash::make($data['contrasena']);
                $contrasenaTextoPlano = $data['contrasena'];
                $debeCambiar = true; // Por regla de negocio
            } else {
                // Generar contraseña aleatoria temporal mientras el usuario define la suya
                $contrasenaHash = Hash::make(Str::random(32));
            }

            // Normalización del rol corto para compatibilidad histórica con enums
            $rolShortMap = [
                Role::ADMINISTRADOR => 'admin',
                Role::ODONTOLOGO => 'odontologo',
                Role::RECEPCIONISTA => 'recepcionista',
                Role::PACIENTE => 'paciente',
            ];

            $usuario = Usuario::create([
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'contrasena' => $contrasenaHash,
                'telefono' => $data['telefono'],
                'documento_identidad' => $data['documento_identidad'] ?? null,
                'role_id' => $roleId,
                'rol' => $rolShortMap[$data['rol']] ?? 'paciente',
                'activo' => isset($data['activo']) ? (bool) $data['activo'] : true,
                'created_by' => Auth::id(),
                'debe_cambiar_contrasena' => $debeCambiar,
                'faltas_acumuladas' => 0,
            ]);

            // Si es odontólogo, crear su registro complementario
            if ($data['rol'] === Role::ODONTOLOGO) {
                Odontologo::create([
                    'usuario_id' => $usuario->id,
                    'especialidad' => $data['especialidad'] ?? 'Odontología General',
                    'registro_medico' => $data['registro_medico'] ?? null,
                    'estado' => $usuario->activo ? 'activo' : 'inactivo',
                ]);
            }

            // Enviar notificación por correo
            if ($metodo === 'enlace') {
                $token = Password::broker('usuarios')->createToken($usuario);
                $usuario->notify(new CuentaCreadaAdminNotification($token));
            } else {
                $usuario->notify(new CuentaCreadaAdminNotification('', $contrasenaTextoPlano));
            }

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Usuario '{$usuario->nombre}' creado exitosamente con rol {$data['rol']}.",
                    'usuario' => $usuario->load(['role', 'odontologo']),
                ], 201);
            }

            return redirect()->route('admin.usuarios.index')->with('success', "Usuario creado exitosamente.");
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el usuario: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    /**
     * Actualizar datos y rol del usuario.
     */
    public function update(UpdateUsuarioRequest $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $this->authorize('update', $usuario);

        $data = $request->validated();

        // Regla de seguridad: un Administrador no puede quitarse el rol de Administrador a sí mismo
        if (Auth::id() === $usuario->id && $usuario->esAdmin() && $data['rol'] !== Role::ADMINISTRADOR) {
            return response()->json([
                'success' => false,
                'message' => 'No puede removerse el rol de Administrador a su propia cuenta.',
            ], 422);
        }

        // Si se cambia el rol a otro diferente de Administrador, verificar que no quede el sistema sin Administradores
        if ($usuario->esAdmin() && $data['rol'] !== Role::ADMINISTRADOR) {
            $adminRoleId = Role::where('nombre', Role::ADMINISTRADOR)->value('id');
            $otrosAdmins = Usuario::where('activo', true)
                ->where(function ($q) use ($adminRoleId) {
                    $q->where('role_id', $adminRoleId)
                      ->orWhere('rol', 'admin');
                })
                ->where('id', '!=', $usuario->id)
                ->count();

            if ($otrosAdmins === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede cambiar el rol: el sistema debe conservar al menos un Administrador activo.',
                ], 422);
            }
        }

        $rolModel = Role::where('nombre', $data['rol'])->first();
        $roleId = $rolModel ? $rolModel->id : $usuario->role_id;

        $rolShortMap = [
            Role::ADMINISTRADOR => 'admin',
            Role::ODONTOLOGO => 'odontologo',
            Role::RECEPCIONISTA => 'recepcionista',
            Role::PACIENTE => 'paciente',
        ];

        DB::beginTransaction();
        try {
            $usuario->nombre = $data['nombre'];
            $usuario->correo = $data['correo'];
            $usuario->telefono = $data['telefono'];
            $usuario->documento_identidad = $data['documento_identidad'] ?? null;
            $usuario->role_id = $roleId;
            $usuario->rol = $rolShortMap[$data['rol']] ?? 'paciente';
            $usuario->updated_by = Auth::id();

            if (!empty($data['contrasena'])) {
                $usuario->contrasena = Hash::make($data['contrasena']);
                $usuario->debe_cambiar_contrasena = true;
            }

            if (isset($data['activo'])) {
                // Verificar que no se desactive a sí mismo ni al último admin
                if (!$data['activo']) {
                    if (!$this->authorize('toggleActivo', $usuario)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No está autorizado para desactivar este usuario.',
                        ], 403);
                    }
                }
                $usuario->activo = (bool) $data['activo'];
            }

            $usuario->save();

            // Sincronizar o crear registro de Odontólogo si aplica
            if ($data['rol'] === Role::ODONTOLOGO) {
                Odontologo::updateOrCreate(
                    ['usuario_id' => $usuario->id],
                    [
                        'especialidad' => $data['especialidad'] ?? 'Odontología General',
                        'registro_medico' => $data['registro_medico'] ?? null,
                        'estado' => $usuario->activo ? 'activo' : 'inactivo',
                    ]
                );
            }

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Usuario '{$usuario->nombre}' actualizado con éxito.",
                    'usuario' => $usuario->fresh(['role', 'odontologo']),
                ]);
            }

            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    /**
     * Activar o desactivar usuario con validación de seguridad.
     */
    public function toggleActivo(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        if (!$this->authorize('toggleActivo', $usuario)) {
            return response()->json([
                'success' => false,
                'message' => 'Operación no permitida: no puede desactivarse a sí mismo ni desactivar al único Administrador activo del sistema.',
            ], 403);
        }

        $nuevoEstado = !$usuario->activo;
        $usuario->activo = $nuevoEstado;
        $usuario->updated_by = Auth::id();
        $usuario->save();

        // Actualizar estado del odontólogo si existe
        if ($usuario->odontologo) {
            $usuario->odontologo->update(['estado' => $nuevoEstado ? 'activo' : 'inactivo']);
        }

        $estadoTexto = $nuevoEstado ? 'activado' : 'desactivado';

        return response()->json([
            'success' => true,
            'message' => "El usuario '{$usuario->nombre}' ha sido {$estadoTexto} exitosamente.",
            'activo' => $usuario->activo,
        ]);
    }

    /**
     * Reenviar correo con enlace seguro de acceso.
     */
    public function resendAccess(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $this->authorize('resendAccess', $usuario);

        if (!$usuario->activo) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede enviar acceso a un usuario inactivo. Debe activarlo primero.',
            ], 422);
        }

        try {
            $token = Password::broker('usuarios')->createToken($usuario);
            $usuario->notify(new CuentaCreadaAdminNotification($token));

            return response()->json([
                'success' => true,
                'message' => "Enlace seguro de acceso reenviado a {$usuario->correo}.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reenviar el correo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar usuario (SoftDeletes).
     */
    public function destroy(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $this->authorize('delete', $usuario);

        $usuario->activo = false;
        $usuario->updated_by = Auth::id();
        $usuario->save();
        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => "Usuario '{$usuario->nombre}' eliminado del sistema (SoftDeletes).",
        ]);
    }
}
