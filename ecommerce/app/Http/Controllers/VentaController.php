<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Http\Requests\ValidarVentaRequest;
use App\Mail\VentaValidadaCompradorMail;
use App\Mail\VentaValidadaVendedorMail;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Venta::class);
        $usuario = request()->user();
        $consulta = Venta::with(['producto', 'cliente', 'vendedor'])->latest('fecha');

        if (! $usuario->can('viewAll', Venta::class)) {
            $consulta->where('cliente_id', $usuario->id);
        }

        return view('ventas.index', ['ventas' => $consulta->paginate(15)]);
    }

    public function create()
    {
        $this->authorize('create', Venta::class);

        return view('ventas.create', [
            'productos' => Producto::where('existencia', '>', 0)->where('usuario_id', '!=', request()->user()->id)->orderBy('nombre')->get(),
        ]);
    }

    public function store(StoreVentaRequest $request)
    {
        $this->authorize('create', Venta::class);
        $datos = $request->validated();

        $venta = DB::transaction(function () use ($request, $datos): Venta {
            $producto = Producto::query()->lockForUpdate()->findOrFail($datos['producto_id']);

            if ($producto->usuario_id === $request->user()->id) {
                throw ValidationException::withMessages([
                    'producto_id' => 'No puedes comprar tus propios productos.',
                ]);
            }

            if ($producto->existencia < 1) {
                throw ValidationException::withMessages([
                    'producto_id' => 'El producto ya no tiene existencias disponibles.',
                ]);
            }

            $producto->decrement('existencia');

            return Venta::create([
                'producto_id' => $producto->id,
                'vendedor_id' => $producto->usuario_id,
                'cliente_id' => $request->user()->id,
                'fecha' => $datos['fecha'],
                'total' => $producto->precio,
                'estado' => 'pendiente',
            ]);
        });

        Log::channel('ventas')->info('Venta creada', [
            'venta_id' => $venta->id,
            'producto_id' => $venta->producto_id,
            'vendedor_id' => $venta->vendedor_id,
            'cliente_id' => $venta->cliente_id,
            'total' => $venta->total,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('ventas.index')->with('exito', 'Compra realizada correctamente.');
    }

    public function edit(Venta $venta)
    {
        $this->authorize('update', $venta);

        return view('ventas.edit', [
            'venta' => $venta,
            'productos' => Producto::orderBy('nombre')->get(),
            'usuarios' => Usuario::orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateVentaRequest $request, Venta $venta)
    {
        $this->authorize('update', $venta);
        $datos = $request->validated();

        DB::transaction(function () use ($datos, $venta): void {
            $actual = Producto::query()->lockForUpdate()->findOrFail($venta->producto_id);
            $nuevo = Producto::query()->lockForUpdate()->findOrFail($datos['producto_id']);

            if ($actual->isNot($nuevo)) {
                if ($nuevo->existencia < 1) {
                    throw ValidationException::withMessages([
                        'producto_id' => 'El producto nuevo no tiene existencias disponibles.',
                    ]);
                }

                $actual->increment('existencia');
                $nuevo->decrement('existencia');
            }

            $venta->update($datos + ['vendedor_id' => $nuevo->usuario_id]);
        });

        return redirect()->route('ventas.index')->with('exito', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $this->authorize('delete', $venta);
        DB::transaction(function () use ($venta): void {
            Producto::query()->whereKey($venta->producto_id)->lockForUpdate()->firstOrFail()
                ->increment('existencia');
            $venta->delete();
        });

        return redirect()->route('ventas.index')->with('exito', 'Venta eliminada y existencia restaurada.');
    }

    public function validar(ValidarVentaRequest $request, Venta $venta)
    {
        $venta->update([
            'estado' => 'validada',
            'fecha_validacion' => now(),
        ]);

        Log::channel('ventas')->info('Venta validada', [
            'venta_id' => $venta->id,
            'gerente_id' => $request->user()->id,
        ]);

        $venta->load(['producto', 'cliente', 'vendedor']);
        Mail::to($venta->vendedor->correo)->send(new VentaValidadaVendedorMail($venta));
        Mail::to($venta->cliente->correo)->send(new VentaValidadaCompradorMail($venta));

        return redirect()->route('ventas.index')->with('exito', 'Venta validada correctamente.');
    }
}