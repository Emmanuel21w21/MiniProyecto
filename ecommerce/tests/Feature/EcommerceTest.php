<?php

namespace Tests\Feature;

use App\Mail\CodigoVerificacionMail;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_sends_a_verification_code_without_authenticating(): void
    {
        Mail::fake();
        $this->travelTo(now()->startOfSecond());
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.prueba@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);

        $this->post(route('login.enviar'), [
            'email' => $cliente->correo,
            'password' => '123',
        ])->assertRedirect(route('login.codigo'));

        $this->assertGuest();
        $codigo = $cliente->codigosVerificacion()->firstOrFail();
        $this->assertMatchesRegularExpression('/^\d{6}$/', (string) $codigo->codigo);
        $this->assertFalse($codigo->usado);
        $this->assertTrue($codigo->fecha_expiracion->equalTo(now()->addMinutes(5)));
        Mail::assertSent(CodigoVerificacionMail::class, fn (CodigoVerificacionMail $mail) => $mail->hasTo($cliente->correo)
            && $mail->codigo === (string) $codigo->codigo);
    }

    public function test_correct_verification_code_completes_login_and_cannot_be_reused(): void
    {
        Mail::fake();
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.verificacion@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);

        $this->post(route('login.enviar'), [
            'email' => $cliente->correo,
            'password' => '123',
        ])->assertRedirect(route('login.codigo'));

        $codigo = $cliente->codigosVerificacion()->firstOrFail();

        $this->get(route('login.codigo'))->assertOk();
        $this->post(route('login.codigo.verificar'), [
            'codigo' => (string) $codigo->codigo,
        ])->assertRedirect(route('dashboard.cliente'));

        $this->assertAuthenticatedAs($cliente);
        $this->assertDatabaseHas('codigos_verificacion', [
            'id' => $codigo->id,
            'usado' => true,
        ]);
    }

    public function test_invalid_credentials_do_not_create_or_send_a_verification_code(): void
    {
        Mail::fake();
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.invalido@tuxtla.tecnm.mx',
        ]);

        $this->post(route('login.enviar'), [
            'email' => $cliente->correo,
            'password' => 'incorrecta',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseCount('codigos_verificacion', 0);
        Mail::assertNothingSent();
    }

    public function test_incorrect_verification_code_does_not_authenticate(): void
    {
        Mail::fake();
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.codigo-incorrecto@tuxtla.tecnm.mx',
        ]);

        $this->post(route('login.enviar'), [
            'email' => $cliente->correo,
            'password' => '123',
        ]);

        $this->post(route('login.codigo.verificar'), [
            'codigo' => '123',
        ])->assertRedirect(route('login.codigo'))
            ->assertSessionHasErrors('codigo');

        $this->post(route('login.codigo.verificar'), [
            'codigo' => '000000',
        ])->assertRedirect(route('login.codigo'))
            ->assertSessionHasErrors('codigo');

        $this->assertGuest();
    }

    public function test_expired_verification_code_cannot_complete_login(): void
    {
        Mail::fake();
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.codigo-expirado@tuxtla.tecnm.mx',
        ]);

        $this->post(route('login.enviar'), [
            'email' => $cliente->correo,
            'password' => '123',
        ]);
        $this->travel(6)->minutes();

        $this->post(route('login.codigo.verificar'), [
            'codigo' => (string) $cliente->codigosVerificacion()->firstOrFail()->codigo,
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_usuario_can_resolve_categories_through_owned_products(): void
    {
        $vendedor = Usuario::factory()->create([
            'correo' => 'vendedor.prueba@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $categoria = Categoria::create(['nombre' => 'Consolas']);
        $producto = $vendedor->productos()->create([
            'nombre' => 'Consola',
            'descripcion' => 'Consola de prueba',
            'precio' => 2500,
            'existencia' => 3,
        ]);
        $producto->categorias()->attach($categoria);

        $this->assertTrue($vendedor->categorias->contains('id', $categoria->id));
    }

    public function test_manager_can_edit_clients_but_not_other_roles(): void
    {
        $gerente = Usuario::factory()->create([
            'correo' => 'gerente.prueba@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente2.prueba@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $administrador = Usuario::factory()->create([
            'correo' => 'admin.prueba@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_ADMINISTRADOR,
        ]);

        $this->actingAs($gerente)
            ->get(route('usuarios.edit', $cliente))
            ->assertOk();
        $this->get(route('usuarios.edit', $administrador))->assertForbidden();
        $this->get(route('usuarios.create'))->assertForbidden();
    }

    public function test_administrator_can_create_users_and_manager_cannot_promote_clients(): void
    {
        $administrador = Usuario::factory()->create([
            'correo' => 'admin.creacion@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_ADMINISTRADOR,
        ]);

        $this->actingAs($administrador)->post(route('usuarios.store'), [
            'nombre' => 'Nuevo',
            'apellidos' => 'Cliente',
            'correo' => 'nuevo.cliente@tuxtla.tecnm.mx',
            'clave' => 'Seguro123',
            'clave_confirmation' => 'Seguro123',
            'rol' => Usuario::ROL_CLIENTE,
        ])->assertRedirect(route('usuarios.index'));

        $cliente = Usuario::where('correo', 'nuevo.cliente@tuxtla.tecnm.mx')->firstOrFail();
        $gerente = Usuario::factory()->create([
            'correo' => 'gerente.promocion@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);

        $this->actingAs($gerente)->put(route('usuarios.update', $cliente), [
            'nombre' => $cliente->nombre,
            'apellidos' => $cliente->apellidos,
            'correo' => $cliente->correo,
            'rol' => Usuario::ROL_ADMINISTRADOR,
        ])->assertSessionHasErrors('rol');

        $this->assertDatabaseHas('users', [
            'id' => $cliente->id,
            'rol' => Usuario::ROL_CLIENTE,
        ]);
    }

    public function test_customer_can_browse_products_but_cannot_manage_catalog(): void
    {
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.catalogo@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $gerente = Usuario::factory()->create([
            'correo' => 'gerente.catalogo@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);

        $this->actingAs($cliente)
            ->get(route('productos.index'))
            ->assertOk();
        $this->get(route('productos.create'))->assertForbidden();
        $this->actingAs($gerente)->get(route('productos.create'))->assertOk();
    }

    public function test_category_links_show_only_products_in_that_category(): void
    {
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.categorias@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $vendedor = Usuario::factory()->create([
            'correo' => 'vendedor.categorias@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $consolas = Categoria::create(['nombre' => 'Consolas']);
        $accesorios = Categoria::create(['nombre' => 'Accesorios']);
        $productoConsola = $vendedor->productos()->create([
            'nombre' => 'Consola de prueba',
            'descripcion' => 'Descripción de consola',
            'precio' => 100,
            'existencia' => 1,
        ]);
        $productoAccesorio = $vendedor->productos()->create([
            'nombre' => 'Control de prueba',
            'descripcion' => 'Descripción de control',
            'precio' => 50,
            'existencia' => 1,
        ]);
        $productoConsola->categorias()->attach($consolas);
        $productoAccesorio->categorias()->attach($accesorios);

        $this->actingAs($cliente)
            ->get(route('categorias.productos', $consolas))
            ->assertOk()
            ->assertSee('Consola de prueba')
            ->assertDontSee('Control de prueba')
            ->assertSee('Ver todos los productos');
    }

    public function test_manager_can_complete_product_and_category_crud(): void
    {
        $gerente = Usuario::factory()->create([
            'correo' => 'gerente.crud@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $this->actingAs($gerente);

        $this->post(route('categorias.store'), [
            'nombre' => 'Consolas',
            'descripcion' => 'Consolas de videojuegos',
        ])->assertRedirect(route('categorias.index'));
        $categoria = Categoria::where('nombre', 'Consolas')->firstOrFail();

        $this->post(route('productos.store'), [
            'nombre' => 'Consola portátil',
            'descripcion' => 'Consola nueva',
            'precio' => 4999.99,
            'existencia' => 5,
            'categorias' => [$categoria->id],
        ])->assertRedirect(route('productos.index'));
        $producto = Producto::where('nombre', 'Consola portátil')->firstOrFail();
        $this->assertTrue($producto->categorias->contains('id', $categoria->id));

        $this->put(route('productos.update', $producto), [
            'nombre' => 'Consola portátil edición nueva',
            'descripcion' => 'Consola actualizada',
            'precio' => 5299.99,
            'existencia' => 6,
            'categorias' => [$categoria->id],
        ])->assertRedirect(route('productos.index'));
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Consola portátil edición nueva',
        ]);

        $this->put(route('categorias.update', $categoria), [
            'nombre' => 'Consolas portátiles',
            'descripcion' => 'Consolas compactas',
        ])->assertRedirect(route('categorias.index'));
        $this->delete(route('productos.destroy', $producto))->assertRedirect(route('productos.index'));
        $this->delete(route('categorias.destroy', $categoria))->assertRedirect(route('categorias.index'));

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    public function test_customer_purchase_decrements_stock_and_records_sale(): void
    {
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.compra@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $vendedor = Usuario::factory()->create([
            'correo' => 'vendedor.compra@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $producto = Producto::create([
            'nombre' => 'Control',
            'descripcion' => 'Control de prueba',
            'precio' => 999.50,
            'existencia' => 2,
            'usuario_id' => $vendedor->id,
        ]);

        $this->actingAs($cliente)
            ->get(route('ventas.index'))
            ->assertOk()
            ->assertSee('Mis compras');

        $this->post(route('ventas.store'), [
            'producto_id' => $producto->id,
            'fecha' => now()->toDateString(),
        ])->assertRedirect(route('ventas.index'));

        $this->assertDatabaseHas('productos', ['id' => $producto->id, 'existencia' => 1]);
        $this->assertDatabaseHas('ventas', [
            'producto_id' => $producto->id,
            'cliente_id' => $cliente->id,
            'vendedor_id' => $vendedor->id,
            'total' => 999.50,
        ]);
        $this->assertSame(1, Venta::count());
    }

    public function test_customer_can_only_see_their_own_purchases(): void
    {
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.historial@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $otroCliente = Usuario::factory()->create([
            'correo' => 'otro.historial@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $vendedor = Usuario::factory()->create([
            'correo' => 'vendedor.historial@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $productoPropio = Producto::create([
            'nombre' => 'Compra propia',
            'descripcion' => 'Descripción',
            'precio' => 100,
            'existencia' => 1,
            'usuario_id' => $vendedor->id,
        ]);
        $productoAjeno = Producto::create([
            'nombre' => 'Compra ajena',
            'descripcion' => 'Descripción',
            'precio' => 200,
            'existencia' => 1,
            'usuario_id' => $vendedor->id,
        ]);
        Venta::create([
            'producto_id' => $productoPropio->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'total' => 100,
        ]);
        Venta::create([
            'producto_id' => $productoAjeno->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $otroCliente->id,
            'fecha' => now()->toDateString(),
            'total' => 200,
        ]);

        $this->actingAs($cliente)
            ->get(route('ventas.index'))
            ->assertOk()
            ->assertSee('Compra propia')
            ->assertDontSee('Compra ajena');
    }

    public function test_demo_seeders_can_be_run_repeatedly(): void
    {
        $this->seed();
        $this->seed();

        $this->assertSame(3, Usuario::count());
        $this->assertSame(3, Categoria::count());
        $this->assertSame(2, Producto::count());
    }

    public function test_administrator_can_update_and_delete_sales_without_losing_stock(): void
    {
        $administrador = Usuario::factory()->create([
            'correo' => 'admin.ventas@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_ADMINISTRADOR,
        ]);
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.ventas@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);
        $vendedor = Usuario::factory()->create([
            'correo' => 'vendedor.ventas@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_GERENTE,
        ]);
        $productoAnterior = Producto::create([
            'nombre' => 'Producto anterior',
            'descripcion' => 'Descripción',
            'precio' => 100,
            'existencia' => 2,
            'usuario_id' => $vendedor->id,
        ]);
        $productoNuevo = Producto::create([
            'nombre' => 'Producto nuevo',
            'descripcion' => 'Descripción',
            'precio' => 200,
            'existencia' => 4,
            'usuario_id' => $administrador->id,
        ]);
        $venta = Venta::create([
            'producto_id' => $productoAnterior->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'total' => 100,
        ]);

        $this->actingAs($administrador)->put(route('ventas.update', $venta), [
            'producto_id' => $productoNuevo->id,
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'total' => 200,
        ])->assertRedirect(route('ventas.index'));

        $this->assertDatabaseHas('productos', ['id' => $productoAnterior->id, 'existencia' => 3]);
        $this->assertDatabaseHas('productos', ['id' => $productoNuevo->id, 'existencia' => 3]);
        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'producto_id' => $productoNuevo->id,
            'vendedor_id' => $administrador->id,
        ]);

        $this->delete(route('ventas.destroy', $venta))->assertRedirect(route('ventas.index'));
        $this->assertDatabaseHas('productos', ['id' => $productoNuevo->id, 'existencia' => 4]);
        $this->assertDatabaseMissing('ventas', ['id' => $venta->id]);
    }
}
