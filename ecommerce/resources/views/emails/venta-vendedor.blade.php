<h2>¡Tu producto fue vendido!</h2>
<p>La venta #{{ $venta->id }} fue validada por el gerente.</p>
<p><strong>Producto:</strong> {{ $venta->producto->nombre }}</p>
<p><strong>Total:</strong> ${{ number_format((float) $venta->total, 2) }}</p>
<h3>Datos del comprador</h3>
<p><strong>Nombre:</strong> {{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</p>
<p><strong>Correo:</strong> {{ $venta->cliente->correo }}</p>