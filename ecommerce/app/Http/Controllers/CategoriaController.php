<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Categoria::class);

        return view('categorias.index', [
            'categorias' => Categoria::withCount('productos')->orderBy('nombre')->paginate(15),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Categoria::class);

        return view('categorias.create');
    }

    public function store(StoreCategoriaRequest $request)
    {
        $this->authorize('create', Categoria::class);
        Categoria::create($request->validated());

        return redirect()->route('categorias.index')->with('exito', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        $this->authorize('update', $categoria);

        return view('categorias.edit', compact('categoria'));
    }

    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $this->authorize('update', $categoria);
        $categoria->update($request->validated());

        return redirect()->route('categorias.index')->with('exito', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $this->authorize('delete', $categoria);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('exito', 'Categoría eliminada correctamente.');
    }
}
