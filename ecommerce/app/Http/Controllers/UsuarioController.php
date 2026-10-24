<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;

/**
 * Administradores gestionan usuarios; los gerentes solo editan clientes.
 */
class UsuarioController extends Controller
{
    /** READ: lista paginada de usuarios, con búsqueda y filtro por rol. */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Usuario::class);
        $usuarios = Usuario::query()
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $termino = $request->input('buscar');
                $q->where(function ($q2) use ($termino) {
                    $q2->where('nombre', 'like', "%{$termino}%")
                        ->orWhere('apellidos', 'like', "%{$termino}%")
                        ->orWhere('correo', 'like', "%{$termino}%");
                });
            })
            ->when($request->filled('rol'), fn ($q) => $q->where('rol', $request->input('rol')))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => Usuario::ROLES,
        ]);
    }

    /** CREATE: formulario de alta. */
    public function create()
    {
        $this->authorize('create', Usuario::class);

        return view('usuarios.create', ['roles' => Usuario::ROLES]);
    }

    /** CREATE: guarda el nuevo usuario. */
    public function store(UsuarioRequest $request)
    {
        $this->authorize('create', Usuario::class);
        Usuario::create($request->validated());

        return redirect()->route('usuarios.index')
            ->with('exito', 'Usuario creado correctamente.');
    }

    /** UPDATE: formulario de edición. */
    public function edit(Usuario $usuario)
    {
        $this->authorize('update', $usuario);

        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles' => auth()->user()->esAdministrador()
                ? Usuario::ROLES
                : [Usuario::ROL_CLIENTE],
        ]);
    }

    /** UPDATE: guarda los cambios. Si la contraseña viene vacía, se conserva la actual. */
    public function update(UsuarioRequest $request, Usuario $usuario)
    {
        $this->authorize('update', $usuario);
        $datos = $request->validated();

        if (empty($datos['clave'])) {
            unset($datos['clave']);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')
            ->with('exito', 'Usuario actualizado correctamente.');
    }

    /** DELETE: elimina un usuario autorizado por la policy. */
    public function destroy(Usuario $usuario)
    {
        $this->authorize('delete', $usuario);
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('exito', 'Usuario eliminado correctamente.');
    }
}
