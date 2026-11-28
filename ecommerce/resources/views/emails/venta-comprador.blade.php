<h2>Tu compra fue validada</h2>
<p>La venta #{{ $venta->id }} fue validada por el gerente.</p>
<p><strong>Producto:</strong> {{ $venta->producto->nombre }}</p>
<p><strong>Total:</strong> ${{ number_format((float) $venta->total, 2) }}</p>
<h3>Contacta al vendedor</h3>
<p>Para coordinar la entrega y el pago, comunícate directamente con el vendedor:</p>
<p><strong>Nombre:</strong> {{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellidos }}</p>
<p><strong>Correo:</strong> {{ $venta->vendedor->email }}</p>