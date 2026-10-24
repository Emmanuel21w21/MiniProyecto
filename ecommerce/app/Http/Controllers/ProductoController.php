<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ProductoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Producto::class);

        return view('productos.index', [
            'productos' => Producto::with(['usuario', 'categorias'])->latest()->paginate(12),
            'categoriaSeleccionada' => null,
        ]);
    }

    public function porCategoria(Categoria $categoria)
    {
        $this->authorize('viewAny', Producto::class);

        return view('productos.index', [
            'productos' => $categoria->productos()
                ->with(['usuario', 'categorias'])
                ->latest()
                ->paginate(12),
            'categoriaSeleccionada' => $categoria,
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
        $imagen = $datos['imagen'] ?? null;
        unset($datos['imagen']);

        if ($imagen instanceof UploadedFile) {
            $datos['imagen_path'] = $this->guardarImagen($imagen);
        }

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
        $imagen = $datos['imagen'] ?? null;
        unset($datos['imagen']);
        $imagenAnterior = $producto->imagen_path;

        if ($imagen instanceof UploadedFile) {
            $datos['imagen_path'] = $this->guardarImagen($imagen);
        }

        $producto->update($datos);
        $producto->categorias()->sync($categorias);

        if ($imagen instanceof UploadedFile && $imagenAnterior !== null) {
            $this->eliminarImagen($imagenAnterior);
        }

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
        $imagenPath = $producto->imagen_path;
        $producto->delete();

        if ($imagenPath !== null) {
            $this->eliminarImagen($imagenPath);
        }

        Log::channel('productos')->info('Producto eliminado', [
            'producto_id' => $productoId,
            'usuario_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return redirect()->route('productos.index')->with('exito', 'Producto eliminado correctamente.');
    }

    private function guardarImagen(UploadedFile $imagen): string
    {
        $ruta = $imagen->storePublicly('productos', 'public');

        if ($ruta === false) {
            throw new RuntimeException('No se pudo guardar la imagen del producto.');
        }

        return $ruta;
    }

    private function eliminarImagen(string $ruta): void
    {
        $disco = Storage::disk('public');

        if ($disco->exists($ruta) && ! $disco->delete($ruta)) {
            throw new RuntimeException('No se pudo eliminar la imagen anterior del producto.');
        }
    }
}
