<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_model_authenticates_with_custom_credentials(): void
    {
        $cliente = Usuario::factory()->create([
            'correo' => 'cliente.prueba@tuxtla.tecnm.mx',
            'rol' => Usuario::ROL_CLIENTE,
        ]);

        $this->post(route('login.enviar'), [
            'email' => $cliente->correo,
            'password' => '123',
        ])->assertRedirect(route('dashboard.cliente'));

        $this->assertAuthenticatedAs($cliente);
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

        $this->actingAs($cliente)->post(route('ventas.store'), [
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
