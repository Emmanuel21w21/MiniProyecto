<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Producto::class);

        return view('productos.index', [
            'productos' => Producto::with(['usuario', 'categorias'])->latest()->paginate(12),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Producto::class);

        return view('productos.create', ['categorias' => Categoria::orderBy('nombre')->get()]);
    }

    public function store(StoreProductoRequest $request)
    {
        $this->authorize('create', Producto::class);
        $datos = $request->validated();
        $categorias = $datos['categorias'] ?? [];
        unset($datos['categorias']);

        $producto = $request->user()->productos()->create($datos);
        $producto->categorias()->sync($categorias);

        Log::channel('productos')->info('Producto creado', [
            'producto_id' => $producto->id,
            'usuario_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);

        return view('productos.edit', [
            'producto' => $producto->load('categorias'),
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $this->authorize('update', $producto);
        $datos = $request->validated();
        $categorias = $datos['categorias'] ?? [];
        unset($datos['categorias']);

        $producto->update($datos);
        $producto->categorias()->sync($categorias);

        Log::channel('productos')->info('Producto editado', [
            'producto_id' => $producto->id,
            'usuario_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);
        $productoId = $producto->id;
        $producto->delete();

        Log::channel('productos')->info('Producto eliminado', [
            'producto_id' => $productoId,
            'usuario_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto eliminado correctamente.');
    }
}
