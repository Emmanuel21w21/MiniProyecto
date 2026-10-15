<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * CRUD de usuarios. Solo accesible para el Gerente (ver routes/web.php).
 */
class UsuarioController extends Controller
{
    /** READ: lista paginada de usuarios, con búsqueda y filtro por rol. */
    public function index(Request $request)
    {
        $usuarios = User::query()
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $termino = $request->input('buscar');
                $q->where(function ($q2) use ($termino) {
                    $q2->where('name', 'like', "%{$termino}%")
                       ->orWhere('email', 'like', "%{$termino}%");
                });
            })
            ->when($request->filled('rol'), fn ($q) => $q->where('rol', $request->input('rol')))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => User::ROLES,
        ]);
    }

    /** CREATE: formulario de alta. */
    public function create()
    {
        return view('usuarios.create', ['roles' => User::ROLES]);
    }

    /** CREATE: guarda el nuevo usuario. */
    public function store(UsuarioRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('usuarios.index')
            ->with('exito', 'Usuario creado correctamente.');
    }

    /** UPDATE: formulario de edición. */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles' => User::ROLES,
        ]);
    }

    /** UPDATE: guarda los cambios. Si la contraseña viene vacía, se conserva la actual. */
    public function update(UsuarioRequest $request, User $usuario)
    {
        $datos = $request->validated();

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        // Evita que el único gerente se quite el rol a sí mismo y deje el sistema sin administración.
        if ($usuario->esGerente()
            && $datos['rol'] !== User::ROL_GERENTE
            && User::where('rol', User::ROL_GERENTE)->count() === 1) {
            return back()
                ->withInput()
                ->withErrors(['rol' => 'Debe existir al menos un gerente en el sistema.']);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')
            ->with('exito', 'Usuario actualizado correctamente.');
    }

    /** DELETE: elimina un usuario (no permite eliminarse a sí mismo). */
    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('exito', 'Usuario eliminado correctamente.');
    }
}
